<?php
// +----------------------------------------------------------------------
// | Description: 场景
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Schedule extends ApiCommon
{

    public function index()
    {
        $scheduleModel = model('Schedule');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $scheduleModel->getDataList($keywords,$page,$limit);
        return resultArray(['data' => $data]);
    }
    public function read()
    {
        $scheduleCommandModel = model('ScheduleCommand');
        $param = $this->param;
        $data = $scheduleCommandModel->getDataList($param);
        return resultArray(['data' => $data]);
    }
    public function save()
    {
        $param = $this->param;
        // $devices = $param['devices'];
        // return resultArray(['data' => $param ]);
        $scheduleModel = model('Schedule');
        $data = $scheduleModel->createData($param);
        if (!$data || !$data[0]) {
            return resultArray(['error' => $scheduleModel->getError()]);
        }
        $newID = $data[1];
        $scheduleCommandModel = model('ScheduleCommand');
        
        $data = $scheduleCommandModel->createData($param,$newID);
        if (!$data) {
            return resultArray(['error' => $scheduleCommandModel->getError()]);
        }
        if(empty($param['id'])){
            return resultArray(['data' => 'Add success']);
        }else{
            return resultArray(['data' => 'Update success']);
        }
        
    }
    public function deleteSchedule()
    {
        $scheduleModel = model('Schedule');
        $param = $this->param;
        $data = $scheduleModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $scheduleModel->getError()]);
        }
        $scheduleCommandModel = model('ScheduleCommand');
        $param = $this->param;
        $data = $scheduleCommandModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $scheduleCommandModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
    public function deleteCommand()
    {
        $scheduleCommandModel = model('ScheduleCommand');
        $param = $this->param;
        $data = $scheduleCommandModel->delCommandById($param);
        if (!$data) {
            return resultArray(['error' => $scheduleCommandModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
}
 