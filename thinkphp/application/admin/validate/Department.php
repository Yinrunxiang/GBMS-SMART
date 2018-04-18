<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Department extends Validate{

	protected $rule = array(
		'name'      	=> 'require',
	);
	protected $message = array(
        'name.require'    	=> 'Name must be filled in'
	);
}