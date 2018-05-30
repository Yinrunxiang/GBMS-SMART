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
		'mood.require'    	=> 'Mood cannot be empty',
		'mood.length'    	=> 'Mood length between 0 and 20',
	);
}