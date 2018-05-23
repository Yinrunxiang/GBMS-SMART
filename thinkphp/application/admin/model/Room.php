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
use app\admin\model\Device;
class Room extends Common
{

	protected $name = 'room';

	public function getDataList($keywords = "", $page = 0, $limit = 0)
	{
		if ($_SERVER['SERVER_NAME'] == "localhost") {
			$host_name = exec("hostname");
			$host_ip = gethostbyname($host_name);
		} else {
			$host_ip = $_SERVER['SERVER_NAME'];
		}
		$image_addr = "http://" . $host_ip . ":" . $_SERVER["SERVER_PORT"];
        $data = $this
		->alias('a')
        ->join('address b', 'a.address=b.id', 'LEFT')
        ->join('floor c', 'a.floor=c.id', 'LEFT')
		->field('a.id,room,room_name,a.image,b.country,a.address,a.floor,a.status,a.comment,b.address as address_name,c.floor as floor_name,a.lat,a.lng,width,height')
		->order('country,address,floor_name+0,room+0')
		->select();
		foreach ($data as $k => $v) {
			$v["image_addr"] = $image_addr;
			$v["image_full"] = $v["image"] == "" ? "" : $image_addr . $v["image"];
		}
		// usort($data, function ($a, $b) {
		// 	if (intval($a->room) == intval($b->room)) return 0;
		// 	if (intval($a->room) < intval($b->room)) {
		// 		return -1;
		// 	} else {
		// 		return 1;
		// 	}
		// });
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
        try {
            $this->allowField(true)->save($param, ['address'=> ['=',$param['address']],'floor'=> ['=',$param['floor']],'room'=> ['=',$param['id']]]);
            $data = array();
            $data["room"] = $this->getDataList();
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
                $this->where(['address'=> ['=',$v['address']],'floor'=> ['=',$v['floor']],'room'=> ['=',$v['room']]])->delete();
                Db::table('device')->where(['address'=> ['=',$v['address']],'floor'=> ['=',$v['floor']],'room'=> ['=',$v['room']]])->delete();
            }
            $this->commit();
            $data = array();
            $data["room"] = $this->getDataList();
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