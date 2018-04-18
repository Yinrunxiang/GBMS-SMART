<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Medicine extends ApiCommon
{

    public function index()
    {   
        $medicineModel = model('Medicine');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $medicineModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $medicineModel = model('Medicine');
        $param = $this->param;
        $data = $medicineModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $medicineModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 