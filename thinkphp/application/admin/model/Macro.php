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

class Marco extends Common
{

    protected $name = 'marco';

    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($keywords, $page, $limit)
    {
        $map = [];
        //根据keywords筛选Macro信息
        if ($keywords) {
            $map['macro'] = ['like', '%' . $keywords . '%'];
        }
        $data = $this->where($map);

        // 若有分页
        if ($page && $limit) {
            $data = $data->page($page, $limit);
        }
        $data = $data->select();
        return $data;
    }

    /**
     * [getDataById 根据主键获取详情]
     */
    public function getDataById($param)
    {
        $id = $param['id'];
        Db::table('macro_command')
            ->alias('a')
            ->join('device b', 'a.device = b.id', 'left')
            ->join('room c', 'b.room = c.room and b.address = c.address and b.floor = c.floor', 'left')
            ->field('macro,a.id as macro_id,a.device as id,subnetid,deviceid,b.device as device,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,b.address,b.floor,b.room,room_name,a.time')
            ->where('macro', '=', $id)
            ->select();
        $list = $this->where('macro', '=', $id)->select();
        if (!$list) {
            $this->error = 'This data is not available';
            return false;
        }
        $data['list'] = $list;
        return $data;
    }

    /**
     * [getDataById 根据主键获取详情]
     */
    public function run($param)
    {
        $id = $param['id'];
        Db::table('macro_command')
            ->alias('a')
            ->join('device b', 'a.device = b.id', 'left')
            ->join('address c', 'b.address = c.address', 'left')
            ->field('a.id as macro_id,a.device as id,subnetid,deviceid,channel,channel_spare,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,ip,port,mac,a.time as time,c.operation as udp_type')
            ->where('macro', '=', $id)
            ->select();
        $data = $this->where('macro', '=', $id)->select();
        if (!$data) {
            $this->error = 'This data is not available';
            return false;
        }
        return $data;
    }

    /**
     * 创建Macro
     * @param  array $param [description]
     */
    public function createData($param)
    {
        $id = $param['id'];
        $macro = $param['macro'];
        $devices = $param['devices'];
       // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        $this->startTrans();
        try {

            if (empty($id)) {
                $this->data(['macro' => $macro])->insert();
                $id = $this->max('id')->get();
            } else {
                $this->data(['macro' => $macro])->where('id', $id)->update();
                Db::table('marco_command')->where('marco', $id)->delete();
            }
            foreach ($devices as $k => $v) {
                $device = json_decode($v);
                $data = ['macro' => $id, 'device' => $device->id, 'on_off' => $device->on_off, 'mode' => $device->mode, 'grade' => $device->grade, 'status_1' => $device->operation_1, 'status_2' => $device->operation_2, 'status_3' => $device->operation_3, 'status_4' => $device->operation_4, 'status_5' => $device->operation_5, 'time' => $device->time];
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
     * 删除Macro
     * @param  array $param [description]
     */
    public function delDataById($param)
    {
        $ids = $param['ids'];
        $this->startTrans();
        try {
            foreach ($ids as $k => $v) {
                $this->where('id', '=', $v)->delete();
                Db::table('marco_command')->where('marco', '=', $v)->delete();
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
            Db::table('marco_command')->where('id', '=', $id)->delete();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }
}