<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Doctor extends Validate{

	protected $rule = array(
		'doctor_name'  		=> 'require|length:6,12|unique:doctor',
		'doctor_password'      	=> 'require',
		'doctor_tel'      	=> 'require|length:11|unique:doctor',
	);
	protected $message = array(
		'doctor_name.require'    	=> 'User name must be filled in',
		'doctor_name.length'    	=> 'Username length is from 6 to 12 bits',
		'doctor_name.unique'    	=> 'User name already exists',
		'doctor_password.require'    	=> '密码必须填写',
        'doctor_tel.require'    	=> '手机号码必须填写',
        'doctor_tel.length'    	=> '手机号码长度在11位',
        'doctor_tel.unique'    	=> '手机号码已存在',
	);
}