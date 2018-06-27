<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Device extends Common
{

    protected $name = 'device';

    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($keywords = "", $page = 0, $limit = 0)
    {
        $data = $this
            ->alias('a')
            ->join('address b', 'a.address=b.id', 'LEFT')
            ->join('room c', 'a.room = c.id', 'LEFT')
            ->join('floor d', 'a.floor=d.id', 'LEFT')
            ->field('a.id,device,subnetid,deviceid,channel,channel_spare,b.address as address_name,d.floor as floor_name,mac,ip,port,b.lat,b.lng,a.floor,a.room,devicetype,case when on_off = "on" then now() - run_date else null end as run_time,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime,a.floor,a.room,room_name,x_axis,y_axis,operation_1,operation_2,operation_3,operation_4,operation_5,operation_6,operation_7,operation_8,operation_9,operation_10,operation_11,operation_12,operation_13,operation_14,operation_15,operation_16,operation_17,operation_18,operation_19,operation_20,operation_21,a.comment,b.operation as udp_type,a.alexa')
            ->order('country,address,floor_name+0,c.room+0,devicetype,breed')
            ->select();
        foreach ($data as $k => $v) {
            $v['on_off'] = $v['on_off'] == 'on' ? true : false;
        }
        return $data;
    }


    /**
     * 创建设备
     * @param  array $param [description]
     */
    public function createData($param)
    {
        $param['subnetid'] = toHex($param['subnetid']);
        $param['deviceid'] = toHex($param['deviceid']);
        $param['channel'] = toHex($param['channel']);
        $param['channel_spare'] = toHex($param['channel_spare']);
        if ($param['devicetype'] == 'curtain') {
            $param['operation_1'] = toHex($param['operation_1']);
        }
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
            // return true;
            $data = array();
            $data["device"] = $this->getDataList();
            return $data;
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
    public function updateDataById($param)
    {
        $id = $param['id'];
        $param['subnetid'] = toHex($param['subnetid']);
        $param['deviceid'] = toHex($param['deviceid']);
        $param['channel'] = toHex($param['channel']);
        $param['channel_spare'] = toHex($param['channel_spare']);
        if ($param['devicetype'] == 'curtain') {
            $param['operation_1'] = toHex($param['operation_1']);
        }
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }
        try {
            $this->allowField(true)->save($param, ['id' => $id]);
            // return true;
            $data = array();
            $data["device"] = $this->getDataList();
            return $data;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }
    /**
     * 删除device
     * @param  array $param [description]
     */
    public function delDatas($param)
    {
        $selections = $param['selections'];
        $this->startTrans();
        try {
            foreach ($selections as $k => $v) {
                $this->where('id', $v['id'])->delete();
            }
            $this->commit();
            // $data = array();
            // $data["device"] = $this->getDataList();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Delete failure';
            return false;
        }
    }
    /**
     * 通过id更新设备位置
     * @param  array $param [description]
     */
    public function updateLocation($param)
    {
        $id = $param['id'];
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
    /**
     * 修改LED颜色
     * @param  array $param [description]
     */
    public function setColor($param)
    {
        $id = $param['id'];
        $this->startTrans();

        try {
            $this->allowField(true)->save(['mode' => $param['color']], ['id' => $id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }
    /**
     * 获取IR的操作指令
     * @param  array $param [description]
     */
    public function getIrOperation($id)
    {
        $id = $param['id'];
        $data['list'] = Db::table('ir_operation')->where('id', $id)->select();
        return $data;
    }
    /**
     * 新增IR操作指令
     * @param  array $param [description]
     */
    public function insertIrOperation($param)
    {
        try {
            Db::table('ir_operation')->insert($param);
            return true;

        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }
    /**
     * 更新IR操作指令
     * @param  array $param [description]
     */
    public function updateIrOperation($param)
    {
        $id = $param['id'];
        try {
            Db::table('ir_operation')->where('id', $id)->update($param);
            return true;

        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }
    /**
     * 修改设备运行时间
     * @param  array $param [description]
     */
    public function setTime($param)
    {
        $id = $param['id'];
        $data = $param['selection'];
        $data = json_decode($data);
        $this->startTrans();
        try {
            if ($param['type'] == "start") {
                $this->allowField(['starttime'])->save(['starttime' => $data->starttime], ['id' => $id]);
            } else {
                $this->allowField(['endtime'])->save(['endtime' => $data->endtime], ['id' => $id]);
            }
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }


    }


}