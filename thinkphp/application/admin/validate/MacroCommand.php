<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class MacroCommand extends Validate{

	protected $rule = array(
		'macro'  		=> 'require|length:0,30',
	);
	protected $message = array(
		'macro.require'    	=> 'macro name must be filled in',
		'macro.length'    	=> 'macro name length is from 1 to 30 bits',
	);
}