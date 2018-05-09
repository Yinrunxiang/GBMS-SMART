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
        $floorModel = model('Floor');
        $data = $floorModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $floorModel = model('Floor');
        $param = $this->param;
        $data = $floorModel->getDataById($param);
        if (!$data) {
            return resultArray(['error' => $floorModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $floorModel = model('Floor');
        $param = $this->param;
        $data = $floorModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $floorModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $floorModel = model('Floor');
        $param = $this->param;
        $data = $floorModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $floorModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }
    
    public function delete()
    {
        $floorModel = model('Floor');
        $param = $this->param;
        $data = $floorModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $floorModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }   
}
 