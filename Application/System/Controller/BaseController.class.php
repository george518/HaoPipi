<?php
/******************************************************************
** 文件名称: BaseController.class.php
** 功能描述: 基础控制器类,权限、基础检查，公共变量等等
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Controller;
use Think\Controller;

class BaseController extends Controller 
{
    /**
     * [_initialize 初始化方法]
     * @Author haodaquan
     * @Date   2016-04-03
     * @return [type]     [公共基础检查等]
     */
    public function _initialize()
    {
        //权限检查
        if (!$this->AuthCheck()) $this->error('暂无权限');

        //数据安全检查
        if (!$this->DataCheck()) $this->error('数据非法！');

    }


    private function AuthCheck()
    {
        return true;
    }

    private function DataCheck()
    {
        return true;
    }

    




}