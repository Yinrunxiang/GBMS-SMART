<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Disease extends ApiCommon
{

    public function index()
    {   
        $diseaseModel = model('Disease');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $diseaseModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $diseaseModel = model('Disease');
        $param = $this->param;
        $data = $diseaseModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $diseaseModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 