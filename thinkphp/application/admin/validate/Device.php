<?php
/**
 * Created by PhpStorm.
 * User: KAZI
 * Date: 2018/3/2
 * Time: 17:06
 */

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Device extends Validate{

    protected $rule = array(
        'device'      	=> 'require',
        'address'      	=> 'address',
        'floor'      	=> 'floor',
        'room'      	=> 'room',
        'device'      	=> 'require',

    );
    protected $message = array(
        'device.require'    	=> 'Name must be filled in'
    );
}