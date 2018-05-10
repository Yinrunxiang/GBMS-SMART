<?php
// +----------------------------------------------------------------------
// | Description: 灯光品牌
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class LightBreed extends ApiCommon
{

    public function index()
    {   
        $lightBreedModel = model('LightBreed');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $lightBreedModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $lightBreedModel = model('LightBreed');
        $param = $this->param;
        $data = $lightBreedModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $lightBreedModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

   public function save()
   {
       $lightBreedModel = model('LightBreed');
       $param = $this->param;
       $data = $lightBreedModel->createData($param);
       if (!$data) {
           return resultArray(['error' => $lightBreedModel->getError()]);
       }
       return resultArray(['data' => 'Add success']);
   }

    public function update()
    {
        $lightBreedModel = model('LightBreed');
        $param = $this->param;
        $data = $lightBreedModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $lightBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }


    public function delete()
    {
        $lightBreedModel = model('LightBreed');
        $param = $this->param;
        $data = $lightBreedModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $lightBreedModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }
    
}
 