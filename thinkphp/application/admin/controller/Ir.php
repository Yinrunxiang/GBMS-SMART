<?php
// +----------------------------------------------------------------------
// | Description: 灯光品牌
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Ir extends ApiCommon
{

    public function index()
    {   
        $IrModel = model('Ir');
        $param = $this->param; 
        $data = $IrModel->getDataList($param);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $IrModel = model('Ir');
        $param = $this->param;
        $data = $IrModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $IrModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

   public function save()
   {
       $IrModel = model('Ir');
       $param = $this->param;
       $data = $IrModel->createData($param);
       if (!$data) {
           return resultArray(['error' => $IrModel->getError()]);
       }
       return resultArray(['data' => 'Add success']);
   }

    public function update()
    {
        $IrModel = model('Ir');
        $param = $this->param;
        $data = $IrModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $IrModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }


    public function delete()
    {
        $IrModel = model('Ir');
        $param = $this->param;
        $data = $IrModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $IrModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }
    
}
 