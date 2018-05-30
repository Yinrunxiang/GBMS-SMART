<?php

namespace app\admin\validate;

use think\Validate;
/**
 * 设置模型
 */
class Floor extends Validate
{

	protected $rule = array(
		'address' => 'require|length:0,200',
		'floor' => 'require',
	);
	protected $message = array(
		'address.require' => 'Address cannot be empty',
		'address.length' => 'Address length between 0 and 100',
		'floor.require' => 'Floor cannot be empty',
	);
}