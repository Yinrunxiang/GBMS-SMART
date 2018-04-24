<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Room extends ApiCommon
{

    public function index()
    {   
        $roomModel = model('Room');
        $data = $roomModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->getDataById($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function update()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }
    
    public function delete()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }   
}
 