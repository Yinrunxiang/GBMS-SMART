<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Department extends ApiCommon
{

    public function index()
    {   
        $departmentModel = model('Department');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $departmentModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $departmentModel = model('Department');
        $param = $this->param;
        $data = $departmentModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $departmentModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 