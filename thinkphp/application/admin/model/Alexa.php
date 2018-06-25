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

class Alexa extends Common
{

    protected $name = 'alexa';

    public function getDataList()
    {
        $data = $this->select();
        return $data;
    }


    /**
     * 创建Alexa Token
     * @param  array $param [description]
     */
    public function createData()
    {
        $this->startTrans();
        try {
            $token = md5(md5(time()).rand(10000, 99999));
            $this->data(['token'=>$token])->allowField(true)->save();
            $this->commit();
            // return true;
            $data = array();
            $data["alexa"] = $this->select();
            return $data;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Add failure';
            return false;
        }
    }




}