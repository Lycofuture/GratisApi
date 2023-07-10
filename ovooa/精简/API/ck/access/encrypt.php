<?php

function hex_encode($str){
$hex="";
for($i=0;$i<strlen($str);$i++)
$hex.='\\u4E'.dechex(ord($str[$i]));
$hex=$hex;
return $hex;
}



function hex_decode($hex){
$str="";
for($i=0;$i<strlen($hex)-1;$i+=2)
$str.=chr(hexdec($hex[$i].$hex[$i+1]));
return $str;
}


function decodeUnicode($str)

{

    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',

        @create_function(

            '$matches',

            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'

        ),

        $str);

}

function encodeUnicodes($str){
    $decode = json_decode('{"text":"'.$str.'"}',true);
       if(!$decode){
        return $str;
        }else{
          $encode = json_encode($decode);
           preg_match_all('/text":"(.*?)"/',$encode,$encode);
            $encode = str_replace('\\u4e','',$encode[1][0]);
             $encode = str_replace('\\u4E','',$encode);
                return $encode;
               }
             }
           
            
     
function jiami($string){

  $str = hex_encode($string);
  
    $str = decodeUnicode($str);
    
       return $str;

 }


function jiemi($string){
 $str = encodeUnicodes($string);
   $str = hex_decode($str);
     return $str;
     }

