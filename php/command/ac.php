<?php
class Ac {  
    public static function getBytes($str) {  
        
              $len = strlen($str);  
              $bytes = array();  
                 for($i=0;$i<$len;$i++) {  
                     if(ord($str[$i]) >= 128){  
                         $byte = ord($str[$i]) - 256;  
                     }else{  
                         $byte = ord($str[$i]);  
                     }  
                  $bytes[] =  $byte ;  
              }  
              return $bytes;  
          }  
}


?>