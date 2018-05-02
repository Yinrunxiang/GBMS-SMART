<?php
// +----------------------------------------------------------------------
// | Description: 楼层
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Floor extends Common
{

	protected $name = 'floor';

	public function getDataList($keywords = "", $page = 0, $limit = 0)
	{
		$data = $this->select();
		usort($data, function ($a, $b) {
			if (intval($a->floor) == intval($b->floor)) return 0;
			if (intval($a->floor) < intval($b->floor)) {
				return -1;
			} else {
				return 1;
			}
		});
		return $data;
	}
}