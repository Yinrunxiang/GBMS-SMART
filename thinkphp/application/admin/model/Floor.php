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

    public function getDataList($keywords = "", $page=0, $limit=0)
	{
		$data = $this
			->order('floor'+'0')->select();
		return $data;
	}
}