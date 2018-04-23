<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Marco extends Validate{

	protected $rule = array(
		'marco'  		=> 'require|length:0,30|unique:marco',
	);
	protected $message = array(
		'marco.require'    	=> 'marco name must be filled in',
		'marco.length'    	=> 'marco name length is from 1 to 30 bits',
		'marco.unique'    	=> 'marco name already exists',
	);
}