<?php
// +----------------------------------------------------------------------
// | Description: 状态记录
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Record extends ApiCommon
{

    public function index()
    {   
        $recordModel = model('Record');
        $param = $this->param;   
        $data = $recordModel->getDataList($param['start'],$param['end'],$param['beginDate'],$param['endDate']);
        return resultArray(['data' => $data]);
    }
    public function count()
    {   
        $recordModel = model('Record');
        $param = $this->param;   
        $data = $recordModel->getDataCount($param['beginDate'],$param['endDate']);
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function update()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        } 
        return resultArray(['data' => 'Update success']);
    }
}
 