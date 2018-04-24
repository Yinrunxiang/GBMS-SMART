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

class Schedule extends Common
{

    protected $name = 'schedule';

    public function getDataList($param)
    {
        $schedule = $param['schedule'];
        $limit = $param['limit'];
        $page = $param['page'];
        $map = [];
        $map['schedule'] = ['like', '%' . $schedule . '%'];
        $data = $this->where($map)->limit($page, $limit)->select();
        return $data;
    }

    public function getCommandList($param)
    {
        $schedule = $param['schedule'];
        $map = ['schedule' => ['=', $schedule]];
        $data = $this->alias('a')->join('device b','a.device = b.id','left')->join('room c','b.room = c.room and b.address = c.address and b.floor = c.floor','left')->where($map)->file('schedule,a.id as schedule_id,a.device as id,subnetid,deviceid,b.device as device,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,b.address,b.floor,b.room,room_name')->select();
        return $data;
    }

    /**
     * 创建设备
     * @param  array $param [description]
     */
    public function createData($param)
    {
        $id = $param['id'];
        $schedule = $param['schedule'];
        $type = $param['type'];
        $time_1 = $param['time_1'];
        $time_2 = $param['time_2'];
        $mon = $param['mon'];
        $tues = $param['tues'];
        $wed = $param['wed'];
        $thur = $param['thur'];
        $fri = $param['fri'];
        $sat = $param['sat'];
        $sun = $param['sun'];
        $devices = $param['devices'];
        $this->startTrans();
        try {
            if (empty($id)) {
                $this->data($param)->insert();
                $id = $this->max('id')->get();
            } else {
                $this->data(['schedule' => $schedule])->where('id', $id)->update();
                Db::table('schedule_command')->where('schedule', $id)->delete();
            }
            foreach ($devices as $k => $v) {
                $device = json_decode($v);
                $data = ['schedeule' => $id, 'device' => $device->id, 'on_off' => $device->on_off, 'mode' => $device->mode, 'grade' => $device->grade, 'status_1' => $device->operation_1, 'status_2' => $device->operation_2, 'status_3' => $device->operation_3, 'status_4' => $device->operation_4, 'status_5' => $device->operation_5, 'time' => $device->time];
                Db::table('marco_command')->data($data)->insert();
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }

   /**
     * 删除Schedule
     * @param  array $param [description]
     */
    public function delDataById($param)
    {
        $ids = $param['ids'];
        $this->startTrans();
        try {
            foreach ($ids as $k => $v) {
                $this->where('id', '=', $v)->delete();
                Db::table('schedule_command')->where('schedule', '=', $v)->delete();
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }
    /**
     * 删除Macro
     * @param  array $param [description]
     */
    public function delCommandById($param)
    {
        $id = $param['id'];
        $this->startTrans();
        try {
            Db::table('schedule_command')->where('schedule', '=', $id)->delete();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }
}