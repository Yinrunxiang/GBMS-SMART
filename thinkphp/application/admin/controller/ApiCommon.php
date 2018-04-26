<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class ApiCommon extends Common
{
    public function _initialize()
    {
        // Db::connect([
        //     // 数据库类型
        //     'type'        => 'mysql',
        //     // 数据库连接DSN配置
        //     'dsn'         => '',
        //     // 服务器地址
        //     'hostname'    => '127.0.0.1',
        //     // 数据库名
        //     'database'    => 'user',
        //     // 数据库用户名
        //     'username'    => 'root',
        //     // 数据库密码
        //     'password'    => 'root',
        //     // 数据库连接端口
        //     'hostport'    => '3306',
        //     // 数据库连接参数
        //     'params'      => [],
        //     // 数据库编码默认采用utf8
        //     'charset'     => 'utf8',
        //     // 数据库表前缀
        //     'prefix'      => '',
        // ]);
        // parent::_initialize();
        // /*获取头部信息*/ 
        // $header = Request::instance()->header();
        
        // $authKey = $header['authkey'];
        // $sessionId = $header['sessionid'];
        // $cache = cache('Auth_'.$authKey);
        
        // // 校验sessionid和authKey
        // if (empty($sessionId)||empty($authKey)||empty($cache)) {
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        // }

        // // 检查账号有效性
        // $userInfo = $cache['userInfo'];
        // $map['id'] = $userInfo['id'];
        // $map['status'] = 1;
        // if (!Db::name('user')->where($map)->value('id')) {
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));   
        // }
        // // 更新缓存
        // cache('Auth_'.$authKey, $cache, 2592000);
        // // $authAdapter = new AuthAdapter($authKey);
        // // $request = Request::instance();
        // // $ruleName = $request->module().'-'.$request->controller() .'-'.$request->action(); 
        // // if (!$authAdapter->checkLogin($ruleName, $cache['userInfo']['id'])) {
        // //     header('Content-Type:application/json; charset=utf-8');
        // //     exit(json_encode(['code'=>102,'error'=>'没有权限']));
        // // }
        // $GLOBALS['userInfo'] = $userInfo;
    }
}
