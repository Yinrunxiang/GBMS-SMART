<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Schedule extends Validate{

	protected $rule = array(
		'schedule'  		=> 'require|length:0,20|unique:schedule',
	);
	protected $message = array(
		'schedule.require'    	=> 'schedule name must be filled in',
		'schedule.length'    	=> 'schedule name length is from 1 to 20 bits',
		'schedule.unique'    	=> 'schedule name already exists',
	);
}