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

class Mood extends Common
{

    protected $name = 'mood';

    public function getDataList()
    {
        $map = [];
        $map['mood.address'] = ['=', $address];
        $map['mood.floor'] = ['=', $floor];
        $map['mood.room'] = ['=', $room];
        $list = $this
            ->alias('mood')
            ->join('device device', 'mood.device=device.id', 'LEFT')
            ->join('address address', 'device.address=address.address', 'LEFT')
            ->where($map)
            ->field('mood.*,subnetid,deviceid,channel,channel_spare,devicetype,mac,ip,port')
            ->select();
        $data['list'] = $list;
        return $data;
    }

    /**
     * 创建设备
     * @param  array $param [description]
     */
    public function createData($param)
    {
        $mood = $param['mood'];
        $address = $param['address'];
        $floor = $param['floor'];
        $room = $param['room'];
        $devicetypes = $param['devicetypes'];
        $curtains = $param['curtains'];
        // // 验证
        // $validate = validate($this->name);
        // if (!$validate->check($param)) {
        //     $this->error = $validate->getError();
        //     return false;
        // }
        $map = [];
        $map['mood'] = ['=', $mood];
        $map['address'] = ['=', $address];
        $map['floor'] = ['=', $floor];
        $map['room'] = ['=', $room];
        $getMoodCount = $this->where($map)->count();
        if ($getMoodCount > 0) {
            $this->error = 'mood name already exists';
            return;
        }
        $this->startTrans();
        try {
            foreach ($devicetypes as $k => $v) {
                $map = [];
                $map['address'] = ['=', $address];
                $map['floor'] = ['=', $floor];
                $map['room'] = ['=', $room];
                $map['devicetype'] = ['=', $v];
                $data = Db::table('device') . field("'" . $mood . "' as mood,'" . $address . "' as address,'" . $floor . "' as floor,'" . $room . "' as room,id,case when on_off = 'on' then '1' when on_off='off' or on_off='' or on_off is null then '0' end as on_off,mode,grade,operation_1,operation_2,operation_3")->where($map)->select();
                $this->data($data)->insert();
            }
            foreach ($curtains as $k => $v) {
                $curtain = $v;
                $on_off = $curtain->on_off ? '1' : '0';
                $this->data(['on_off' => $on_off])->update();
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
     * 通过id修改设备
     * @param  array $param [description]
     */
    public function delDatas($param)
    {
        $map = [];
        $map['mood'] = ['=', $mood];
        $map['address'] = ['=', $address];
        $map['floor'] = ['=', $floor];
        $map['room'] = ['=', $room];
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