<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Device extends Validate{

	protected $rule = array(
		'device'  		=> 'require|length:0,100',
		'subnetid'      	=> 'require',
        'deviceid'      	=> 'require',
        'devicetype'      	=> 'require',
	);
	protected $message = array(
		'device.require'    	=> 'device name must be filled in',
		'device.length'    	=> 'device name length is from 6 to 12 bits',
		'subnetid.require'    	=> 'subnetid must be filled in',
		'deviceid.require'    	=> 'deviceid must be filled in',
		'devicetype.devicetype'    	=> 'devicetype must be filled in',
	);
}