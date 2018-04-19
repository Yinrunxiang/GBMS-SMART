<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: GBMS-SMART
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Record extends Common
{

    protected $name = 'runtime';


   //获取运行时间
   public function getDataList($id)
   {
       $data['list'] = Db::table('runtime')->where('device_id', $id)->select();
       return $data;
   }
   //设置运行时间
   public function setRunTime($param)
   {

       try {
           if ($param['type'] == 'insert') {
               Db::table('runttime')->allowField(true)->data($param)->save();
           } else {
               Db::table('runttime')->allowField(true)->save($param, ["device_id" => $param["device_id"]]);
           }
           return true;

       } catch (\Exception $e) {
           $this->error = 'Update failure';
           return false;
       }

   }
    /**
     * 创建设备
     * @param  array $param [description]
     */
    public function createData($param)
    {
       // 验证
        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }

    /**
     * 通过id修改设备
     * @param  array $param [description]
     */
    public function updateDataById($param, $id)
    {
        $this->startTrans();

        try {
            $this->allowField(true)->save($param, ['device_id' => $id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }
}