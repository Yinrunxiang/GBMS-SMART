<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class MarcoCommand extends Validate{

	protected $rule = array(
		'marco'  		=> 'require|length:0,30',
	);
	protected $message = array(
		'marco.require'    	=> 'marco name must be filled in',
		'marco.length'    	=> 'marco name length is from 1 to 30 bits',
	);
}