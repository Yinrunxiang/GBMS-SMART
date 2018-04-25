<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Address extends ApiCommon
{

    public function updateDataBase()
    {   
        
        if (!$data) {
            return resultArray(['error' => $addressModel->getError()]);
        }
        return resultArray(['data' => 'Add success']);
    }   
}
 