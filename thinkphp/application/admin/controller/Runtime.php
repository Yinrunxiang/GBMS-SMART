<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Runtime extends ApiCommon
{

    public function index()
    {
        $runtimeModel = model('Runtime');
        $param = $this->param;
        $id = !empty($param['device_id']) ? $param['device_id'] : '';

        $data = $runtimeModel->getDataList($id);
        return resultArray(['data' => $data]);
    }
    public function setRunTime()
    {
        $runtimeModel = model('Runtime');
        $param = $this->param;
        if($param['type'] == 'insert'){
            $data = $runtimeModel->createData($param);
        }else{
            $data = $runtimeModel->updateDataById($param, $param['id']);
        }
        if (!$data) {
            return resultArray(['error' => $runtimeModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }
}
 