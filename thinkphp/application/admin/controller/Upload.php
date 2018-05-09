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
        $info = $file->validate(['ext' => 'jpg,png,gif'])->move('../../image');
        $imageUrl = DS . 'image' . DS . $info->getSaveName();
        $imageUrl = str_replace("\\", "/", $imageUrl);
        if ($info) {
            return resultArray(['data' => $imageUrl]);
        }

        return resultArray(['error' => $file->getError()]);
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

        try {
            if ($image) {
                unlink('../..' . $image);
            }
            return resultArray(['data' => 'Delete success']);
        } catch (\Exception $e) {
            return resultArray(['error' => 'Delete error']);
        }
    }
    public function getImage()
    {

        header("Content-type: text/html; charset=utf-8");
        // 指定允许其他域名访问  
        header('Access-Control-Allow-Origin:*');  
        // 响应类型  
        header('Access-Control-Allow-Methods:*');  
        // 响应头设置  
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $image = request()->post('image');
        
        if(!$image){
            return resultArray(['data' => "Don't search image"]);
        }
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $host_name = exec("hostname");
            $host_ip = gethostbyname($host_name);
        } else {
            $host_ip = $_SERVER['SERVER_NAME'];
        }
        $image_addr = "http://" . $host_ip . ":" . $_SERVER["SERVER_PORT"];
        
        $image = $image_addr.$image;
        $image_file = $image;
        $image_info = getimagesize($image_file);
        $base64_image_content = "data:{$image_info['mime']};base64," . 
        chunk_split(base64_encode(file_get_contents($image_file)));
        return resultArray(['data' => $base64_image_content]);
        // echo $base64_image_content;


        // try {
        //     if ($image) {
        //         unlink('../..' . $image);
        //     }
        //     return resultArray(['data' => 'Delete success']);
        // } catch (\Exception $e) {
        //     return resultArray(['error' => 'Delete error']);
        // }
    }
}
 