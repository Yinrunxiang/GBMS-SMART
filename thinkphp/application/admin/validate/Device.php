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
		'address'      	=> 'require',
		'floor'      	=> 'require',
		'room'      	=> 'require',
	);
	protected $message = array(
		'device.require'    	=> 'Device cannot be empty',
		'device.length'    	=> 'Device length between 0 and 100',
		'subnetid.require'    	=> 'Subnetid cannot be empty',
		'deviceid.require'    	=> 'Deviceid cannot be empty',
		'devicetype.require'    	=> 'Device Type  cannot be empty',
		'address.require'    	=> 'Address cannot be empty',
		'floor.require'    	=> 'Floor  cannot be empty',
		'room.require'    	=> 'Room  cannot be empty',
	);
}