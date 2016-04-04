<?php
/******************************************************************
** 文件名称: SystemController.class.php
** 功能描述: 模块基础类，公共逻辑
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Controller;
use Think\Controller;

class SystemController extends BaseController 
{
    /**
     * [_initialize 初始化方法]
     * @Author haodaquan
     * @Date   2016-04-03
     * @return [type]     []
     */
    public function _initialize()
    {
    	parent::_initialize();
    }

    /**
     * [_initGridFields 初始化grid字段]
     * @Author haodaquan
     * @Date   2016-04-03
     * @return [type]     [description]
     */
    protected function _initGridFields()
    {
        if (!empty($this->rowLinks)) {
            $this->showFields['_grid_actions'] = '操作';
        }
    }

    public function action()
    {
        $action = $_GET['action'];
        switch ($action) {
            default:
                if (method_exists($this, $action)) {
                    return $this->$action();
                } else {
                    $this->error('action错误:' . $action);
                }
        }
    }

    public function index()
    {
        //if(!$this->disableInterfaceSearch)
        $this->showSearch();
        //$this->_displayMmgrids();
        if ($_GET['t']) {
            $this->filterObj = 't';
            if (empty($this->pageTitle)) {
                $node = D('node')->field('node.name, pnode.name as pname')
                    ->join('node as pnode on node.pid=pnode.id')
                    ->where("node.url like '%status?t=" . $_GET['t'] . "'")->find();
                $this->pageTitle = $node['pname'] . ' - ' . $node['name'];
            }
        }
        //jiaoyuanyuan  pageTitle，增加禁用开关
        if(!$this->disablePageTitle){
            if (empty($this->entityName)) {
                $this->entityName = $this->getActionName();
            }
            $this->assign('entityName', @$this->entityName);
            if (empty($this->pageTitle)) {
                $this->pageTitle = $this->entityName . '管理';
            }
            $this->assign('pageTitle', @$this->pageTitle);
        }
        $this->assign('showFields', $this->getShowFields());
        $this->assign('columnsWidth', $this->getColumnsWidth());
        $outfields = json_encode($this->getInsertFields());
        $this->assign('insertfields', $outfields);
        $this->assign('_setSort', $this->_setSort);        //设置是否全程请求数据排序
        $this->assign('updatefields', empty($this->updatefields) ? $outfields : json_encode($this->updatefields));
        $this->assign('singleActions', json_encode($this->singleActions));
        $this->assign('batchActions', json_encode($this->batchActions));
        $this->assign('showExportExcel', isset($this->_enableExportExcel) ? $this->_enableExportExcel : 1);//默认显示导出excel按钮

        $this->assign('showPage', isset($this->_showPage) ? $this->_showPage : 1);//默认显示导出excel按钮

        $this->assign('filterObjName', $this->filterObj);
        $this->assign('filterObjValue', $_GET[$this->filterObj]);

        $this->assign('_unset_jquery',isset($this->_unset_jquery) ? $this->_unset_jquery : 1);

        $this->assign('showBackboard',$this->_showBackboard); //是否显示下拉显示例

        $this->assign('_enableAdd', @$this->_enableAdd);
        $this->assign('_enableDelete', @$this->_enableDelete);
        $this->assign('_enableEdit', @$this->_enableEdit);

        $_enableCheckCol = @$this->_enableDelete || !empty($this->batchActions);
        $this->assign('_enableCheckCol', $_enableCheckCol ? 'true' : 'false');
        $this->assign('mmgPapeList',isset($this->mmgPageList)?json_encode($this->mmgPageList):json_encode(array(10, 20, 50, 100, 200, 300, 500, 1000)));

        $this->assign('disableSearchInput', @$this->disableSearchInput);//是否显示mmgrid的搜索

        $this->assign('pk', D($this->getActionName())->getPk());
        $this->assign('pagelimit', @$this->_pageLimit);
        $this->assign('sort', @$this->_sort); // $this->_sort = array('name'=>'排序字段名','status'=>'desc/asc')
        if ($_GET) {
            $params = $this->_getParams();
            if (!empty($this->filterObj)) {
                $this->assign('getParams', $params);
            } else {
                $this->assign('getParams', ltrim($params, '&'));
            }
        }

        //加载搜索模块 liupeng70 增加禁用开关
        if(!$this->disableInterfaceSearch)
            $this->_interfaceSearch();

        $this->_displayMmgrids();
        //$this->display(APP_PATH . 'Tpl/AbstractGrid/mmgrids.html');
    }

    protected function showSearch()
    {
       $this->assign('showSearch',true); 
    }

    /**
     * [_displayMmgrids 显示列表模板]
     * @Author haodaquan
     * @Date   2016-04-04
     * @return [type]     [description]
     */
    protected function _displayMmgrids()
    {
        $this->display('Grid/mmgrid');
    }

    // protected function _displayMmgrids($params = null)
    // {
    //     layout(false);
    //     $this->display(APP_PATH . 'Tpl/AbstractGrid/mmgrids.html');
    // }

    private function _getParams()
    {
        $map = '';
        foreach ($this->getShowFields() as $k => $val) {
            $field_val = trim($_GET[$k]);
            if (isset($field_val) && $field_val != '') {
                $map .= "&" . $k . "=" . $field_val;
            }
        }
        return $map;
    }

    /**
     * 子类可重载此方法，展示不同的列
     * @return type
     */
    protected function getShowFields()
    {
        if (!empty($this->showFields)) {
            return $this->showFields;
        }

        $showFields = array();
        $fields = D($this->getActionName())->getDbFields();
        foreach ($fields as $v) {
            $showFields[$v] = $v;
        }
        $this->showFields = $showFields;
        return $this->showFields;
    }

    protected function getInsertFields()
    {
        if (!empty($this->insertfields)) {
            return $this->insertfields;
        }

        $_showFields = $this->getShowFields();
        $_insertfields = array();
        foreach ($_showFields as $k => $v) {
            $_insertfields[$k] = array(
                'title' => $v,
                'type' => array('t' => 'text', 'val' => array('type' => '', 'vals' => array())),
            );
        }
        return $this->insertfields = $_insertfields;
    }

    /**
     * 向操作栏增加一个链接
     *
     * @param type $linkText
     * @param type $urlTemplate URL模板 eg. U('sample_subitem/view?id=__pkid__')
     * @param type $targetBlank
     */
    protected function addRowLink($linkText, $urlTemplate, $targetBlank = false)
    {
        $this->rowLinks[] = array(
            'linkText' => $linkText,
            'urlTemplate' => $urlTemplate,
            'targetBlank' => $targetBlank,
        );
    }

    protected function addRowLink_fun($linkText, $urlTemplate, $fn = NULL, $targetBlank = false)
    {
        $this->rowLinks[] = array(
            'linkText' => $linkText,
            'urlTemplate' => $urlTemplate,
            'targetBlank' => $targetBlank,
            'fn' => $fn,
        );
    }

    protected function addRowLink_fun_with_id($id, $linkText, $urlTemplate, $fn = NULL, $targetBlank = false)
    {
        $this->rowLinks[] = array(
            'id' => $id,
            'linkText' => $linkText,
            'urlTemplate' => $urlTemplate,
            'targetBlank' => $targetBlank,
            'fn' => $fn,
        );
    }
     protected function addRowLink_fun_txt( $linkText, $type)
    {
        $this->rowLinks[] = array(
            'linkText' => $linkText,
            'urlTemplate' => $type,
          //  'targetBlank' => $targetBlank,

        );
    }
     protected function addRowLink_parameter( $linkText,$urlTemplate,$targetBlank, $type)
    {
        $this->rowLinks[] = array(
            'linkText' => $linkText,
            'urlTemplate' => $urlTemplate,
             'targetBlank' => $targetBlank,
            'fn'=>$type,
        );
    }

    /**
     * 向操作栏增加一项操作
     * @deprecated since version 20140730 此方法调用较复杂，请使用 addRowLink() 方法代替
     * @param type $html
     */
    protected function addSingleAction($html)
    {
        $this->singleActions[] = $html;
    }

    /**
     * 增加一个批量操作
     * @param type $actionName 需有 public function $actionName
     * @param type $buttonText 按钮文字
     * @param type $arrPostData eg. array('audit_status' => 1)  支持一个 _status_name 结尾的变量名，会根据此字段做权限检查，无权限展示灰色按钮
     * @param type $arrPostVars eg. array('start_date' => '开始日期（格式2014-07-25）', 'remark' => '备注')
     * @param int $from_status 要更改的原始状态，只作用于第一个状态字段
     */
    protected function addBatchAction($actionName, $buttonText, $arrPostData = array(), $arrPostVars = array(), $from_status = 0)
    {
        $strPostData = '';
        $hasAuth = 1;
        $op_uid = $_SESSION['user_uid'];

        foreach ($arrPostData as $k => $v) {
            $strPostData .= "&$k=$v";
            $sql = '';

            // 要修改状态，判断用户是否有此权限
            if (strpos($k, '_status_name') > 0) {
                $sql = "select * from user, auth_set, auth, enum_const "
                    . "where user.uid=$op_uid and user.agid=auth_set.agid and auth_set.aid = auth.aid "
                    . "and to_status=ecid and ecvalue='$v'";
            } else if (strpos($k, '_status') > 0) {
                $sql = "select * from user, auth_set, auth "
                    . "where user.uid=$op_uid and user.agid=auth_set.agid and auth_set.aid = auth.aid "
                    . "and to_status='$v'";
            }

            if (!empty($sql)) {
                if ($from_status > 0) {
                    $sql .= " and from_status=$from_status";
                    $from_status = 0;   // from_status 只作用于第一个状态字段
                }

                $rs = M()->execute($sql);
                if (empty($rs)) {
                    $hasAuth = 0;
                }
            }
        }

        $this->batchActions[] = array(
            'actionName' => $actionName,
            'buttonText' => $buttonText,
            'strPostData' => $strPostData,
            'arrPostVars' => $arrPostVars,
            'hasAuth' => $hasAuth,
            'arrPostData' => $arrPostData,
        );
    }

    public function add()
    {
        if (!@$this->_enableAdd) {
            $this->error("不允许添加操作！");
            return;
        }

        if (D($this->getActionName())->add($_POST) !== false) {
            $this->success('新增成功!');
        } else {
            $this->error('新增失败!');
        }
    }

    public function edit()
    {
        if (!@$this->_enableEdit) {
            $this->error("不允许修改操作！");
            return;
        }

        if (D($this->getActionName())->save($_POST) !== false) {
            $this->success('编辑成功!');
        } else {
            $this->error('编辑失败!');
        }
    }

    public function delete()
    {
        if (!@$this->_enableDelete) {
            $this->error("不允许删除操作！");
            return;
        }
        $ids = explode(',', $_POST['id']);
        $success = 0;//是否存在成功的操作
        $error = 0;//是否存在失败的操作
        D($this->getActionName())->startTrans();
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id > 0) {
                if (D($this->getActionName())->delete($id)) {
                    $success = 1;
                } else {
                    $error = 1;
                    break;
                }
            }
        }
        if ($error) {
            D($this->getActionName())->rollback();
            $this->error('删除失败!');
        } else if ($success) {
            D($this->getActionName())->commit();
            $this->success('删除成功!');
        }
        $this->error('删除失败!');
    }

    /**
     * 查询当前Model并且用ajax返回
     */
    public function query()
    {
        $name = $this->getActionName();
        $model = D($name);

        if (!empty($model)) {
            $sort = isset($_POST['sort']) ? $_POST['sort'] : $this->sort;
            $sort = explode('.', $sort);
            $map = '1=1';

            foreach ($this->getShowFields() as $k => $val) {
                if (isset($_POST[urlencode($k) . '_like'])) {    // FIX 中文字段名筛选后编辑，筛选丢失问题
                    $_POST[$k . '_like'] = $_POST[urlencode($k) . '_like'];
                }
                if (isset($_POST[$k . '_like'])) {
                    $field_val = trim($_POST[$k . '_like']);
                    $field_val = urldecode($field_val);  // FIXME: 不知道哪里做的编码
                    if (!empty($field_val)) {
                        // 如果搜索字段名重复，有歧义，需要显式声明 $this->searchFields
                        $field = isset($this->searchFields[$k]) ? $this->searchFields[$k] : $k;
                        $map .= " and $field like '%" . $field_val . "%' ";
                    }
                }
            }

            $list = $this->_pagedSelect($model, $map, $sort[0], $sort[1]);
        }

        $this->ajaxReturn($this->_renderGridLinks($list));
    }

    protected function _pagedSelect($model, $map, $sortBy = '', $asc = 'asc')
    {
        $pagedList = parent::_pagedSelect($model, $map, $sortBy, $asc);
        $returnList = $this->buildRowLinks($pagedList);
        return $returnList;
    }

    /**
     * @author niuxiaosai
     * @desc 将rowlinks 数据处理部分拿出来，单独处理，一边在子类中独立继承和重写
     * @param string $pagedList
     * @return string
     */
    protected function buildRowLinks($pagedList = "")
    {
        if (!empty($this->rowLinks)) {
            $pk = D($this->getActionName())->getPk();

            foreach ($pagedList['items'] as &$item) {
                foreach ($this->rowLinks as $rowLink) {
                    $item['_grid_actions'] .= '<a class="btn btn-info" href="'
                        . str_replace('__pkid__', $item[$pk], $rowLink['urlTemplate']) . '">'
                        . $rowLink['linkText'] . '</a> ';
                }
            }
        }

        return $pagedList;
    }

    /**
     * 渲染数据加链接
     * @param type $pagedList
     * @return type
     */
    private function _renderGridLinks($pagedList)
    {
        foreach ($pagedList['items'] as &$obj) {
            $id = $obj[D($this->getActionName())->getPk()];
            foreach ($obj as $k => &$v) {
                if (strpos($k, '_status_name') > 0) {
                    $fieldName = substr($k, 0, strlen($k) - 5);
                    $v = "<a target='_blank' href='" . U('status_log/index') . "/t/$id-$fieldName'>$v</a>";
                }
            }
        }
        return $pagedList;
    }

    /**
     * 查询当前Model并且从Excel文件输出
     */
    public function export()
    {
        $name = $this->getActionName();
        $model = D($name);
        $map = '1=1';
        $list = $this->_pagedSelect($model, $map);

        return $this->outputExcel($list['items'], $this->getShowFields());
    }

    /**
     * 把数据写入Excel文件并输出
     *
     * @param type $arrData
     * @param type $arrFields
     */
    protected function outputExcel($arrData, $arrFields, $outputFileName = '', $setOwnType = false)
    {
        //导入thinkphp第三方类库
        Vendor('Excel.PHPExcel');
        $inputFileName = get_save_path() . "/templete/export_excel.xlsx";
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        // 写表头
        $i = 1;
        $j = 0;
        $res = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($arrFields as $k => $v) {
            $position = $this->_getExcelPosition($j) . $i;
            $j++;
            $res->setCellValue($position, $v);           
        }

        // 写数据区域
        foreach ($arrData as $val) {
            $i++;
            $j = 0;
            foreach ($arrFields as $k => $v) {
                $position = $this->_getExcelPosition($j) . $i;
                $j++;
                $value = $setOwnType ? $val[$k] : " ".strip_tags($val[$k]); 
                $res->setCellValue($position, is_array($val[$k]) ? implode(",", $val[$k]) : $value);
            }
        }
        // HTTP输出
        $outputFileName = $outputFileName ? $outputFileName : ($this->pageTitle ? $this->pageTitle : $this->getActionName()) . '.xlsx';
        header("Content-Type: application/force-download");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE" ) > 0) {
            header("Content-Disposition:attachment;filename=" . str_ireplace('+', '%20', URLEncode($outputFileName)));
        } else {
            header("Content-Disposition:attachment;filename*=UTF-8''" . str_ireplace('+', '%20', URLEncode($outputFileName)));
        }
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    private function _getExcelPosition($size)
    {
        $index = $size;
        if($size >= 26){
            $preFix = "";
            $index = $size % 26;
            $multiple = floor($size / 26) - 1;
            $preFix = $this->_getExcelColumn($multiple);
        }
        $position = $preFix . $this->_getExcelColumn($index) . $i;
        return $position;
    }

    private function _getExcelColumn($j)
    {
        // 暂未支持超过26列
        return chr(0x41 + $j);
    }

    /**
     * 把数据库查询结果用 echarts 输出
     * @param type $dbResultSet
     */
    protected function chart($dbResultSet)
    {
        $objects = $xtitles = $data = array();

        foreach ($dbResultSet[0] as $k => $v) {
            if ($k != 'xtitle') {
                $objects[] = $k;
            }
        }
        foreach ($dbResultSet as $k => $v) {
            foreach ($v as $kk => $vv) {
                if ($kk == 'xtitle') {
                    $xtitles[] = $v['xtitle'];
                } else {
                    $data[$kk][] = floatval($vv);
                }
            }
        }

        $this->assign('objects', $objects);
        $this->assign('xtitles', $xtitles);
        $this->assign('data', $data);
        $this->display(APP_PATH . 'Tpl/AbstractGrid/echarts.html');
    }

    protected function getExcelinfo($fileName, $start_Row = 0)
    {

        // $fileName = "upload/111.xlsx";
        if (empty($fileName)) {
            return "file not exists";
        }
        Vendor('Excel.PHPExcel');
        $objReader = new PHPExcel_Reader_Excel2007(); //use excel2007

        if (!$objReader->canRead($fileName)) {
            $objReader = new PHPExcel_Reader_Excel5();
        }

        //获取excel
        if ($objReader->canRead($fileName)) {
            $objPHPExcel = $objReader->load($fileName); //指定的文件
            $sheet = $objPHPExcel->getSheet(0); //第一个工作簿
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        } else {
            die(json_encode(array('status' => "-1")));
        }
        $array = array();
        for ($currentRow = $start_Row; $currentRow <= $highestRow; $currentRow++) {
            /**从第A列开始输出*/
            for ($currentColumn = 'A'; $currentColumn <= $highestColumn; $currentColumn++) {
                $val = $sheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue();
                /**ord()将字符转为十进制数*/
                $array[$currentRow][ord($currentColumn) - 65] = $val;
            }
        }
        return $array;
    }


    /**
     * 简单更新状态值 batchAction 的通用响应
     * @todo 补充字段 arrPostVars处理                       ````
     * @param type $status_field_name
     */
    protected function simple_status_update($status_field_name)
    {
        $ids = explode(',', $_POST['ids']);

        // 兼容两种参数：*_status_name 对应 ecvalue，*_status 对应 ecid
        if ($status_name = $_POST[$status_field_name . '_name']) {
            $enumConstModel = D('EnumConst');
            $status_value = $enumConstModel->getId($status_name);
        } else {
            $status_value = $_POST[$status_field_name];
        }

        if (empty($ids) || empty($status_value)) {
            $this->error('参数错误');
        }

        $modified = 0;
        foreach ($ids as $id) {
            $arrData = $_POST;
            $arrData[D($this->getActionName())->getPk()] = $id;
            $arrData[$status_field_name] = $status_value;
            foreach ($arrData as $k => &$v) {
                if ($v == 'AUTO_TIMESTAMP') {
                    $v = date('Y-m-d H:i:s');
                }
            }

            $modified += D($this->getActionName())->save($arrData);
        }

        if ($modified) {
            $this->success("提交成功，已更新" . $modified . "条");
        } else {
            $this->error('更新失败');
        }
    }

    /**
     * 检查是否有重复
     */
    public function checkDuplicate()
    {
        foreach ($_POST as $k => $v) {
            $where = " $k='$v'";

            if (D($this->getActionName())->where($where)->find()) {
//        $this->error("！提示：发现重复数据，<a target='_blank' href='index?_hideMenu=1&$k=$v'>查看</a>");
                $this->error("！提示：发现重复数据");
            } else {
                if ($k == 'idcard' && !idcard_checksum18($v)) {
                    if (strlen($v) >= 18) {
                        $this->error("身份证号输入错误");
                    } else {
                        $this->success("请输入正确的身份证号");
                    }
                }

                $this->success('√ 数据库中无此记录');
            }
        }
    }

    /**
     * 获取列的宽度,用于手工设置列表页每个td的宽度
     */
    protected function getColumnsWidth()
    {
        if (!empty($this->fieldsColumnsWidth)) {
            return $this->fieldsColumnsWidth;
        }
        return array();
    }

    public function interfaceSave($object = '')
    {
        if ($_POST) {
            $model = D(str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()));

            if ($_POST[$model->getPk()]) {
                $methodName = '_interfaceUpdate';
            } else {
                $methodName = '_interfaceAdd';
            }

            $methodName .= $object;

            $this->ajaxReturn($model->{$methodName}());
        }
    }

    protected function interfaceQuery($map = '', $url = '', $rowProcessFunc = '_interfaceRowLinks', $interfaceFunc = '')
    {
        //获取数据列表
        $https = http_build_query($_POST);

        // 自定义interfaceGetList
        if ($interfaceFunc)
        {
            $methodList = get_class_methods($this);
            if (!in_array($interfaceFunc, $methodList))
            {
                $className = get_called_class();
                $this->error("Action ". $className ."is not content the function ". $interfaceFunc);
            }

            $arr = $this->{$interfaceFunc}($map, $url);
        }
        else
        {
            $arr = $this->interfaceGetList($map, $url);
        }

        //获取rowlinks数据
        $items = $this->{$rowProcessFunc}($arr);

        $items["maps"] = $https;
        //ajax 返回请求
        $this->ajaxReturn(
            $items
        );
    }

    /**
     * 设置获取接口的基础数据
     * @return unknown
     */
    protected function _interfaceGetListData($map = "", $url = '')
    {
        $name = str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName());
        $model = D($name);

        $p = $_POST["p"] ? $_POST["p"] : ($map["p"] ? $map["p"] : 1);
        $limit = $_POST["limit"] ? $_POST["limit"] : ($map["limit"] ? $map["limit"] : 10);
        $offset = ($p - 1) * $limit;


        if (!$map) {
            unset($_POST["p"]);
            unset($_POST["limit"]);
            //再把为空的数据过滤掉
            foreach ($_POST as $key => $value) {
                if ($value || $value === "0" || $value === 0) {
                    $value = str_replace("%", "\%", $value);
                    $map[$key] = trim($value);
                }
            }

        }

        if ($this->_setSort) {
            if (!$map["orderFields"] && $_POST["sort"]) {
                $order = explode(".", $_POST["sort"]);
                $map["orderFields"] = $order[0];
                $map["orderType"] = $order[1];
                unset($map["sort"]);
            }
        }
        $rs = $model->_interfaceGetList($map, $offset, $limit, $url);


        return $rs;
    }

    /**
     * 设置单条记录的rowlinks（也就是页面上的操作）
     * @param   &$item , flags
     * @return  $item
     * @author  chenxing9
     * @date    2014-11-18
     */
    const ROWLINK_SHOW = 1;
    const ROWLINK_HIDE = 0;
    const ROWLINK_DISABLED = 2;

    protected function _singleRowLinks(&$item, $linkText, $urlTemplate, $flag = AbstractGridAction::ROWLINK_SHOW)
    {
        Log::record('[Debug] ' . __class__ . '::' . __method__ . ': ' . $flag);
        $css_class = '';
        if (AbstractGridAction::ROWLINK_HIDE == $flag)
            $css_class = 'hide';
        else if (AbstractGridAction::ROWLINK_DISABLED == $flag)
            $css_class = 'disabled';
        $pk = D(str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()))->getPk();
        $item['_grid_actions'] .= '<a class="btn btn-info ' . $css_class . '" href="' . str_replace('__pkid__', $item[$pk], $urlTemplate) . '">' . $linkText . '</a> ';

        return $item;
    }

    /**
     * 设置获取接口的rowlinks数据
     * @param unknown $item
     * @return string
     */
    protected function _interfaceRowLinks($item)
    {
        if ($item) {
            if (!empty($this->rowLinks)) {
                $pk = D(str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()))->getPk();

                foreach ($item["items"] as $key => $value) {
                    foreach ($this->rowLinks as $rowLink) {
                        if ($rowLink['linkText']) {
                         if ($rowLink['urlTemplate'] == "input") {
                                $item["items"][$key]['_grid_actions'] .= str_replace('__pkid__', $value[$pk], $rowLink['linkText']);
                            }  elseif($rowLink['fn']=="parameter") {
                                $arr=explode(",",$rowLink['targetBlank']);
                                foreach ($arr as $k=>$v)
                                {
                                 $url=   str_replace('__'.$v.'__', $value[$v], $rowLink['urlTemplate']);
                                }
                                 $url= str_replace('__pkid__', $value[$pk], $url);
                                $item["items"][$key]['_grid_actions'] .= '<a class="btn btn-info" href="' . $url . '">' . $rowLink['linkText'] . '</a> ';
                            }elseif($rowLink['fn']=="auditStatus"){
                               if ($value["auditStatus"]=="INIT")
                               {
                                  $item["items"][$key]['_grid_actions'] .= '<a class="btn btn-info" href="' . str_replace('__pkid__', $value[$pk], $rowLink['urlTemplate']) . '">' . $rowLink['linkText'] . '</a> ';
                               }

                            }

                            else {
                                $item["items"][$key]['_grid_actions'] .= '<a class="btn btn-info" href="' . str_replace('__pkid__', $value[$pk], $rowLink['urlTemplate']) . '">' . $rowLink['linkText'] . '</a> ';
                            }
//   $item["items"][$key]['_grid_actions'] .= '<a class="btn btn-info" href="' . str_replace('__pkid__', $value[$pk], $rowLink['urlTemplate']) . '">' . $rowLink['linkText'] . '</a> ';
                        }
                    }
                }
            }
        }
        return $item;
    }

    protected function _interfaceRowLinks_fun($item)
    {
        if (!empty($this->rowLinks)) {
            $pk = D(str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()))->getPk();

            foreach ($item["items"] as $key => $value) {
                $action_class_name = str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()) . "Action";
                //var_dump($action_class_name);
                foreach ($this->rowLinks as $rowLink) {
                    $fn = $rowLink["fn"];
                    $css_class = '';
                    if ($fn) {
                        $flag = call_user_method($fn, new $action_class_name, $value);
                        // 映射
                        if (AbstractGridAction::ROWLINK_HIDE == $flag)
                            $css_class = 'hide';
                        else if (AbstractGridAction::ROWLINK_DISABLED == $flag)
                            $css_class = 'disabled';
                    }

                    if (empty($rowLink['id'])) {
                        $item["items"][$key]['_grid_actions'] .= '<a class="btn btn-info ' . $css_class . '" href="' . str_replace('__pkid__', $value[$pk], $rowLink['urlTemplate']) . '">' . $rowLink['linkText'] . '</a> ';
                    } else {
                        $item["items"][$key]['_grid_actions'] .= '<a id="' . $rowLink['id'] . '" class="btn btn-info ' . $css_class . '" href="javascript:void(0);" param="' . str_replace('__pkid__', $value[$pk], $rowLink['urlTemplate']) . '">' . $rowLink['linkText'] . '</a> ';
                    }
                }
            }
        }

        return $item;
    }

    protected function _interfaceSearch()
    {
        return false;
    }

    /**
     * 格式化接口的基础数据，按照query 方法的数据结构进行格式化
     * @return multitype:NULL unknown
     */
    public function interfaceGetList($map = array(), $url = '')
    {
        //$get_map=array("data"=>"data.datas","count"=>"data.count")
        $rs = $this->_interfaceGetListData($map, $url);

        if ($this->_getDataFormat) {
            $data_count = explode(".", $this->_getDataFormat["data"]);
            if (count($data_count) == 1) {
                foreach ($rs[$this->_getDataFormat["data"]] as $key => $value) {
                    $item[$key] = $value;
                }
            } else {
                foreach ($rs[$data_count[0]][$data_count[1]] as $key => $value) {
                    $item[$key] = $value;
                }
            }
            $count_count = explode(".", $this->_getDataFormat["count"]);
            if (count($count_count) == 1) {
                $totalCount = $rs[$this->_getDataFormat["count"]];
            } else {
                $totalCount = $rs[$count_count[0]][$count_count[1]];
            }

            return array(
                "totalCount" => $totalCount,
                "items" => $item ? $item : array()
            );
        } else {
            if(empty($totalCount) && !empty($rs['totalCount'])) {
            	$totalCount = $rs['totalCount'];
            	$item = $rs['data'];
            }
            if(isset($rs["data"]["datas"])) {
            	$items = $rs["data"]["datas"];
            	$totalCount = $rs["data"]["count"] ? $rs["data"]["count"] : $rs["data"]["totalCount"];
            } else if(isset($rs["data"]["data"])) {
            	$items = $rs["data"]["data"];
            	$totalCount = $rs["data"]["count"] ? $rs["data"]["count"] : $rs["data"]["totalCount"];
            } else {
            	$items = $rs["data"];
            	$totalCount = $rs['totalCount'];
            }
            foreach ($items as $key => $value) {
                $item[$key] = $value;
            }

            return array(
                "totalCount" => $totalCount,
                "items" => is_array($item) ? $item : array()
            );
        }
    }

    public function interfaceDelete($returnType = "JSON")
    {
        $name = str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName());
        $model = D($name);
        $rs = $model->_interfaceDelete();
        if ($returnType == "JSON") {
            $this->ajaxReturn($rs);
        } else {
            return $rs;
        }

    }

    public function interfaceExport($arr = array(), $url = '', $rowProcessFunc = '',$rowLinkProcessFunc='')
    {
        $_POST = $_GET;
        $list = $this->interfaceGetList($arr, $url);
        if($rowProcessFunc) {
            $list = $this->{$rowProcessFunc}($list);
        }
        if($rowLinkProcessFunc) {

            $showFields = $this->{$rowLinkProcessFunc}($this->getShowFields());
        }else{
            $showFields = $this->getShowFields();
        }

        return $this->outputExcel($list['items'],$showFields);
    }


    public function interfaceDetail($getDataKey = "data", $method = 'GET')
    {
        $params = array();
        $url = '';
        $rs = D(str_replace(C('DEFAULT_GROUP') . "/", "", $this->getActionName()))->_interfaceGetDetail($params, $url, $method);

        if ($rs["status"] == 200 || $rs["status"] == 0) {
            $this->info = $rs[$getDataKey];
        }
        if ($_GET["debug"] == "true") {
            print_r($this->info);
        }
    }

    /**
     * 基础数据操作日志
     */
    public function logPage()
    {
        if ($_POST['submit_type'] == "excel") {
            $this->logExcel();
        } else {
            $audits["serviceCode"] = $this->serviceCode;
            $audits["dataKey"] = $_GET["id"];
            $limit = isset($_POST['pagesize']) ? $_POST['pagesize'] : 10;;
            $offset = isset($_POST['page']) ? $_POST['page'] : 0;
            $start = $offset * $limit;
            $audits_data = D("Database/Audits")->_interfaceGetList($audits, $start, $limit);
            if ($start > $audits_data['totalCount']) {
                $start = 0;
                $offset = 0;
                $audits_data = D("Database/Audits")->_interfaceGetList($audits, $start, $limit);
            }

            $temp = $audits['totalCount'] % $limit;

            if ($temp > 0) {
                $page_count = floor($audits_data['totalCount'] / $limit) + 1;
            } else {
                $page_count = $audits_data['totalCount'] / $limit;
            }

            if ($offset == 0) {

                $page_up = "";
            } else {
                $page_up = "1";
            }
            if ($page_count > $offset + 1) {
                $page_next = $offset + 1;
            } else {
                $page_next = "";
            }
            $this->assign("page_up", $page_up);
            $this->assign("page_next", $page_next);
            $this->assign("page_size", $limit);
            $this->assign("this_page", $offset);
            $this->assign("dates", $audits_data['datas']);
            $this->assign("totalCount", $audits_data['totalCount']);
            $this->assign("titlePageLog", $this->titlePageLog);
            $this->assign("showStatus", $this->showStatus);
        }
    }

    /**
     * 操作日志导出excel
     */
    public function logExcel()
    {
        $audits["serviceCode"] = $this->serviceCode;
        $audits["dataKey"] = $_GET["id"];
        $limit = isset($_POST['pagesize']) ? $_POST['pagesize'] : 10;;
        $offset = isset($_POST['page']) ? $_POST['page'] : 0;
        $start = $offset * $limit;
        $audits = D("Database/Audits")->_interfaceGetList($audits, $start, $limit);
        if ($this->titlePageLog) {
            foreach ($audits['datas'] as $key_temp => $value_temp) {
                foreach ($this->titlePageLog as $key => $value) {
                    $list[$key_temp][$key] = $value_temp[$key];
                    $list[$key_temp]['status'] = $this->showStatus[$value_temp['status']];
                }
            }
        } else {
            foreach ($audits['datas'] as $key_temp => $value_temp) {
                foreach ($this->titlePageLog as $key => $value) {
                    $list[$key_temp][$key] = $value_temp[$key];
                }
            }
        }

        return $this->outputExcel($list, $this->titlePageLog,"操作日志.xlsx");
    }

    public function preview()
    {
    	$productTypes = array('coupon');
    	 if(empty($_GET["productNo"]) || empty($_GET["productType"]) || !in_array($_GET["productType"], $productTypes)) {
	        // FIXME show error and more details
	        die('error');
	    }

	    $productNo = $_GET['productNo'];

	    $result = json_decode(http(INTERFACE_FFAN_GET_PREVIEW_TOKEN, array('id' => $productNo)), true);
	    $token = $result['data'];

	    // preview 取决于 productType
	    if($_GET["productType"] == 'coupon') {
	    	$previewUrl = sprintf(FFAN_COUPON_DETAIL, $_GET['productNo']);
	    }

	    $previewUrl .= strpos($previewUrl, '?') ? '&' : '?';
	    $previewUrl .= "token={$token}";

	    redirect($previewUrl);
    }

	/**
	+----------------------------------------------------------
	 * 字符串截取，支持中文和其他编码
	+----------------------------------------------------------
	 * @static
	 * @access public
	+----------------------------------------------------------
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 */
	public function substrs($str, $start=0, $length, $charset='utf-8', $suffix=true) {
		// Strip tags
		$str = trim(strip_tags($str));
		// Is the string long enough to ellipsize?
                 $strlen= (strlen($str) + mb_strlen($str,'UTF8')) / 2;
		if ($strlen<= $length){
			return $str;
		}

		if(function_exists("mb_substr")){
			$slice = mb_substr($str, $start, $length, $charset);
		}elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.'...' : $slice;
	}


    public function getActionName()
    {
        return 'MemberBase';
    }




}