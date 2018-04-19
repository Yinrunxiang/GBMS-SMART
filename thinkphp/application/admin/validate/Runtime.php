<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Doctor extends Validate{

	protected $rule = array(
		// 'device'  		=> 'require|length:0,100|unique:doctor',
		// 'subnetid'      	=> 'require',
        // 'deviceid'      	=> 'require|length:2',
        // 'devicetype'      	=> 'require|length:2',
	);
	protected $message = array(
		// 'device.require'    	=> 'device name must be filled in',
		// 'device.length'    	=> 'device name length is from 6 to 12 bits',
		// 'device.unique'    	=> 'device name already exists',
		// 'doctor_password.require'    	=> '密码必须填写',
        // 'doctor_tel.require'    	=> '手机号码必须填写',
        // 'doctor_tel.length'    	=> '手机号码长度在11位',
        // 'doctor_tel.unique'    	=> '手机号码已存在',
	);
}