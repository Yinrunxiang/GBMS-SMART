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
		'username.require'    	=> 'Username cannot be empty',
		'username.length'    	=> 'Username length between 0 and 20',
		'username.unique'    	=> 'Username already exists',
		'password.require'    	=> 'Password cannot be empty',
	);
}