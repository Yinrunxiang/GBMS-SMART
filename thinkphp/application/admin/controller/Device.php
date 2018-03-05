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
        $model = model('Device');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $model->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $model = model('Department');
        $param = $this->param;
        $data = $model->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 