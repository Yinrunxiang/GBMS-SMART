<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Record extends Validate{

	protected $rule = array(
		'device'  		=> 'require',
	);
	protected $message = array(
		'device.require'    	=> 'Device cannot be empty',
	);
}