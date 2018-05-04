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
		'schedule.require'    	=> 'schedule name must be filled in',
		'schedule.length'    	=> 'schedule name length is from 1 to 30 bits',
	);
}