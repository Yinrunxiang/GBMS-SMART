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

class Medicine extends Common
{

    protected $name = 'medicine';


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
        $map = [];
        //根据keywords筛选信息
        if ($keywords) {
            $map['medicine.id|medicine.name'] = ['like', '%' . $keywords . '%'];
        }

//		$map['doctor.doctor_id'] = array('neq', 1);
//        $map[] = array();
        $dataCount = $this
            ->where($map)
            ->count('medicine.id');

        $list = $this
            ->where($map);

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list
            ->field('medicine.*')
            ->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }
    public function createData($param)
    {
        if (empty($param['type'])) {

            $this->error = 'Please check the medicine type';
            return false;
        }
        $map['name'] = $param['name'];
        $dept = $this->where($map)->find();
        if ($dept) {
            $this->error = 'The Medicine has been in existence';
            return false;
        }
        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch(\Exception $e) {
            $this->error = 'Add failure';
            return false;
        }
    }

}