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

class Ir extends Common
{

    protected $name = 'ir_operation';
    /**
     * [getDataList 获取数据]
     * @param     string                   $id [主键]
     * @return    [array]                       
     */
    public function getDataList($param)
    {
        $device = $param['device'];
        $data = $this->where('device', $device)->select();
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
        $validate = validate('ir');
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

		// 验证
        $validate = validate('ir');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        try {
            $this->allowField(['ir_name','ir_value'])->save($param, ['device' => $param['device'],'ir_key' => $param['ir_key']]);
            return true;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }

    /**
     * [delDataById 根据id删除数据]
     * @param     string                   $id     [主键]
     * @param     boolean                  $delSon [是否删除子孙数据]
     * @return    [type]                           [description]
     */
    public function delDataById($param)
    {

        $this->startTrans();
        try {
            $this->where($this->getPk(), $param['id'])->delete();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = 'Delete failure';
            $this->rollback();
            return false;
        }
    }

    /**
     * [delDatas 批量删除数据]
     * @param     array                   $ids    [主键数组]
     * @param     boolean                 $delSon [是否删除子孙数据]
     * @return    [type]                          [description]
     */
    public function delDatas($ids)
    {
        if (empty($ids)) {
            $this->error = 'Please select data';
            return false;
        }
		
		// 查找所有子元素
        if ($delSon) {
            foreach ($ids as $k => $v) {
                if (!is_numeric($v)) continue;
                $childIds = $this->getAllChild($v);
                $ids = array_merge($ids, $childIds);
            }
            $ids = array_unique($ids);
        }

        try {
            $this->where($this->getPk(), 'in', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            $this->error = 'Delete failure';
            return false;
        }

    }

}