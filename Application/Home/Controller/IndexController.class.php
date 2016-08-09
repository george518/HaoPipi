<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
       	$id = M('userinfo')->data(array('username'=>'123'))->add();

       	dump($id);
    }
}