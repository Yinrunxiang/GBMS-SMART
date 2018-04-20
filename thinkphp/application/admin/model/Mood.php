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
     * [getDataById 根据主键获取详情]
     */
    public function getDataById($id = '')
    {
        $data = $this->get($id);
        if (!$data) {
            $this->error = 'This data is not available';
            return false;
        }
        return $data;
    }

    /**
     * 创建设备
     * @param  array $param [description]
     */
    public function createData($param)
    {
       // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

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
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }
        $this->startTrans();

        try {
            $this->allowField(true)->save($param, ['id' => $id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }
}