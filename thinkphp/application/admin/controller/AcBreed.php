<?php
// +----------------------------------------------------------------------
// | Description: 空调品牌
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class AcBreed extends ApiCommon
{

    public function index()
    {   
        $acBreedModel = model('AcBreed');
        $data = $acBreedModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $acBreedModel = model('AcBreed');
        $param = $this->param;
        $data = $acBreedModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $acBreedModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

   public function save()
   {
       $acBreedModel = model('AcBreed');
       $param = $this->param;
       $data = $acBreedModel->createData($param);
       if (!$data) {
           return resultArray(['error' => $acBreedModel->getError()]);
       }
       return resultArray(['data' => 'Add success']);
   }

    public function update()
    {
        $acBreedModel = model('AcBreed');
        $param = $this->param;
        $data = $acBreedModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $acBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }


    public function delete()
    {
        $acBreedModel = model('AcBreed');
        $param = $this->param;
        $data = $acBreedModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $acBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }   
}
 