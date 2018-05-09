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
            $this->data($param)->allowField(true)->save();
            $floor_num = intval($param['floor_num']);
            $floor_list = [];
            for ($i = 1; $i <= $floor_num; $i++) {
                $floor_data = ['floor' => $i, 'room_num' => 0, 'address' => $param['address'], 'status' => 'enabled'];
                array_push($floor_list, $floor_data);
            }
            Db::table('floor')->insertAll($floor_list);
            return true;
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
        $floor_count = Db::table('floor')->where('address',$param['oldAddress'])->count('id');
        $floor_num = intval($param['floor_num']);
        try {
            //更新地址表
            $this->allowField(true)->save($param, ['address'=> $param['oldAddress']]);
            //更新与地址有关联的表
            Db::table('device')->where('address', $param['oldAddress'])->update(['address' => $param['address']]);
            Db::table('floor')->where('address', $param['oldAddress'])->update(['address' => $param['address']]);
            Db::table('room')->where('address', $param['oldAddress'])->update(['address' => $param['address']]);
            Db::table('record')->where('address', $param['oldAddress'])->update(['address' => $param['address']]);
            //更新楼层数量
            $floor_list = [];
            if ($floor_num > $floor_count) {
                for ($i = $floor_count + 1; $i <= $floor_num; $i++) {
                    $floor_data = ['floor' => $i, 'room_num' => 0, 'address' => $param['address'], 'status' => 'enabled'];
                    array_push($floor_list, $floor_data);
                }
                Db::table('floor')->insertAll($floor_list);
            } else if ($floor_num < $floor_count) {
                Db::table('floor')->where(['address'=>['=',$param['address']],'floor'=>['>',$floor_num]])->delete();
                Db::table('room')->where(['address'=>['=',$param['address']],'floor'=>['>',$floor_num]])->delete();
                Db::table('device')->where(['address'=>['=',$param['address']],'floor'=>['>',$floor_num]])->delete();
            }
            return true;
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
                $this->where('address', $v['address'])->delete();
                Db::table('floor')->where('address', $v['address'])->delete();
                Db::table('room')->where('address', $v['address'])->delete();
                Db::table('device')->where('address', $v['address'])->delete();
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