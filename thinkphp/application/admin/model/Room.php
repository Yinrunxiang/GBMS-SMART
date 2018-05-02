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

class Room extends Common
{

    protected $name = 'room';

    public function getDataList($keywords = "", $page = 0, $limit = 0)
	{
		$data = $this->select();
		usort($data, function ($a, $b) {
			if (intval($a->room) == intval($b->room)) return 0;
			if (intval($a->room) < intval($b->room)) {
				return -1;
			} else {
				return 1;
			}
		});
		return $data;
	}
}