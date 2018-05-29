<?php
// +----------------------------------------------------------------------
// | Description: 建筑模块
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;
use app\admin\model\Floor;
use app\admin\model\Room;
use app\admin\model\Device;
class Address extends Common
{

    protected $name = 'address';

    /**
     * [getDataList 获取数据]
     * @param     string                   $id [主键]
     * @return    [array]                       
     */
    public function getDataList($keywords = "", $page = 0, $limit = 0)
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $host_name = exec("hostname");
            $host_ip = gethostbyname($host_name);
        } else {
            $host_ip = $_SERVER['SERVER_NAME'];
        }
        $image_addr = "http://" . $host_ip . ":" . $_SERVER["SERVER_PORT"];

        $data = $this->order('country')->select();
        foreach ($data as $k => $v) {
            $v["image_addr"] = $image_addr;
            $v["image_full"] = $v["image"] == "" ? "" : $image_addr . $v["image"];
        }
        return $data;
    }

    /**
     * [createData 新建]
     * @param     array                    $param [description]
     * @return    [array]                         [description]
     */
    public function createData($param)
    {
		
		// 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        try {
            $this->allowField(true)->save($param);
            $id = $this->getLastInsID();
            $floor_num = intval($param['floor_num']);
            $floor_list = [];
            for ($i = 1; $i <= $floor_num; $i++) {
                $floor_data = ['floor' => $i, 'room_num' => 0, 'address' => $id, 'status' => 'enabled'];
                array_push($floor_list, $floor_data);
            }
            Db::table('floor')->insertAll($floor_list);
            $data = array();

            $data["address"] = $this->getDataList();
            $Floor = new Floor();
            $data["floor"] = $Floor->getDataList();
            return $data;
        } catch (\Exception $e) {
            $this->error = 'Add failure';
            return false;
        }
    }

    /**
     * [updateDataById 编辑]
     * @param     [type]                   $param [description]
     * @param     [type]                   $id    [description]
     * @return    [type]                          [description]
     */
    public function updateDataById($param)
    {
        $checkData = $this->get($param['id']);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }

		// 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $floor_count = Db::table('floor')->where('address',$param['id'])->count('id');
        $floor_num = intval($param['floor_num']);
        try {
            //更新地址表
            $this->allowField(true)->save($param, ['id'=> $param['id']]);
            //更新与地址有关联的表
            // Db::table('device')->where('address', $param['id'])->update(['address' => $param['address']]);
            // Db::table('floor')->where('address', $param['id'])->update(['address' => $param['address']]);
            // Db::table('room')->where('address', $param['id'])->update(['address' => $param['address']]);
            // Db::table('record')->where('address', $param['id'])->update(['address' => $param['address']]);
            //更新楼层数量
            $floor_list = [];
            if ($floor_num > $floor_count) {
                for ($i = $floor_count + 1; $i <= $floor_num; $i++) {
                    $floor_data = ['floor' => $i, 'room_num' => 0, 'address' => $param['id'], 'status' => 'enabled'];
                    array_push($floor_list, $floor_data);
                }
                Db::table('floor')->insertAll($floor_list);
            } else if ($floor_num < $floor_count) {
                Db::table('floor')->where(['address'=>['=',$param['id']],'floor'=>['>',$floor_num]])->delete();
                Db::table('room')->where(['address'=>['=',$param['id']],'floor'=>['>',$floor_num]])->delete();
                Db::table('device')->where(['address'=>['=',$param['id']],'floor'=>['>',$floor_num]])->delete();
            }
            $data = array();
            $data["address"] = $this->getDataList();
            $Floor = new Floor();
            $data["floor"] = $Floor->getDataList();
            $Room = new Room();
            $data["room"] = $Room->getDataList();
            $Device = new Device();
            $data["device"] = $Device->getDataList();
            return $data;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }

    /**
     * 删除Schedule
     * @param  array $param [description]
     */
    public function delDatas($param)
    {
        $selections = $param['selections'];
        $this->startTrans();
        try {
            foreach ($selections as $k => $v) {
                $this->where('id', $v['id'])->delete();
                Db::table('floor')->where('address', $v['id'])->delete();
                Db::table('room')->where('address', $v['id'])->delete();
                Db::table('device')->where('address', $v['id'])->delete();
            }
            $this->commit();
            $data = array();
            $data["address"] = $this->getDataList();
            $Floor = new Floor();
            $data["floor"] = $Floor->getDataList();
            $Room = new Room();
            $data["room"] = $Room->getDataList();
            $Device = new Device();
            $data["device"] = $Device->getDataList();
            return $data;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Delete failure';
            return false;
        }
    }
}