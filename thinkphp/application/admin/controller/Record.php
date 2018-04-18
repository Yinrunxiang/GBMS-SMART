<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Record extends ApiCommon
{

    public function index()
    {   
        $recordModel = model('Record');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $recordModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        } 
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

    public function delete()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function deletes()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        } 
        return resultArray(['data' => 'Delete success']);
    }

    public function enables()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        } 
        return resultArray(['data' => 'Successful operation']);
    }

    public function getRecordByDoctor()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $doctor = !empty($param['doctor']) ? $param['doctor']: '';
        $data = $recordModel->getRecordByUser($doctor,'doctor');
        return resultArray(['data' => $data]);
    }

    public function getRecordByPatient()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $patient = !empty($param['patient']) ? $param['patient']: '';
        $data = $recordModel->getRecordByUser($patient,'patient');
        return resultArray(['data' => $data]);
    }

    public function beginTreatment()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->beginTreatment($param["doctor"]);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        }
        return resultArray(['data' => 'Update success']);
    }

    public function endTreatment()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->endTreatment($param["doctor"]);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        }
        return resultArray(['data' => 'Update success']);
    }

    public function missTreatment()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->missTreatment($param["doctor"]);
        if (!$data) {
            return resultArray(['error' => $recordModel->getError()]);
        }
        return resultArray(['data' => 'Update success']);
    }
    public  function treatmentTime()
    {
        $recordModel = model('Record');
        $param = $this->param;
        $data = $recordModel->treatmentTime($param["doctor"]);
        return resultArray(['data' => $data]);
    }
}
 