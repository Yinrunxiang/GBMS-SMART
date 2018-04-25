<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Address extends ApiCommon
{

    public function index()
    {   
        $addressModel = model('Address');
        $data = $addressModel->getDataList();
        return resultArray(['data' => $data]);
    }

   public function save()
   {
       $addressModel = model('Address');
       $param = $this->param;
       $data = $addressModel->createData($param);
       if (!$data) {
           return resultArray(['error' => $addressModel->getError()]);
       }
       return resultArray(['data' => 'Add success']);
   }

    public function update()
    {
        $addressModel = model('Address');
        $param = $this->param;
        $data = $addressModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $addressModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }
    public function delete()
    {
        $addressModel = model('Address');
        $param = $this->param;
        $data = $addressModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $addressModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }   
}
 