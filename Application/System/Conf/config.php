<?php
return array(
	//'配置项'=>'配置值'
	'BUTTON_TYPE' =>array(
			'detail' 		=> 'info', //查看
			'edit'   		=> 'primary',//编辑
			'delete' 		=> 'danger',//删除
			'changeStatus'	=> 'warning',//改变状态
			'disable'       => 'default',//禁止或者默认
			'default'       => 'success',//普通操作
		),

	'GENDER' => array(
			'1' => '男',
			'2' => '女'
		),

	'RESOK'  => array('status'=>200,'message'=>'操作成功'),
	'RESERR' => array('status'=>300,'message'=>'操作失败'),
);