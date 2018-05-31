<?php
// +----------------------------------------------------------------------
// | Description: 房间
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Room extends ApiCommon
{

    public function index()
    {   
        $roomModel = model('Room');
        $data = $roomModel->getDataList();
        return resultArray(['data' => $data]);
    }

   public function save()
   {
       $roomModel = model('Room');
       $param = $this->param;
       $data = $roomModel->createData($param);
       if (!$data) {
           return resultArray(['error' => $roomModel->getError()]);
       }
       $data['result'] = 'Add success';
       return resultArray(['data' => $data]);
   }

    public function update()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->updateDataById($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        $data['result'] = 'Update success';
        return resultArray(['data' => $data]);
    }

    public function delete()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        $data['result'] = 'Delete success';
        return resultArray(['data' => $data]);
    }   
    public function collect()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->collect($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'Collect success']);
    }   
    public function uncollect()
    {
        $roomModel = model('Room');
        $param = $this->param;
        $data = $roomModel->uncollect($param);
        if (!$data) {
            return resultArray(['error' => $roomModel->getError()]);
        } 
        return resultArray(['data' => 'UnCollect success']);
    }   
}
 