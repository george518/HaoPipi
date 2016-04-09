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
    //列表字段，必须设置
    public $showFields = array(
                                'nick_name'   => '客户昵称',
                                'mobile'      => '手机号码',
                                'rank_id'     => '会员级别',
                                'sex'         => "性别",
                                'addtime'     => '注册时间',
                                'job'         => '职业名称',
                                'birthday'    => '出生日期',
                                'store_num'   => '到店次数',
                                'action'      => '操作'
                            );
    public $columnsWidth = array(
                                'nick_name'   => 120,
                                'action'      => 150,
                                'addtime'     => 150,
                                'sex'         => 50,
                            );
    //设置标题，非必设置,不设置需要查询权限节点名称
    public $pageTitle = 'Mmgrid测试';
    //设置模型名称，非必设置，不设置默认控制器名称，如这里默认为‘ListDemo’
    public $modelName = 'MemberBase'; 

    /**
     * [_initialize 初始化]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    public function _initialize()
    {
        parent::_initialize();
    }
    
    /**
     * [index 使用基类首页方法]
     * @Author haodaquan
     * @Date   2016-04-05
     * @return [type]     [description]
     */
    public function index()
    {
        parent::index();
    }

    /**
     * [query 查询配置]
     * @Author haodaquan
     * @Date   2016-04-07
     * @return [type]     [description]
     */
    public function query()
    {
        $startTime = I('post.startTime',0);
        $endTime   = I('post.endTime',0);

        if($startTime || $endTime){
            $_POST['addtime'] = queryTimeFormat($startTime,$endTime);
        }
        parent::query();
    }

    /**
     * [listDataFormat 列表数据格式化,]
     * @Author haodaquan
     * @Date   2016-04-07
     * @param  array      $data [description]
     * @return [type]           [description]
     */
    public function listDataFormat($listData)
    {
        if(empty($listData)) return array('111');

        $listFormat = array();
        $buttons = array(
                'detail' => '查看',
                'edit'   => '编辑',
                'delete' => '删除'
            );
       
        foreach ($listData as $key => $value) 
        {
            $listFormat[$key] = $value;
            //数据的格式化及添加操作按钮
            if(isset($value['addtime'])){
                $listFormat[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
            }

            if(isset($value['edittime'])){
                $listFormat[$key]['edittime'] = date('Y-m-d H:i:s',$value['edittime']);
            }
            $listFormat[$key]['action'] = getButton($value['member_id'],$buttons);
        }
        return $listFormat;
    }
}