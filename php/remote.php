<?php
require_once './udpServer.php';
$udpServer = new udpServer;
$udpServer->runServer('8888');