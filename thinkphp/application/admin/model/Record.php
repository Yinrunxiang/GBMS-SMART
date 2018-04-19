<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: GBMS-SMART
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Record extends Common
{

    protected $name = 'record';


    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataCount($param)
    {
        $map = [];
        //根据keywords筛选信息
            $map['record_date'] = ['>=', '%' . $param['beginDate'] . '%'];
            $map['record_date'] = ['<', '%' . $param['endDate'] . '%'];
            $map['devicetype'] = ['in', ["ac","light"]];
        $dataCount = $this
            ->where($map)
            ->count('id');

        return $dataCount;
    }
    public function getDataList($start,$end,$beginDate,$endDate)
    {
        $map = [];
        //根据keywords筛选信息
            $map['record_date'] = ['>=', '%' . $beginDate . '%'];
            $map['record_date'] = ['<', '%' . $endDate . '%'];
            $map['devicetype'] = ['in', ["ac","light"]];
        $data = $this
            ->where($map)
            ->limit($start,$end)
            ->select();

        return $data;
    }

    public function createData($param)
    {
        // 验证
        $param['create_time'] = date('y-m-d H:i:s');
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch (\Exception $e) {
            $this->error = 'Add failure';
            return false;
        }
    }

    public function getRecordByUser($user, $usertype)
    {
        $map = [];
        //根据keywords筛选信息
        if ($user) {
            $map['record.' . $usertype . '|' . $usertype . '.name'] = ['=', $user];
        }
        $dataCount = $this
            ->where($map)
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->count('patient.id');

        $list = $this
            ->where($map)
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->join('user nurse', 'record.doctor=nurse.user_id', 'LEFT')
            ->join('disease disease', 'record.disease=disease.Id', 'LEFT')
            ->join('medicine medicine', 'record.medicine=medicine.Id', 'LEFT');

        $list = $list
            ->field('record.*,patient.name as patient_name,doctor.name as doctor_name,nurse.name as nurse_name,disease.name as disease_name,medicine.name as medicine_name')
            ->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }

    /**
     * 通过id修改病历信息
     * @param  array $param [description]
     * @param  [string]  $id [病历ID]
     * @param  array $param [description]
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }
        $this->startTrans();

        try {
            $this->allowField(true)->save($param, ['Id' => $id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }
}