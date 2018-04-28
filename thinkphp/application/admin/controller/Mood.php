<?php
// +----------------------------------------------------------------------
// | Description: 心情
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Mood extends ApiCommon
{

    public function index()
    {
        $moodModel = model('Mood');
        $data = $moodModel->getDataList();
        return resultArray(['data' => $data]);
    }
    public function save()
    {
        $moodModel = model('Mood');
        $param = $this->param;
        $data = $moodModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }
    public function delete()
    {
        $moodModel = model('Mood');
        $param = $this->param;
        $data = $moodModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $moodModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
}
 