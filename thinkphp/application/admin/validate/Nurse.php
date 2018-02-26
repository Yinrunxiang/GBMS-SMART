<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Nurse extends Validate{

	protected $rule = array(
		'nurse_name'  		=> 'require|length:6,12|unique:nurse',
		'nurse_password'      	=> 'require',
		'nurse_tel'      	=> 'require|length:11|unique:nurse',
	);
	protected $message = array(
		'nurse_name.require'    	=> '用户名必须填写',
		'nurse_name.length'    	=> '用户名长度在6到12位',
		'nurse_name.unique'    	=> '用户名已存在',
		'nurse_password.require'    	=> '密码必须填写',
        'nurse_tel.require'    	=> '手机号码必须填写',
        'nurse_tel.length'    	=> '手机号码长度在11位',
        'nurse_tel.unique'    	=> '手机号码已存在',
	);
}