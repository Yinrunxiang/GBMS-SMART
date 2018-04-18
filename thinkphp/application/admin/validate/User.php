<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class User extends Validate{

	protected $rule = array(
        'tel'  		=> 'require|length:11|unique:user',
		'name'  		=> 'require|length:1,12',
		'password'      	=> 'require',
	);
	protected $message = array(
        'tel.require'    	=> 'Mobile phone number must be filled in',
        'tel.length'    	=> 'The number of mobile phone is 11 bits',
        'tel.unique'    	=> 'Mobile phone number already exists',
		'name.require'    	=> 'User name must be filled in',
		'name.length'    	=> 'The longest user name is 12 bits',
		'password.require'    	=> 'Password must be filled in',
	);
}