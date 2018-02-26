<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Medicine extends Validate{

	protected $rule = array(
		'name'      	=> 'require',
        'type'      	=> 'require',
	);
	protected $message = array(
        'name.require'    	=> 'Name must be filled in',
        'type.require'    	=> 'Type must be filled in',
	);
}