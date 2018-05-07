<?php
// +----------------------------------------------------------------------
// | Description: 场景
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Macro extends ApiCommon
{

    public function index()
    {
        $macroModel = model('Macro');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $macroModel->getDataList();
        return resultArray(['data' => $data]);
    }
    public function read()
    {
        $macroCommandModel = model('MacroCommand');
        $param = $this->param;
        $data = $macroCommandModel->getDataList($param);
        return resultArray(['data' => $data]);
    }
    public function save()
    {
        $param = $this->param;
        // $devices = $param['devices'];
        // return resultArray(['data' => $param ]);
        $macroModel = model('Macro');
        $data = $macroModel->createData($param);
        if (!$data || !$data[0]) {
            return resultArray(['error' => $macroModel->getError()]);
        }
        $newID = $data[1];
        $macroCommandModel = model('MacroCommand');
        
        $data = $macroCommandModel->createData($param,$newID);
        if (!$data) {
            return resultArray(['error' => $macroCommandModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }
    public function delete()
    {
        $macroModel = model('Macro');
        $param = $this->param;
        $data = $macroModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $macroModel->getError()]);
        }
        $macroCommandModel = model('MacroCommand');
        $param = $this->param;
        $data = $macroCommandModel->delDatas($param);
        if (!$data) {
            return resultArray(['error' => $macroCommandModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
    public function deleteCommand()
    {
        $macroCommandModel = model('MacroCommand');
        $param = $this->param;
        $data = $macroCommandModel->delCommandById($param);
        if (!$data) {
            return resultArray(['error' => $macroCommandModel->getError()]);
        }
        return resultArray(['data' => 'Delete success']);
    }
    public function run()
    {
        $macroCommandModel = model('MacroCommand');
        $param = $this->param;
        $data = $macroCommandModel->run($param);
        if (!$data) {
            return resultArray(['error' => $macroCommandModel->getError()]);
        }
        return resultArray(['data' => $data]);
    }
}
 