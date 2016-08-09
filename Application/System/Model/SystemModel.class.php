<?php
/******************************************************************
** 文件名称: SystemModel.class.php
** 功能描述: 数据表对象，增删改查等基础方法
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-06
******************************************************************/

namespace System\Model;

class SystemModel extends BaseModel 
{


    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * [queryList 单表查询数据]
     * @Author haodaquan
     * @Date   2016-04-06
     * @param  [type]     $param [wehre,sort,limit]
     * @return [type]          [description]
     */
    public function queryList($param)
    {
        $map = $this->queryParam($param);
    	$totalCount = $this->getCount($map['where']);

    	$items = $this->where($map['where'])
                      ->order($map['sort'])
                      ->limit($map['limit'])
                      ->select();

    	return $this->returnData(200,'success',$items,$totalCount);
    }

    /**
     * [returnData 组成返回数据]
     * @Author haodaquan
     * @Date   2016-04-06
     * @param  [type]     $status     [状态码，200，300，500]
     * @param  [type]     $info       [状态信息]
     * @param  [type]     $data       [返回信息]
     * @param  [type]     $totalCount [查询时返回条数]
     * @return [type]                 [返回数组]
     */
    protected function returnData($status,$info,$items,$totalCount='')
    {
        $data = array();

        $data['status']  = $status;
        $data['message'] = $info;
        if($totalCount==='')
        {
            $data['data'] = $items;
        }else{
            $data['data'] = array(
                    'totalCount'=>$totalCount,
                    'items'=>$items
                );
        }
        return $data;
    }

    /**
     * [getCount 获取数据条数]
     * @Author haodaquan
     * @Date   2016-04-06
     * @param  array      $where [查询条件]
     * @return [type]            [description]
     */
    public function getCount($where=array())
    {
        $count = $this->where($where)->count();
        return $count ? $count : 0;
    }

    /**
     * [queryParam 处理查询数据]
     * @Author haodaquan
     * @Date   2016-04-07
     * @param  [type]     $param [查询参数]
     * @return [type]            [description]
     */
    protected function queryParam($param)
    {
        $map = array();
        //查询分页
        $limit = $param['limit'] ? $param['limit'] : 10;
        $page  = $param['page'] ? $param['page'] : 1;
        if(isset($param['sort']) && strpos($param['sort'], '.')){
            $map['sort'] = str_replace('.', ' ', $param['sort']);
        }else{
            $map['sort'] = $this->pk.' DESC '; 
        }

        unset($param['page']);
        unset($param['sort']);
        if(isset($param['key'])) unset($param['key']); 

        foreach ($param as $key => $value) {
            if($value) $map['where'][$key] = $value; //注意：查询0和空都将被过滤掉条件
        }

        $map['limit'] = ($page-1)*$limit.','.$limit;
        
        if (empty($map['where'])) {
            $map['where'] = '1=1';
        }
        return $map;
    }

    /**
     * [addData 新增数据]
     * @Author haodaquan
     * @Date   2016-04-27
     * @param  [type]     $data [description]
     * @return [type]            [id]
     */
    public function addData($data)
    {
        return  $this->data($data)->add();
    }

    /**
     * [saveData 保存数据成功]
     * @Author haodaquan
     * @Date   2016-04-27
     * @param  [type]     $data [含有id]
     * @return [type]           [description]
     */
    public function saveData($data)
    {
        return  $this->data($data)->save();
    }

    /**
     * [deleteData 删除数据]
     * @Author haodaquan
     * @Date   2016-04-29
     * @param  [type]     $where [description]
     * @return [type]         [description]
     */
    public function deleteData($where)
    {
        return  $this->where($where)->delete();
    }

}