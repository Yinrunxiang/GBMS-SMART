<?php
// +----------------------------------------------------------------------
// | Description: LED品牌
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class LedBreed extends ApiCommon
{

    public function index()
    {   
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $ledBreedModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $data = $ledBreedModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $ledBreedModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

//    public function save()
//    {
//        $ledBreedModel = model('LedBreed');
//        $param = $this->param;
//        $data = $ledBreedModel->createData($param);
//        if (!$data) {
//            return resultArray(['error' => $ledBreedModel->getError()]);
//        }
//        return resultArray(['data' => 'Add success']);
//    }

    public function update()
    {
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $data = $ledBreedModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $ledBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }

    public function delete()
    {
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $data = $ledBreedModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $ledBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $data = $ledBreedModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $ledBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $ledBreedModel = model('LedBreed');
        $param = $this->param;
        $data = $ledBreedModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $ledBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }
    
}
 