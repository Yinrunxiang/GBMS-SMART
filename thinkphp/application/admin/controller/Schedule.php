<?php
// +----------------------------------------------------------------------
// | Description: 计划任务
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Schedule extends ApiCommon
{

    public function index()
    {
        $moodModel = model('Schedule');
        $data = $moodModel->getDataList();
        return resultArray(['data' => $data]);
    }
    
    public function read()
    {
        $moodModel = model('Schedule');
        $param = $this->param;
        $data = $moodModel->getCommandList($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function save()
    {
        $moodModel = model('Schedule');
        $param = $this->param;
        $data = $moodModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }

    public function delete()
    {
        $moodModel = model('Schedule');
        $param = $this->param;
        $data = $moodModel->delDataById($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }

    public function delCommandById()
    {
        $moodModel = model('Schedule');
        $param = $this->param;
        $data = $moodModel->delCommandById($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
}
 