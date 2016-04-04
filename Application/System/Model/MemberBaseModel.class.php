<?php
/******************************************************************
** 文件名称: MemberBaseModel.class.php
** 功能描述: 会员数据表对象
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Model;
use Think\Model;

class MemberBaseModel extends Model 
{
    
    public function getAll()
    {
        return $this->select();
    }



}