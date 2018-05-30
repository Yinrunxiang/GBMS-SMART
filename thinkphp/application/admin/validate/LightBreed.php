<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class LightBreed extends Validate{

	protected $rule = array(
		'breed'  		=> 'require|length:0,10',
	);
	protected $message = array(
		'breed.require'    	=> 'Breed cannot be empty',
		'breed.length'    	=> 'Breed length between 0 and 10',
	);
}