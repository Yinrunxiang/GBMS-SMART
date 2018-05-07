<?php
// +----------------------------------------------------------------------
// | Description: 场景
// +----------------------------------------------------------------------
// | Author: GBMS-SMART
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Macro extends Common
{

    protected $name = 'macro';

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
        $keywords = $param['keywords'];
        $limit = $param['limit'];
        $page = $param['page'];
        $map = [];
        //根据keywords筛选Macro信息
        if ($keywords) {
            $map['macro'] = ['like', '%' . $keywords . '%'];
        }
        $data = $this->where($map);

        // 若有分页
        if ($page && $limit) {
            $data = $data->page($page, $limit);
        }
        $data = $data->select();
        return $data;
    }

    /**
     * 创建Macro
     * @param  array $param [description]
     */
    public function createData($param)
    {
        $id = $param['id'];
        $macro = $param['macro'];
        $newID= "";
       // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $this->startTrans();
        try {

            if (empty($id)) {
                // $this->data(['macro' => $macro])->insert();
                $newID = $this->insertGetId(['macro' => $macro]);
            } else {
                $this->allowField(true)->save($param, ['id' => $id]);
            }
            $this->commit();
            $data = [true, $newID];
            return $data;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }

    /**
     * 删除Macro
     * @param  array $param [description]
     */
    public function delDatas($param)
    {
        $ids = $param['ids'];
        $this->startTrans();
        try {
            foreach ($ids as $k => $v) {
                $this->where('id', $v)->delete();
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Delete failure';
            return false;
        }
    }


    /**
     * 删除Macro
     * @param  array $param [description]
     */
    public function getNewID()
    {
        $newID = $this->max('id')->select();
        return $newID;
    }


}