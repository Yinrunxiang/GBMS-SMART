<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Doctor extends ApiCommon
{

    public function index()
    {   
        $doctorModel = model('Doctor');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $doctorModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $doctorModel = model('Doctor');
        $param = $this->param;
        $data = $doctorModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $doctorModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

//    public function save()
//    {
//        $doctorModel = model('Doctor');
//        $param = $this->param;
//        $data = $doctorModel->createData($param);
//        if (!$data) {
//            return resultArray(['error' => $doctorModel->getError()]);
//        }
//        return resultArray(['data' => 'Add success']);
//    }

    public function update()
    {
        $doctorModel = model('Doctor');
        $param = $this->param;
        $data = $doctorModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $doctorModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $doctorModel = model('Doctor');
        $param = $this->param;
        $data = $doctorModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $doctorModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $doctorModel = model('Doctor');
        $param = $this->param;
        $data = $doctorModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $doctorModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $doctorModel = model('Doctor');
        $param = $this->param;
        $data = $doctorModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $doctorModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 