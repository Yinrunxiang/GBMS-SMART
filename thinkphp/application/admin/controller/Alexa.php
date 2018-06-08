<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Db;
use com\verify\HonrayVerify;
use app\common\controller\Common;
use think\Request;
use app\common\udp\device\Light;
require __DIR__.'/../../common/udp/device/Light.php';

class Alexa extends Common
{
    public function save()
    {
        $Light = new Light;
        $Light->switch_change(true,'01','01','05',['53','03','00','00','00','98','C8'],'smartbuscloud.com',8888);
        // $Light = new Light;
        // $Light->switch_change(true,'01','01','05',[],'255.255.255.255',6000);
    }

}
 