<?php

namespace app\admin\validate;

use think\Validate;
/**
 * 设置模型
 */
class Room extends Validate
{

        protected $rule = array(
                'address' => 'require|length:0,200',
                'floor' => 'require',
                'room' => 'require',
                'room_name' => 'length:0,255',
        );
        protected $message = array(
                'address.require' => 'Address cannot be empty',
                'address.length' => 'Address length between 0 and 200',
                'floor.require' => 'Floor cannot be empty',
                'room.require' => 'Room cannot be empty',
                'room_name.length' => 'Room_name length between 0 and 255',
        );
}