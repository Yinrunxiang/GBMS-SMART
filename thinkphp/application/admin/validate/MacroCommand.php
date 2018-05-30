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
		'macro.require'    	=> 'Macro cannot be empty',
		'macro.length'    	=> 'Macro length between 0 and 30',
	);
}