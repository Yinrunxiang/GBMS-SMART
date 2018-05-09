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
		'address.require' => 'address name must be filled in',
		'address.length' => 'address name length is from 0 to 200 bits',
		'floor.require' => 'floor must be filled in',
	);
}