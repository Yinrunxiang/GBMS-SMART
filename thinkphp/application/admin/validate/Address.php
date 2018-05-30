<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Address extends Validate{

	protected $rule = array(
        'address'  		=> 'require|length:0,200',
        'country'  		=> 'require',
	);
	protected $message = array(
	'address.require'    	=> 'Address name cannot be empty',
	'address.length'    	=> 'Address length between 0 and 200',
        'country.require'    	=> 'Country cannot be empty',
	);
}