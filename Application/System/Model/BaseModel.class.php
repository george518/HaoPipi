<?php
/******************************************************************
** 文件名称: BaseModel.class.php
** 功能描述: 模型抽象类，日志，字段检查等
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-06
******************************************************************/

namespace System\Model;
use Think\Model;

abstract class BaseModel extends Model
{

    public function _initialize()
    {
        $data = I();
        if(!$this->checkQueryData($data)){
            echo "非法参数";
            exit();
        }
    }
    protected function _before_insert(&$data, $options)
    {
        return $this->_data_check($data);
    }

    protected function _before_update(&$data, $options)
    {
        return $this->_data_check($data);
    }

    protected function _after_insert($data, $options)
    {
        return $this->_syslog($data, '创建数据');
    }

    protected function _after_update($data, $options)
    {
        return $this->_syslog($data, '修改数据');
    }

    protected function _after_delete($data, $options)
    {
        return $this->_syslog($data, '删除数据');
    }


    protected function checkQueryData($data)
    {
        return true;
    }


    private function _syslog($data, $operation)
    {
        //记录日志
    }




    /**
     * 初始化接口post或者get的数据，按照累中设定_fields进行设定。
     * @param unknown $filter
     * @param string $filter_pk
     * @return unknown
     */
    protected function _initializeInterfaceFields($filter = array(), $filter_pk = true)
    {
        if ($this->_fields) {
            foreach ($this->_fields as $key => $value) {
                if (!in_array($value, $filter)) {
                    $arr[$value] = $_POST[$value];
                }
            }
        } else {
            foreach ($_POST as $key => $value) {
                if (!in_array($key, $filter)) {
                    $arr[$key] = $value;
                }
            }
        }

        if ($filter_pk) {
            unset($arr[$this->pk]);
        }
        if ($this->_setLog) {
            $arr["userName"] = $_SESSION["user_name"];
            $arr["userId"] = $_SESSION["user_uid"];

        }
        if ($this->_loginToken) {
            foreach ($this->_loginToken as $key => $value) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * 获取数据参数数据列表。
     * @param string $map
     * @param number $offset
     * @param number $limit
     * @param string $url
     * @return mixed
     */
    public function _interfaceGetList($map = "", $offset = 0, $limit = 10, $url = "", $get_data = "", $doFilter=false, $timeout = 5)
    {
        if (!$url) {
            $url = C($this->_getListUrl) ? C($this->_getListUrl) : $this->_getListUrl;
        }

        $map["offset"] = $offset;
        $map["limit"] = $limit;

        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . $url);
        // 这是个 bug
        $method = 'GET';
        if(strpos($url, '/specs/specNos')
          || strpos($url, '/takeout_config')) {
            $method = 'POST';
        }

        //增加array(), false, "sign", $timeout，解决API超时  by jiaoyuanyuan
        $response = http($url, $map, $method, array(), false, "sign", $timeout);

        // 字符串出现\0造成无法json_decode解析的情况，特殊处理 @lijing151
        // @todo \1\a等这种非人类的数据也无法处理
        if ($doFilter) {
            $response = str_replace('\\0', '', $response);
        }

        $js = json_decode($response, true);
        if ($get_data) {
            return $js["data"];
        } else {
            return $js;
        }
    }

    /**
     * 获取单挑数据的详细记录
     * 默认不传真，自动默认按照pkid进行获取。
     * 如果船只，怎按照params 进行获取数据
     * @param unknown $params
     * @param string $url
     * @return boolean|mixed
     */
    public function _interfaceGetDetail($params = array(), $url = "", $method="GET")
    {
        if (!$url) {
            $url = C($this->_getDetailUrl) ? C($this->_getDetailUrl) : $this->_getDetailUrl;
        }
        if (!$params) {
            $id = $_GET["id"];
            if (!$id) {
                return false;
            } else {
                $arr[$this->pk] = $id;
            }
            if (strstr($url, "%s")) {
                if ($this->_loginToken) {
                    foreach ($this->_loginToken as $key => $value) {
                        $arr[$key] = $value;
                    }
                }
                $url = sprintf($url, $id);
                $js = json_decode(http($url, $arr, $method), true);
            } else {
                if ($this->_loginToken) {
                    foreach ($this->_loginToken as $key => $value) {
                        $arr[$key] = $value;
                    }
                }
                $js = json_decode(http($url, $arr, $method), true);
            }
        } else {
            $arr = $params;
            if ($this->_loginToken) {
                foreach ($this->_loginToken as $key => $value) {
                    $arr[$key] = $value;
                }
            }
            $id = $_GET["id"];
            $url = sprintf($url, $id);
            $js = json_decode(http($url, $arr, $method), true);
        }
        return $js;
    }


    /**
     * 添加数据(non-PHPdoc)
     * @see Model::add()
     */
    public function _interfaceAdd($url = "")
    {
        $data = $this->_initializeInterfaceFields();
        if (!$url) {
            $url = C($this->_getPostUrl) ? C($this->_getPostUrl) : $this->_getPostUrl;
        }
        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . json_encode($data));
        $js = json_decode(http($url, http_build_query($data), "POST"), true);
        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . $js);
        return $js;
    }

    public function _interfaceUpdate($url = "", $method = "POST")
    {
        $data = $this->_initializeInterfaceFields();
        if (!$url) {
            $url = C($this->_getUpdateUrl) ? C($this->_getUpdateUrl) : $this->_getUpdateUrl;
        }
        $edit_url = sprintf($url, $_POST[$this->pk]);
        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . json_encode($data));
        $js = json_decode(http($edit_url, http_build_query($data), "POST"), true);
        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . $js);

        return $js;
    }

    public function _interfaceDelete($params = array(), $url = "", $method = "GET")
    {
        if (!$url) {
            $url = C($this->_getDeleteUrl) ? C($this->_getDeleteUrl) : $this->_getDeleteUrl;
        }
        if (!$params) {
            $id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
            if (!$id) {
                return false;
            }
            if (strstr($url, "%s")) {
                $url = sprintf($url, trim($id, ","));
                if ($this->_setLog) {
                    $arr["userName"] = $_SESSION["user_name"];
                    $arr["userId"] = $_SESSION["user_uid"];

                }
                $js = json_decode(http($url, http_build_query($arr), "POST"), true);
            } else {
                if ($this->_setLog) {
                    $arr["userName"] = $_SESSION["user_name"];
                    $arr["userId"] = $_SESSION["user_uid"];
                }
                $js = json_decode(http($url, http_build_query($arr), "POST"), true);
            }
        } else {
            $arr = $params;
            $id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
            $url = sprintf($url, $id);

            if ($this->_setLog) {
                $arr["userName"] = $_SESSION["user_name"];
                $arr["userId"] = $_SESSION["user_uid"];
            }
            $js = json_decode(http($url, $arr, $method), true);
        }
        return $js;
    }


    protected function getAPIUrl($url, $id = 0)
    {
        $url = C($url) ? C($url) : $url;
        if(!$id) {
        	$id = $_POST[$this->pk] ? $_POST[$this->pk] : $_GET['id'];
        }
        return sprintf($url, $id);
    }

    protected function _data_check(&$data)
    {
        $fieldTypes = $this->fields['_type'];
        foreach ($data as $k => &$v) {
            if (isset($fieldTypes[$k])) {
                if (!$this->_type_value_check($fieldTypes[$k], $v)) {
                    throw new Exception("【" . $k . "】字段的输入值【" . $v . "】无效");
                }
            }
        }
        return true;
    }


    /**
     * 根据字段类型检查输入值合法性
     * @todo 更严格的检查
     *
     * @param type $fieldType
     * @param type $value
     * @return boolean
     */
    private function _type_value_check($fieldType, &$value)
    {
        return true;
    }

    
}
