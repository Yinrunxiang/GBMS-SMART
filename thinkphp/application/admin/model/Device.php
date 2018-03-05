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

class Department extends Common
{

    protected $name = 'department';


    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList()
    {
        $map = [];
        $dataCount = $this
            ->where($map)
            ->count('device.id');
        $list = $this
            ->where($map);
        // 若有分页
        $list = $list
            ->alias('a')
            ->join('address b', 'address as b on a.address = b.address', 'LEFT')
            ->join('room r', 'a.address = r.address and a.floor = r.floor and a.room = r.room', 'LEFT')
            ->join('room r', 'a.address = r.address and a.floor = r.floor', 'LEFT')
            ->field('a.id,device,subnetid,deviceid,channel,channel_spare,b.id as addressid,mac,ip,port,lat,lng,a.floor,a.room,devicetype,case when on_off = "on" then now() - run_date else null end as run_time,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime,a.floor,a.room,room_name,x_axis,y_axis,operation_1,operation_2,operation_3,operation_4,operation_5,operation_6,operation_7,operation_8,operation_9,operation_10,operation_11,operation_12,operation_13,operation_14,operation_15,operation_16,operation_17,operation_18,operation_19,operation_20,operation_21')
            ->order('a.address,a.floor,a.room + 0,devicetype,breed,id')
            ->select();
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }

    public function createData($param)
    {

        $map['device'] = $param['device'];
        $dept = $this->where($map)->find();
        if ($dept) {
            $this->error = '该设备已存在';
            return false;
        }
        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch (\Exception $e) {
            $this->error = 'Add failure';
            return false;
        }
    }

}