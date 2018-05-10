<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Ir extends Validate{

	protected $rule = array(
		'device'  		=> 'require',
	);
	protected $message = array(
		'device.require'    	=> 'device  must be filled in',
	);
}