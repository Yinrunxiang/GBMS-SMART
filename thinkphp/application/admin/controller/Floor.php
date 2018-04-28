<?php
// +----------------------------------------------------------------------
// | Description: 楼层
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Floor extends ApiCommon
{

    public function index()
    {   
        $roomModel = model('Floor');
        $data = $roomModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $roomModel = model('Floor');
        $param = $this->param;
        $data = $roomModel->getDataById($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function update()
    {
        $roomModel = model('Floor');
        $param = $this->param;
        $data = $roomModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }
    
    public function delete()
    {
        $roomModel = model('Floor');
        $param = $this->param;
        $data = $roomModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }   
}
 