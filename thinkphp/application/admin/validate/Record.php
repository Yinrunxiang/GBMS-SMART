<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Record extends Validate{

	protected $rule = array(
		'patient'      	=> 'require',
        'doctor'      	=> 'require',
	);
	protected $message = array(
        'patient.require'    	=> 'Patient must be filled in',
        'doctor.require'    	=> 'Doctor must be filled in',
	);
}