<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class ScheduleCommand extends Validate{

	protected $rule = array(
		'schedule'  		=> 'require|length:0,30',
	);
	protected $message = array(
		'schedule.require'    	=> 'Schedule cannot be empty',
		'schedule.length'    	=> 'Schedule length between 0 and 20',
	);
}