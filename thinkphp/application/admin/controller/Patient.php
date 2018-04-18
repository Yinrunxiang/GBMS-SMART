<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Patient extends ApiCommon
{

    public function index()
    {   
        $patientModel = model('patient');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $patientModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $patientModel = model('patient');
        $param = $this->param;
        $data = $patientModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $patientModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 