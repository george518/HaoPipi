<?php
/******************************************************************
** 文件名称: TemplateController.class.php
** 功能描述: 模板控制器
** 创建人员: george Hao<41352963@qq.com>
** 创建日期: 2016-04-02
******************************************************************/
namespace System\Controller;
use Think\Controller;

class TemplateController extends Controller {
   
    public function font()
    {
		$this->display(); 
    }

    public function icon()
    {
    	$this->display();
    }

    public function form()
    {
    	$this->display();
    }

    public function formAdvanced()
    {
    	$this->display();
    }

    public function table()
    {
    	$this->display();
    }

    public function ui()
    {
    	$this->display();
    }

}