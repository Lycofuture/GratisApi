<?php
//传入读写函数
function Admin_Permission($robot,$sendid)
{
    $data = dic_DataRead("管理员",$robot,"text");
    if ($sendid == ROOT_QQ) {
        return 200;
    }elseif ($data == $sendid) {
        return 100;
    }else{
      return 0;
  }
}