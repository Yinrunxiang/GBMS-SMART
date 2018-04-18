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

class Device extends Common
{

    protected $name = 'device';
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
    ];
    /**
     * 获取用户所属所有用户组
     * @param  array $param [description]
     */
//    public function groups()
//    {
//        return $this->belongsToMany('group', '__ADMIN_ACCESS__', 'group_id', 'user_id');
//    }

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
        $dataCount = $this
            ->alias('device')
            ->count('device.id');
        $list = $this
            ->alias('device')
            ->join('address address', 'device.address=address.address', 'LEFT')
            ->join('room room', 'address.address = room.address and address.floor = room.floor and address.room = room.room', 'LEFT')
            ->field('a.id,maxid,device,subnetid,deviceid,channel,channel_spare,b.id as addressid,mac,ip,port,b.lat,b.lng,a.floor,a.room,devicetype,case when on_off = "on" then now() - run_date else null end as run_time,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime,a.floor,a.room,room_name,x_axis,y_axis,operation_1,operation_2,operation_3,operation_4,operation_5,operation_6,operation_7,operation_8,operation_9,operation_10,operation_11,operation_12,operation_13,operation_14,operation_15,operation_16,operation_17,operation_18,operation_19,operation_20,operation_21,a.comment,b.operation as udp_type')
            ->select();
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

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
        if (empty($param['department_name'])) {
            $this->error = 'Please check at least one department';
            return false;
        }

       // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        $this->startTrans();
        try {
            $param['doctor_password'] = user_md5($param['doctor_password']);
            $this->data($param)->allowField(true)->save();

//			foreach ($param['doctor'] as $k => $v) {
//				$userGroup['user_id'] = $this->id;
//				$userGroup['group_id'] = $v;
//				$userGroups[] = $userGroup;
//			}
//			Db::name('admin_access')->insertAll($userGroups);

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
    public function updateDataById($param, $user_id)
    {
        $checkData = $this->get($user_id);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }
        $this->startTrans();

        try {
            $this->allowField(true)->save($param, ['user_id' => $user_id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }

}