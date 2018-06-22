<?php
// +----------------------------------------------------------------------
// | Description: Alexa
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Alexa extends ApiCommon
{

    public function index()
    {
        $alexaModel = model('Alexa');
        $data = $alexaModel->getDataList();
        if (count($data) > 0) {
            return resultArray(['data' => $data[0]['token']]);
        } else {
            $data = $alexaModel->createData();
            if (!$data) {
                return resultArray(['error' => $alexaModel->getError()]);
            }
            $data['result'] = 'success';
            return resultArray(['data' => $data[0]['token']]);
        }

    }

    public function save()
    {
        $alexaModel = model('Alexa');
        $param = $this->param;
        $data = $alexaModel->createData();
        if (!$data) {
            return resultArray(['error' => $alexaModel->getError()]);
        }
        $data['result'] = 'success';
        return resultArray(['data' => $data['token']]);
    }
}
 