<?php
namespace app\common\udp;
class UdpProtocol
{
    
    //转16进制方法
    function toHex($num) {
        $num = dechex($num);
        if(strlen($num) < 2){
            $num = '0'.$num;
        }
        return $num ;
    }
    
    function UdpProtocol($operatorCodefst = '',$operatorCodesec = '',$targetSubnetID = '',$targetDeviceID = '',$additionalContentData = [],$macAddress = [])
    {
        //根据是否传入macaddress判断远程还是本地
        if(count($macAddress) == 0){
            $isRemoteControl = 0;
        }else{
            $isRemoteControl = 1;
        }
        // echo ',';
        // var_dump($macAddress);
        // echo $isRemoteControl;
        /// 本地wifi
        $Local_Server_IP = "255.255.255.255";
    /// 本地wifi
        $Remote_Server_IP = "162.144.66.131";
        // 源终端子网ID
        $originalSubnetID = 'BB';

        // 源终端设备ID
        $originalDeviceID = 'BB';
        
        // 本地wifiWWW
        $ipArray = $isRemoteControl == 1? $Remote_Server_IP : $Local_Server_IP;
        $ipArray = explode('.',$Local_Server_IP); 
        // 1.1 获得目标ip的大小 (远程 && 本地) 【固定】
        
         
           
        // 1.2 固定协议头 【固定】
        // 发送协议的数据包头  数据包的下标 4 ~ 13 是协议头，固定 udpPckageHead数组
         $udpPckageHeadArray = array('53', '4D', '41', '52', '54', '43', '4C', '4F', '55', '44');
         // 1.3 UDP Package Head + SoureceIP  的长度【固定】
        $protocolPackageAndSourceIPLength = count($ipArray) + count($udpPckageHeadArray);
        // 1.4 可变参数的长度
        $additionalContentLength = count($additionalContentData);
        // 1.5 mac地址的长度
        $maclen = count($macAddress);
        $remoteDataLength = $isRemoteControl == 1 ? (1 + $maclen) : 0;// 1 表示设备标示符
        // 1.5 Protocol Base Structure 协议数据包的长度
        // 远程:(固定部分11 + additionalContentLength + (1个标示位 + 8个mac地地址) +  2个 0x00, 0x00)
        // 本地:(固定部分11 + additionalContentLength + 2个 CRC)
        $protocolBaseStructureLength = 11 + $additionalContentLength+$remoteDataLength + 2;
        // 1.6 数据包的总大小
        $sendUDPBufLength = $protocolPackageAndSourceIPLength + $protocolBaseStructureLength;

     


        // 声明发送数据包
    $maraySendUDPBuf =array();
    
    // 设置开始索引
    $index = 0;
    
    // 2.1 设置目标ip
    
    while ($index < count($ipArray)) {
        // 赋值ip地址
        $value = $this->toHex($ipArray[$index]);
        $maraySendUDPBuf[$index++] = $value;
        
    }
    
    // 2.2  协议头 固定 udpPckageHead数组
    for ($i = 0; $i  < count($udpPckageHeadArray); $i++) {
        $maraySendUDPBuf[$index++] = $udpPckageHeadArray[$i];
    }
    
    // 2.3 Protocol Base Structure 部分
    
    // 2.3.1  开始代码: 0XAAAA固定
    $maraySendUDPBuf[$index++] = 'AA';
    $maraySendUDPBuf[$index++] = 'AA';
    
    // 2.3.2 数据包的长度  -- 计算(SN2 ~ 10)
    $maraySendUDPBuf[$index++] = $this->toHex($protocolBaseStructureLength - 2); // -2 是不含 SN1的内容
    
    // 2.3.3 手机的子网ID
    $maraySendUDPBuf[$index++] = $originalSubnetID;
    // 2.3.4 手机的设备ID
    $maraySendUDPBuf[$index++] = $originalDeviceID;
    
    // 2.3.5 设备类型
    $maraySendUDPBuf[$index++] = 'CC';
    $maraySendUDPBuf[$index++] = 'CC';
    
    // 2.3.6 操作码
    // 高8位
    $maraySendUDPBuf[$index++] = $operatorCodefst;
    // 低8位
    $maraySendUDPBuf[$index++] = $operatorCodesec;
    
    // 2.3.7 目标设备的子网ID与设备ID
    
    // 目标设备的子网ID
    $maraySendUDPBuf[$index++] = $targetSubnetID;
    // 目标设备的设备ID
    $maraySendUDPBuf[$index++] = $targetDeviceID;
    
    // 2.3.8 可变参数
    for ($i = 0; $i < $additionalContentLength; $i++) {
        $maraySendUDPBuf[$index++] = $additionalContentData[$i];
    }
    // 2.3.9 不同部的部分
    if ($isRemoteControl == 1) {  // 远程部分
        
        
        /// 远程设备类型标示
        $iOS_Flag = '02';
        //  设置状态标示位
        $maraySendUDPBuf[$index++] = $iOS_Flag;
        // 2. 添加mac地址
        for ($i = 0; $i < count($macAddress); $i++) {
            $maraySendUDPBuf[$index++] = $macAddress[$i];
        }
        
        // 3.添加最后两个0
        $maraySendUDPBuf[$index++] = '00';
        $maraySendUDPBuf[$index++] = '00';
        
    } else {  // 本地部分
        
        // 校验码  -- 由CRC算法来生成
        // 第一个参数：整个数据包的中Protocol Base Structure部分的LEN of Data Package的地址
        // 第二个参数：从【Protocol Base Structure】数据的总大小 - CRC的两个字节(cRCLength) - Start code的大小(2个字节)
        // pack(($maraySendUDPBuf[$protocolPackageAndSourceIPLength + 2]), $protocolBaseStructureLength - 2 - 2);
        // $command = './crc '.$maraySendUDPBuf[$protocolPackageAndSourceIPLength + 2].' '.$protocolBaseStructureLength - 2 - 2;
        $crc_table = array(
        0x0,  0x1021,  0x2042,  0x3063,  0x4084,  0x50a5,  0x60c6,  0x70e7,
        0x8108,  0x9129,  0xa14a,  0xb16b,  0xc18c,  0xd1ad,  0xe1ce,  0xf1ef,
        0x1231,  0x210,  0x3273,  0x2252,  0x52b5,  0x4294,  0x72f7,  0x62d6,
        0x9339,  0x8318,  0xb37b,  0xa35a,  0xd3bd,  0xc39c,  0xf3ff,  0xe3de,
        0x2462,  0x3443,  0x420,  0x1401,  0x64e6,  0x74c7,  0x44a4,  0x5485,
        0xa56a,  0xb54b,  0x8528,  0x9509,  0xe5ee,  0xf5cf,  0xc5ac,  0xd58d,
        0x3653,  0x2672,  0x1611,  0x630,  0x76d7,  0x66f6,  0x5695,  0x46b4,
        0xb75b,  0xa77a,  0x9719,  0x8738,  0xf7df,  0xe7fe,  0xd79d,  0xc7bc,
        0x48c4,  0x58e5,  0x6886,  0x78a7,  0x840,  0x1861,  0x2802,  0x3823,
        0xc9cc,  0xd9ed,  0xe98e,  0xf9af,  0x8948,  0x9969,  0xa90a,  0xb92b,
        0x5af5,  0x4ad4,  0x7ab7,  0x6a96,  0x1a71,  0xa50,  0x3a33,  0x2a12,
        0xdbfd,  0xcbdc,  0xfbbf,  0xeb9e,  0x9b79,  0x8b58,  0xbb3b,  0xab1a,
        0x6ca6,  0x7c87,  0x4ce4,  0x5cc5,  0x2c22,  0x3c03,  0xc60,  0x1c41,
        0xedae,  0xfd8f,  0xcdec,  0xddcd,  0xad2a,  0xbd0b,  0x8d68,  0x9d49,
        0x7e97,  0x6eb6,  0x5ed5,  0x4ef4,  0x3e13,  0x2e32,  0x1e51,  0xe70,
        0xff9f,  0xefbe,  0xdfdd,  0xcffc,  0xbf1b,  0xaf3a,  0x9f59,  0x8f78,
        0x9188,  0x81a9,  0xb1ca,  0xa1eb,  0xd10c,  0xc12d,  0xf14e,  0xe16f,
        0x1080,  0xa1,  0x30c2,  0x20e3,  0x5004,  0x4025,  0x7046,  0x6067,
        0x83b9,  0x9398,  0xa3fb,  0xb3da,  0xc33d,  0xd31c,  0xe37f,  0xf35e,
        0x2b1,  0x1290,  0x22f3,  0x32d2,  0x4235,  0x5214,  0x6277,  0x7256,
        0xb5ea,  0xa5cb,  0x95a8,  0x8589,  0xf56e,  0xe54f,  0xd52c,  0xc50d,
        0x34e2,  0x24c3,  0x14a0,  0x481,  0x7466,  0x6447,  0x5424,  0x4405,
        0xa7db,  0xb7fa,  0x8799,  0x97b8,  0xe75f,  0xf77e,  0xc71d,  0xd73c,
        0x26d3,  0x36f2,  0x691,  0x16b0,  0x6657,  0x7676,  0x4615,  0x5634,
        0xd94c,  0xc96d,  0xf90e,  0xe92f,  0x99c8,  0x89e9,  0xb98a,  0xa9ab,
        0x5844,  0x4865,  0x7806,  0x6827,  0x18c0,  0x8e1,  0x3882,  0x28a3,
        0xcb7d,  0xdb5c,  0xeb3f,  0xfb1e,  0x8bf9,  0x9bd8,  0xabbb,  0xbb9a,
        0x4a75,  0x5a54,  0x6a37,  0x7a16,  0xaf1,  0x1ad0,  0x2ab3,  0x3a92,
        0xfd2e,  0xed0f,  0xdd6c,  0xcd4d,  0xbdaa,  0xad8b,  0x9de8,  0x8dc9,
        0x7c26,  0x6c07,  0x5c64,  0x4c45,  0x3ca2,  0x2c83,  0x1ce0,  0xcc1,
        0xef1f,  0xff3e,  0xcf5d,  0xdf7c,  0xaf9b,  0xbfba,  0x8fd9,  0x9ff8,
        0x6e17,  0x7e36,  0x4e55,  0x5e74,  0x2e93,  0x3eb2,  0xed1,  0x1ef0);
        // $test = chr(0xC6).chr(0xCE).chr(0xA2).chr(0x03); // CRC16-CCITT = 0xE2B4
        $crc = 0x0000;
        // pack('H*', '010301180001');
        // echo ($protocolPackageAndSourceIPLength + 2).',';
        // echo ($protocolBaseStructureLength - 4).',';
        for ($i = 0; $i < $protocolBaseStructureLength - 4; $i++){
            $crc =  $crc_table[(($crc>>8) ^ hexdec($maraySendUDPBuf[$protocolPackageAndSourceIPLength + 2+$i]))] ^ (($crc<<8) & 0x00FFFF);
            // echo $crc.',';
        }
        $maraySendUDPBuf[$index++] = $this->toHex($crc >> 8);
        $crc = $this->toHex($crc);
        $crclen = strlen($crc);
        // echo $crc;
        // echo $crclen;
        for($i = $crclen;$i < 4;$i++){
                $crc = '0'.$crc;
        }
        // echo $crc;
        $maraySendUDPBuf[$index++] = substr($crc,2,2);

    }
    // var_dump($maraySendUDPBuf);

    $msg = join("",$maraySendUDPBuf);
    $strLen = strlen($msg); 
    // echo $msg;
    $msg = pack("H{$strLen}", $msg);  
    return $msg;
    }
}