<?php
/******************************************************************
** 文件名称: AuthUserController.class.php
** 功能描述: 权限用户管理
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-14
******************************************************************/
namespace System\Controller;
use Think\Controller;

class AuthUserController extends SystemController 
{
    //列表字段，必须设置
    public $showFields = array(
                                'account'       => '登录账号',
                                'status'        => '状态状态',
                                'belong'        => '所属用户组',
                                'create_time'   => '创建时间',
                                'login_count'   => "登录次数",
                                'action'        => '操作'
                            );
    public $columnsWidth = array(
                                'account'       => 150,
                                'status'        => 80,
                                'belong'        => 200,
                                'create_time'   => 150,
                                'login_count'   => 80,
                                'action'        => 200
                            );
    //设置标题，非必设置,不设置需要查询权限节点名称
    public $pageTitle = '账号';
    //设置模型名称，非必设置，不设置默认控制器名称，如这里默认为‘ListDemo’
    public $modelName = 'Admin'; 

    public static $userType  = array(
                                1 => '正常',
                                2 => '暂停'
                                );

    /**
     * [_initialize 初始化]
     * @Author haodaquan
     * @Date   2016-04-14
     * @return [type]     [description]
     */
    public function _initialize()
    {
        parent::_initialize();
    }
    
    /**
     * [index 使用基类首页方法]
     * @Author haodaquan
     * @Date   2016-04-14
     * @return [type]     [description]
     */
    public function index()
    {
        $this->assign('userType',self::$userType);
        parent::index();
    }

    /**
     * [query 查询配置]
     * @Author haodaquan
     * @Date   2016-04-14
     * @return [type]     [description]
     */
    public function query()
    {
        parent::query();
    }

    /**
     * [listDataFormat 列表数据格式化,]
     * @Author haodaquan
     * @Date   2016-04-14
     * @param  array      $data [description]
     * @return [type]           [description]
     */
    public function listDataFormat($listData)
    {
        if(empty($listData)) return array('');

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
            if(isset($value['create_time'])){
                $listFormat[$key]['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
            if(isset($value['login_time'])){
                $listFormat[$key]['login_time'] = date('Y-m-d H:i:s',$value['login_time']);
            }
            $listFormat[$key]['status'] = isset(self::$userType[$listFormat[$key]['status']]) ? self::$userType[$listFormat[$key]['status']]: '未知';
            $listFormat[$key]['action'] = getButton($value['id'],$buttons);
        }
        return $listFormat;
    }

    /**
     * [input 新增或者删除]
     * @Author haodaquan
     * @Date   2016-04-15
     * @return [type]     [description]
     */
    public function input()
    {
        $id = I('get.id',0,'intval');
        if($id===0)
        {
            //新增
            $this->assign('pageTitle','新增'.$this->pageTitle);
        }else{
            //编辑

        }

        $this->display();
    }


    public function inputAjax()
    {
        $id = I('post.id',0,'intval');
        if($id===0)
        {
            //新增
        }else{
            //修改
        }
    }
}