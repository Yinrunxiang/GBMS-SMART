<?php
// +----------------------------------------------------------------------
// | Description: 图片上传
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Request;
use think\Controller;

class Upload extends Controller
{
    public function index()
    {

        header("Content-type: text/html; charset=utf-8");
        // 指定允许其他域名访问  
        header('Access-Control-Allow-Origin:*');  
        // 响应类型  
        header('Access-Control-Allow-Methods:*');  
        // 响应头设置  
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $file = request()->file('file');
        
        if (!$file) {
            return resultArray(['error' => '请上传文件']);
        }
        // if ($_SERVER['SERVER_NAME'] == "localhost") {
        //     $host_name = exec("hostname");
        //     $host_ip = gethostbyname($host_name);
        // } else {
        //     $host_ip = $_SERVER['SERVER_NAME'];
        // }
        // $addr = $host_ip . ":" . $_SERVER["SERVER_PORT"];
       
        $info = $file->validate(['ext' => 'jpg,png,gif'])->move('../../../../image/');
        if ($info) {
            return resultArray(['data' =>  DS .'image'. DS .$info->getSaveName()]);
        }
        
        return resultArray(['error' =>  $file->getError()]);
        // try {
        //     $info->move('../../../../image');
        //     if ($info) {
        //         return resultArray(['data' => 'image' . DS . $info->getSaveName()]);
        //     }
        // } catch (\Exception $e) {
        //     return resultArray(['error' => $file->getError()]);
        // }
    }

    public function delete()
    {

          header("Content-type: text/html; charset=utf-8");
        // 指定允许其他域名访问  
        header('Access-Control-Allow-Origin:*');  
        // 响应类型  
        header('Access-Control-Allow-Methods:*');  
        // 响应头设置  
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $image = request()->post('image');
        // if ($_SERVER['SERVER_NAME'] == "localhost") {
        //     $host_name = exec("hostname");
        //     $host_ip = gethostbyname($host_name);
        // } else {
        //     $host_ip = $_SERVER['SERVER_NAME'];
        // }
        // $addr = $host_ip . ":" . $_SERVER["SERVER_PORT"];

        try {
            if ($image) {
                unlink('../../../..'.$image);
            }
            return resultArray(['data' => 'Delete success']);
        } catch (\Exception $e) {
            return resultArray(['error' => 'Delete error']);
        }
    }
    // public function index()
    // {
    //     header("Content-type: text/html; charset=utf-8");
    //     // 指定允许其他域名访问  
    //     header('Access-Control-Allow-Origin:*');  
    //     // 响应类型  
    //     header('Access-Control-Allow-Methods:*');  
    //     // 响应头设置  
    //     header('Access-Control-Allow-Headers:x-requested-with,content-type');
    //     $uploaddir = '../../../image/';
    //     $name = $_FILES['file']['name'];
    //     // return $_FILES['file'];
    //     $type = strtolower(substr(strrchr($name, '.'), 1));
    //     $name = time();
    //     $name = time() . "." . $type;
    //     $uploadfile = $uploaddir . $name;
    //     $typeArr = array("jpg", "png");
    //     if (!in_array($type, $typeArr)) {
    //         echo "请上传jpg,png类型的图片！";
    //         exit;
    //     }
    //     if (move_uploaded_file($_FILES['file']['tmp_name'], iconv("UTF-8", "gb2312", $uploadfile))) {

    //         $img_url = "/image/" . $name;
    //         $url = str_replace("\\", "/", $img_url);
    //         return ($url);
    //     } else {
    //         return "Possible file upload attack!  Here's some debugging info:\n";
    //     }
    // }

    // public function delete()
    // {

    //     header('Access-Control-Allow-Origin: *');
    //     header('Access-Control-Allow-Methods: POST');
    //     header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    //     $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
    //     echo $image;
    //     if ($image) {
    //         unlink('../../../..' . $image);
    //     }
    // }
}
 