<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Marco extends Validate{

	protected $rule = array(
		'mood'  		=> 'require|length:0,20',
	);
	protected $message = array(
		'mood.require'    	=> 'mood name must be filled in',
		'mood.length'    	=> 'mood name length is from 1 to 20 bits',
	);
}