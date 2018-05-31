<?php
// +----------------------------------------------------------------------
// | Description: 场景
// +----------------------------------------------------------------------
// | Author: GBMS-SMART
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class ScheduleCommand extends Common
{

    protected $name = 'schedule_command';

    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($param)
    {

        $schedule = $param['id'];
        $map = [];
        $map['schedule'] = ['=', $schedule];
        $data = $this->alias('a')
            ->join('device b', 'a.device = b.id', 'left')
            ->join('room c', 'b.room = c.id and b.address = c.address and b.floor = c.floor', 'left')
            ->where($map)
            ->field('schedule,a.id as schedule_id,a.device as id,subnetid,deviceid,b.device as device,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,b.address,b.floor,b.room,room_name')
            ->select();
        return $data;
    }

    /**
     * 创建ScheduleCommand
     * @param  array $param [description]
     */
    public function createData($param, $newID)
    {
        $id = $param['id'];
        $schedule = $param['schedule'];
        $devices = $param['devices'];
       // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        $this->startTrans();
        try {

            if (!empty($id)) {
                $this->where('schedule', $id)->delete();

            } else {
                $id = $newID;
            }
            $list = [];
            foreach ($devices as $k => $v) {
                $device = $v;
                $data = ['schedule' => $id, 'device' => $device['id'], 'on_off' => $device['on_off'], 'mode' => $device['mode'], 'grade' => $device['grade'], 'status_1' => $device['operation_1'], 'status_2' => $device['operation_2'], 'status_3' => $device['operation_3'], 'status_4' => $device['operation_4'], 'status_5' => $device['operation_5']];
                array_push($list, $data);

            }
            $this->allowField(true)->insertAll($list);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }
    /**
     * 删除ScheduleCommand
     * @param  array $param [description]
     */
    public function delCommandById($param)
    {
        $id = $param['id'];
        $this->startTrans();
        try {
            $this->where('id', '=', $id)->delete();
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
    public function delDatas($param)
    {
        $ids = $param['ids'];
        $map = [];
        $map['schedule'] = ['in', $ids];
        $this->startTrans();
        try {

            $this->where($map)->delete();

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Delete failure';
            return false;
        }
    }
}