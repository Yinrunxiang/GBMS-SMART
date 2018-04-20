<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class AcBreed extends Validate{

	protected $rule = array(
		'breed'  		=> 'require|length:0,10|unique:ac_breed',
	);
	protected $message = array(
		'breed.require'    	=> 'breed name must be filled in',
		'breed.length'    	=> 'breed name length is from 0 to 10 bits',
		'breed.unique'    	=> 'breed name already exists',
	);
}