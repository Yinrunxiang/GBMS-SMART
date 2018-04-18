<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Device extends ApiCommon
{

    public function index()
    {   
        $deviceModel = model('Device');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $deviceModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $deviceModel = model('Device');
        $param = $this->param;
        $data = $deviceModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $deviceModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

//    public function save()
//    {
//        $deviceModel = model('Device');
//        $param = $this->param;
//        $data = $deviceModel->createData($param);
//        if (!$data) {
//            return resultArray(['error' => $deviceModel->getError()]);
//        }
//        return resultArray(['data' => 'Add success']);
//    }

    public function update()
    {
        $deviceModel = model('Device');
        $param = $this->param;
        $data = $deviceModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $deviceModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $deviceModel = model('Device');
        $param = $this->param;
        $data = $deviceModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $deviceModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $deviceModel = model('Device');
        $param = $this->param;
        $data = $deviceModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $deviceModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $deviceModel = model('Device');
        $param = $this->param;
        $data = $deviceModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $deviceModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 