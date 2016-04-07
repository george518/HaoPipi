<?php 

//公共函数库

/**
 * [queryTimeFomat 查询时间格式化]
 * @Author haodaquan
 * @Date   2016-04-07
 * @param  string     $startTime [开始时间：2016-12-13]
 * @param  string     $endTime   [结束时间：2018-12-12]
 * @return [type]                [查询数组]
 */
function queryTimeFormat($startTime='',$endTime='')
{
	$data = array();
	if(!$startTime && !$endTime) return $data;

	if($startTime)
	{
		$st = strtotime($startTime.' 00:00:00');
		$data[0] = array('EGT',$st);
	}

	if($endTime)
	{
		$et = strtotime($endTime.' 23:59:59');
		if(isset($data[0])){
			$data[1] = array('ELT',$et);
		}else{
			$data[0] = array('ELT',$et);
		}
	}

	return $data;
}

/**
 * [getButton 生成操作按钮]
 * @Author haodaquan
 * @Date   2016-04-07
 * @param  int        $id     [操作id]
 * @param  string     $btnArr [array('edit'=>'编辑','delete'=>'删除')]
 * @return [type]             [button字符串]
 */
function getButton($id=0,$btnArr='')
{
	
	if (empty($btnArr) || $id==0) return '';
	$btn = '';

	$relation = C('BUTTON_TYPE');
	foreach ($btnArr as $key => $value) 
	{
		$btn .= '<button type="button" onClick="return '.$key.'Action('.$id.')" class="btn btn-xs btn-'.$relation[$key].'">'.$value.'</button>&nbsp;';
	}

	return $btn;
}