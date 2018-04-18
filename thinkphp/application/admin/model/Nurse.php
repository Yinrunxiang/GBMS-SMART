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

class Nurse extends Common
{

    protected $name = 'nurse';
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
    ];
    /**
     * 获取用户所属所有用户组
     * @param  array $param [description]
     */
//    public function groups()
//    {
//        return $this->belongsToMany('group', '__ADMIN_ACCESS__', 'group_id', 'user_id');
//    }

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
        //根据keywords筛选用户信息
        if ($keywords) {
            $map['user.name|user.tel'] = ['like', '%' . $keywords . '%'];
        }

        // 默认除去超级管理员
//		$map['nurse.nurse_id'] = array('neq', 1);
//        $map[] = array();
        $dataCount = $this
            ->alias('nurse')
            ->join('user user', 'user.user_id=nurse.user_id', 'LEFT')
            ->where($map)
            ->count('nurse.user_id');

        $list = $this
            ->where($map)
            ->alias('nurse')
            ->join('user user', 'user.user_id=nurse.user_id', 'LEFT')
            ->join('department department', 'nurse.department=department.id', 'LEFT');

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list
            ->field('nurse.*,user.name as name,user.tel as tel,department.name as department_name')
            ->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }

    /**
     * [getDataById 根据主键获取详情]
     * @linchuangbin
     * @DateTime  2017-02-10T21:16:34+0800
     * @param     string $id [主键]
     * @return    [array]
     */
    public function getDataById($id = '')
    {
        $data = $this->get($id);
        if (!$data) {
            $this->error = 'This data is not available';
            return false;
        }
        return $data;
    }

    /**
     * 创建用户
     * @param  array $param [description]
     */
//    public function createData($param)
//    {
//        if (empty($param['department_name'])) {
//            $this->error = 'Please check at least one department';
//            return false;
//        }
//
//        // 验证
//        $validate = validate($this->name);
//        if (!$validate->check($param)) {
//            $this->error = $validate->getError();
//            return false;
//        }
//
//        $this->startTrans();
//        try {
//            $param['nurse_password'] = user_md5($param['nurse_password']);
//            $this->data($param)->allowField(true)->save();
//
////			foreach ($param['nurse'] as $k => $v) {
////				$userGroup['user_id'] = $this->id;
////				$userGroup['group_id'] = $v;
////				$userGroups[] = $userGroup;
////			}
////			Db::name('admin_access')->insertAll($userGroups);
//
//            $this->commit();
//            return true;
//        } catch (\Exception $e) {
//            $this->rollback();
//            $this->error = 'Add failure';
//            return false;
//        }
//    }

    /**
     * 通过id修改用户
     * @param  array $param [description]
     */
    public function updateDataById($param, $user_id)
    {
        // 不能操作超级管理员
        if ($user_id == 1) {
            $this->error = 'Illegal operation';
            return false;
        }
        $checkData = $this->get($user_id);
        if (!$checkData) {
            $this->error = 'This data is not available';
            return false;
        }
        if (empty($param['department'])) {
            $this->error = 'Please check the department';
            return false;
        }
        $this->startTrans();

        try {
//			Db::name('admin_access')->where('user_id', $id)->delete();
//			foreach ($param['groups'] as $k => $v) {
//				$userGroup['user_id'] = $id;
//				$userGroup['group_id'] = $v;
//				$userGroups[] = $userGroup;
//			}
//			Db::name('admin_access')->insertAll($userGroups);
//            Db::name('nurse')->insert($param, ['$user_id' => $user_id]);
//            $this->allowField(['department','sex','age','title'])->save($param, ['$user_id' => $user_id]);
            $this->allowField(true)->save($param, ['user_id' => $user_id]);
            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Update failure';
            return false;
        }
    }

}