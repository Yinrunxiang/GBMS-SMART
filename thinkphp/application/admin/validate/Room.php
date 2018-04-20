<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Room extends Validate{

	protected $rule = array(
        'address'  		=> 'require|length:0,200',
        'floor'  		=> 'require',
        'room'  		=> 'require',
        'room_name'  		=> 'length:0,255',
	);
	protected $message = array(
		'address.require'    	=> 'address name must be filled in',
		'address.length'    	=> 'address name length is from 0 to 200 bits',
        'address.unique'    	=> 'address name already exists',
        'floor.require'    	=> 'floor must be filled in',
        'room.require'    	=> 'room must be filled in',
        'room_name.length'    	=> 'room name length is from 0 to 200 bits',
	);
}