<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Patient extends Validate{

	protected $rule = array(
		'name'  		=> 'require|length:0,12|unique:patient',
	);
	protected $message = array(
		'name.require'    	=> '用户名必须填写',
		'name.length'    	=> '用户名长度在6到12位',
		'name.unique'    	=> '用户名已存在',
	);
}