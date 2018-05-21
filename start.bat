REM php start_io.php  php test_6001.php  php admin_6002.php
:: 设置环境变量  
  
:: 关闭终端回显  
@echo off  

:: 配置环境变量 
set "Path=%PATH%;D:\phpStudy20161103\php\php-5.4.45"

cd bin\thinkphp\application\common\udp
php Local.php

pause


