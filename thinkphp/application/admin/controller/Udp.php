<?php
// +----------------------------------------------------------------------
// | Description: UDP发送
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\udp\UdpProtocol;
use app\common\udp\UdpSocket;

class Udp extends ApiCommon
{

    public function sendUdp()
    {
        $param = $this->param;
        $operatorCodefst = $param['operatorCodefst'];
        $operatorCodesec = $param['operatorCodesec'];
        $targetSubnetID = $param['targetSubnetID'];
        $targetDeviceID = $param['targetDeviceID'];
        $operatorCodefst = $param['operatorCodefst'];
        $additionalContentData = $param['additionalContentData'];
        $udp_type = $param['udp_type'];
        if ($udp_type == "1") {
            $dest_address = $_REQUEST["dest_address"] ? $_REQUEST["dest_address"] : '255.255.255.255';
            $dest_port = 8888;
            $macAddress = $_REQUEST["macAddress"] ? $_REQUEST["macAddress"] : [];
        } else {
            $dest_address = '255.255.255.255';
            $dest_port = 6000;
            $macAddress = [];
        }
        //调用UDP协议类，生成UDP协议
        $udpProtocol = new UdpProtocol();
        $msg = $udpProtocol->UdpProtocol($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress);
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $host_name = exec("hostname");
            $host_ip = gethostbyname($host_name);
        } else {
            $host_ip = $_SERVER['SERVER_NAME'];
        }
        new UdpSocket("0.0.0.0", 0, $dest_address, $dest_port, $msg);
        usleep(300000);
        return resultArray(['data' => $msg]);
    }
}
 