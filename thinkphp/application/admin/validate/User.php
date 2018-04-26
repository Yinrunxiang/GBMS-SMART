<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class User extends Validate{

	protected $rule = array(
		'username'  		=> 'require|length:1,12|unique:user',
		'password'      	=> 'require',
	);
	protected $message = array(
		'username.require'    	=> 'Username must be filled in',
		'username.length'    	=> 'The longest username is 12 bits',
		'username.unique'    	=> 'Username already exists',
		'password.require'    	=> 'Password must be filled in',
	);
}