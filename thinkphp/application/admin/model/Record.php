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
    public function getDataList($keywords, $page, $limit)
    {
        $map = [];
        //根据keywords筛选信息
        if ($keywords) {
            $map['record.id|patient.name|doctor.name'] = ['like', '%' . $keywords . '%'];
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

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list
            ->field('record.*,patient.name as patient_name,doctor.name as doctor_name,nurse.name as nurse_name,disease.name as disease_name,medicine.name as medicine_name')
            ->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

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
    // public function updateDataById($param, $id)
    // {
    //     $checkData = $this->get($id);
    //     if (!$checkData) {
    //         $this->error = 'This data is not available';
    //         return false;
    //     }
    //     $this->startTrans();

    //     try {
    //         $this->allowField(true)->save($param, ['Id' => $id]);
    //         $this->commit();
    //         return true;

    //     } catch (\Exception $e) {
    //         $this->rollback();
    //         $this->error = 'Update failure';
    //         return false;
    //     }
    // }

    public function getFirsetPatientByDoctor($doctor)
    {
        $map = [];
        $map["doctor"] = ["=", $doctor];
//        $map["begin_time"] = ["eq",null];
        $getPatient = $this
            ->where($map)
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->order('record.')
            ->min('record.*,patient.name as patient_name,doctor.name as doctor_name');
        return $getPatient;
    }

    //开始就诊
    public function beginTreatment($doctor)
    {
        $map = [];
        $map["doctor.name|record.doctor"] = ["=", $doctor];
        $map["record.status"] = ["eq", "0"];
        $id = $this
            ->where($map)
            ->where("begin_time is null")
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->order('record.create_time')
            ->min('record.Id');
        $data = [];
        $data["begin_time"] = date('y-m-d H:i:s');
        $data["status"] = "1";
        try {
            $this->allowField(["begin_time", "status"])->save($data, ["Id" => $id]);
            return true;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }

    //结束就诊
    public function endTreatment($doctor)
    {
        $map = [];
        $map["doctor.name|record.doctor"] = ["=", $doctor];
        $map["record.status"] = ["=", "1"];
        $id = $this
            ->where($map)
            ->where("end_time is null")
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->order('record.create_time')
            ->min('record.Id');
        $data = [];
        $data["end_time"] = date('y-m-d H:i:s');
        $data["status"] = "2";
        try {
            $this->allowField(["end_time", "status"])->save($data, ["Id" => $id]);
            return true;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }

    //错过就诊
    public function missTreatment($doctor)
    {
        $map = [];
        $map["doctor.name|record.doctor"] = ["=", $doctor];
        $map["record.status"] = ["=", "0"];
        $id = $this
            ->where($map)
            ->where("begin_time is null")
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->order('record.create_time')
            ->min('record.Id');
        $data = [];
        $data["status"] = "3";
        try {
            $this->allowField(["status"])->save($data, ["Id" => $id]);
            return true;
        } catch (\Exception $e) {
            $this->error = 'Update failure';
            return false;
        }
    }

    //获取医生平均看诊时间
    public function treatmentTime($doctor)
    {
        $doctorMap = [];
        $doctorMap["user.user_id|user.name"] = ["eq", $doctor];
        $doctorData = Db::name('user')->where($doctorMap)->field('user.*')->limit(1)->select();
        foreach ($doctorData as $k => $v) {
            $doctor_id = $v["user_id"];
            $doctor_name = $v["name"];
        }
        if (!$doctorData) {
            $this->error = 'This data is not available';
            return false;
        }
        $map = [];
        $map["doctor.name|record.doctor"] = ["=", $doctor];
        $map["record.status"] = ["=", "2"];
        $record = $this
            ->where($map)
            ->where("begin_time is not null and end_time is not null")
            ->alias('record')
            ->join('user patient', 'record.patient=patient.user_id', 'LEFT')
            ->join('user doctor', 'record.doctor=doctor.user_id', 'LEFT')
            ->order('record.create_time')
            ->field('record.*')
            ->select();
        $i = 0;
        $time_difference = 0;
        foreach ($record as $k => $v) {
            $i++;
            $begin_time = strtotime($v["begin_time"]);

            $end_time = strtotime($v["end_time"]);
            $time_difference = $time_difference + ($end_time - $begin_time);
        }
        $data["doctor"] = $doctor_id;
        $data["name"] = $doctor_name;
        $data["time"] = round($time_difference / $i / 60, 2);
        return $data;
    }
}