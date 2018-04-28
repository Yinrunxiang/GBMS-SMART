<?php
// +----------------------------------------------------------------------
// | Description: 更新数据库
// +----------------------------------------------------------------------
// | Author: Jensen
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
 