<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: JensenYin
// +----------------------------------------------------------------------

return [
    // 定义资源路由
    '__rest__' => [
        'admin/device' => 'admin/device',
        'admin/address' => 'admin/address',
        'admin/floor' => 'admin/floor',
        'admin/room' => 'admin/room',
        'admin/ac_breed' => 'admin/ac_breed',
        'admin/led_breed' => 'admin/led_breed',
        'admin/light_breed' => 'admin/light_breed',
        'admin/mood' => 'admin/mood',
        'admin/marco' => 'admin/marco',
        'admin/schedule' => 'admin/schedule',
    ],
    // 【基础】登录
    'admin/base/login' => ['admin/base/login', ['method' => 'POST']],
	// 【基础】记住登录
    'admin/base/relogin'	=> ['admin/base/relogin', ['method' => 'POST']],
	// 【基础】修改密码
	'admin/base/setInfo' => ['admin/base/setInfo', ['method' => 'POST']],
	// 【基础】退出登录
    'admin/base/logout' => ['admin/base/logout', ['method' => 'POST']],
    // 【基础】退出登录
    'admin/base/register' => ['admin/base/register', ['method' => 'POST']],
    // 【基础】更新数据库
	'admin/base/updateDatabase' => ['admin/base/updateDatabase', ['method' => 'POST']],
	// 【设备】修改设备位置
    'admin/device/updateLocationById' => ['admin/device/updateLocationById', ['method' => 'POST']],
	// 【设备】修改LED颜色
    'admin/device/setColor' => ['admin/device/setColor', ['method' => 'POST']],
	// 【设备】获取IR模块额外参数
    'admin/device/getIrOperation' => ['admin/device/getIrOperation', ['method' => 'POST']],
	// 【设备】新增IR模块额外参数
    'admin/device/insertIrOperation' => ['admin/device/insertIrOperation', ['method' => 'POST']],
	// 【设备】更新IR模块额外参数
    'admin/device/updateIrOperation' => ['admin/device/updateIrOperation', ['method' => 'POST']],
    // 【设备】设置运行时间
    'admin/device/setTime' => ['admin/device/setTime', ['method' => 'POST']],
    // 【Macro】根据命令ID删除数据
    'admin/macro/delCommandById' => ['admin/macro/delCommandById', ['method' => 'POST']],
    // 【Macro】执行Mood
    'admin/macro/run' => ['admin/macro/run', ['method' => 'POST']],
    // 【Schedule】根据命令ID删除数据
    'admin/macro/delCommandById' => ['admin/macro/delCommandById', ['method' => 'POST']],
	// MISS路由
    '__miss__' => 'admin/base/miss',
];