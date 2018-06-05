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

    public function getDataList($keywords,$page,$limit)
    {
        $map = [];
        //根据keywords筛选Macro信息
        if ($keywords) {
            $map['schedule'] = ['like', '%' . $keywords . '%'];
        }
        $data = $this->where($map);

        // 若有分页
        if ($page && $limit) {
            $data = $data->page($page, $limit);
        }
        $data = $data->select();
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
        $data = ["schedule"=>$schedule,"type"=>$type,"time_1"=>$time_1,"time_2"=>$time_2,"mon"=>$mon,"tues"=>$tues,"wed"=>$wed,"thur"=>$thur,"fri"=>$fri,"sat"=>$sat,"sun"=>$sun];
        $newID= "";
        $this->startTrans();
        try {
            if (empty($id)) {
                $newID = $this->insertGetId($data);
            } else {
                $this->allowField(true)->save($param, ['id' => $id]);
            }
            $this->commit();
            $data = [true, $newID];
            return $data;
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
        $this->startTrans();
        try {
            foreach ($ids as $k => $v) {
                $this->where('id', $v)->delete();
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Delete failure';
            return false;
        }
    }
}