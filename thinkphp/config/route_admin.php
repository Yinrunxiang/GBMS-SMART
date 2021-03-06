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
        'admin/macro' => 'admin/macro',
        'admin/schedule' => 'admin/schedule',
        'admin/record' => 'admin/record',
        'admin/ir' => 'admin/ir',
        'admin/alexa' => 'admin/alexa',
    ],
    // 【基础】登录
    'admin/base/login' => ['admin/base/login', ['method' => 'POST']],
	// 【基础】记住登录
    'admin/base/relogin' => ['admin/base/relogin', ['method' => 'POST']],
	// 【基础】修改密码
    'admin/base/setInfo' => ['admin/base/setInfo', ['method' => 'POST']],
	// 【基础】退出登录
    'admin/base/logout' => ['admin/base/logout', ['method' => 'POST']],
    // 【基础】退出登录
    'admin/base/register' => ['admin/base/register', ['method' => 'POST']],
    // 【基础】更新数据库
    'admin/dataBase/updateDatabase' => ['admin/dataBase/updateDatabase', ['method' => 'POST']],
    // 【UDP】发送UDP
    'admin/udp/sendUdp' => ['admin/udp/sendUdp', ['method' => 'POST']],
    // // 【设备】修改设备位置
    // 'admin/device/save' => ['admin/device/save', ['method' => 'POST']],
    // // 【设备】修改设备位置
    // 'admin/device/update' => ['admin/device/update', ['method' => 'POST']],
    // 【设备】删除设备
    'admin/device/delete' => ['admin/device/delete', ['method' => 'POST']],
	// 【设备】修改设备位置
    'admin/device/updateLocation' => ['admin/device/updateLocation', ['method' => 'POST']],
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
    // 【Macro】执行Macro命令
    'admin/macro/run' => ['admin/macro/run', ['method' => 'POST']],
    // 【Macro】删除Macro
    'admin/macro/delete' => ['admin/macro/delete', ['method' => 'POST']],
    // 【Macro】删除MarcoCommand
    'admin/macro/deleteCommand' => ['admin/macro/deleteCommand', ['method' => 'POST']],
    // 【Schedule】删除Schedule
    'admin/schedule/delete' => ['admin/schedule/delete', ['method' => 'POST']],
    // 【Schedule】删除ScheduleCommand
    'admin/schedule/deleteCommand' => ['admin/schedule/deleteCommand', ['method' => 'POST']],
    // 【Mood】删除Mood
    'admin/mood/delete' => ['admin/mood/delete', ['method' => 'POST']],
    // 【Record】删除Record
    'admin/record/count' => ['admin/record/count', ['method' => 'POST']],
    // 【Upload】上传图片
    'admin/upload/index' => ['admin/upload/index', ['method' => 'POST']],
    // 【Upload】删除图片
    'admin/upload/delete' => ['admin/upload/delete', ['method' => 'POST']],
     // 【Upload】获取图片
    'admin/upload/getImage' => ['admin/upload/getImage', ['method' => 'POST']],
    // 【Address】删除Address
    'admin/address/delete' => ['admin/address/delete', ['method' => 'POST']],
    // 【Floor】删除Floor
    'admin/floor/delete' => ['admin/floor/delete', ['method' => 'POST']],
    // 【Room】删除Room
    'admin/room/delete' => ['admin/room/delete', ['method' => 'POST']],
    // 【Room】收藏room
    'admin/room/collect' => ['admin/room/collect', ['method' => 'POST']],
    // 【Room】取消收藏room
    'admin/room/uncollect' => ['admin/room/uncollect', ['method' => 'POST']],
    // 【AC】删除AC
    'admin/ac_breed/delete' => ['admin/ac_breed/delete', ['method' => 'POST']],
    // 【LIGHT】删除LIGHT
    'admin/light_breed/delete' => ['admin/light_breed/delete', ['method' => 'POST']],
    // 【LED】删除LED
    'admin/led_breed/delete' => ['admin/led_breed/delete', ['method' => 'POST']],
    // 【IR】删除IR
    'admin/ir/delete' => ['admin/ir/delete', ['method' => 'POST']],
	// MISS路由
    '__miss__' => 'admin/base/miss',
];