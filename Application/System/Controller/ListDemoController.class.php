<?php
/******************************************************************
** 文件名称: ListDemoController.class.php
** 功能描述: 列表页功能演示
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Controller;
use Think\Controller;

class ListDemoController extends SystemController 
{
    //列表字段
    public $showFields = array(
                                'nick_name'   => '客户昵称',
                                'mobile'      => '手机号码',
                                'rank_id'     => '会员级别',
                                'sex'         => "性别",
                                'addtime'     => '注册时间',
                                'job'         => '职业名称',
                                'birthday'    => '出生日期',
                                'store_num'   => '到店次数'
                            );

    public function _initialize()
    {

        parent::_initialize();
    }
    
    public function index()
    {
        $this->assign('pageTitle','会员基础');
       // parent::index();
       $this->display();
    }

    /**
     * [showSearch 是否显示搜索,false-不显示，true-显示 并需要在模块目录下建立search文件，默认为true]
     * @Author haodaquan
     * @Date   2016-04-04
     * @return [type]     [viod]
     */
    public function showSearch()
    {
        $this->assign('showSearch', true);
    }




    




}