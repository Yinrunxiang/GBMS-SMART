<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Nurse extends ApiCommon
{

    public function index()
    {   
        $nurseModel = model('nurse');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $nurseModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $nurseModel = model('nurse');
        $param = $this->param;
        $data = $nurseModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $nurseModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 