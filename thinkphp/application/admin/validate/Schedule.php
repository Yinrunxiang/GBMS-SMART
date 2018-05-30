<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Schedule extends Validate{

	protected $rule = array(
		'schedule'  		=> 'require|length:0,20',
	);
	protected $message = array(
		'schedule.require'    	=> 'Schedule cannot be empty',
		'schedule.length'    	=> 'Schedule length between 0 and 20',
	);
}