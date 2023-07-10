<?php

function dic_delete($path){

$data = fopen($path,"w");

fwrite($data,"");

fclose($data);

}



