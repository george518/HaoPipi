<?php
/******************************************************************
** 文件名称: AdminModel.class.php
** 功能描述: 账号表对象
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-14
******************************************************************/

namespace System\Model;

class AdminModel extends SystemModel 
{
    //初始化数据
    public function _initialize()
    {
    	parent::_initialize();
    }

    /**
     * [getAdminInfo 获取单个用户信息]
     * @Author haodaquan
     * @Date   2016-04-27
     * @param  [type]     $where [条件数组]
     * @return [type]            [description]
     */
    public function getAdminInfo($where)
    {
    	return $this->where($where)->find();
    }





}