<?php
function dic_headBasic()
{
    echo "±img=http://q2.qlogo.cn/headimg_dl?spec=0&dst_uin=%QQ%±";
    echo "\n";
}
function dic_head()
{
    dic_headBasic();
    echo "[用户昵称]：%昵称%";
    echo "\n";
    echo "[用户Q Q]： %QQ%";
    echo "\n";
}
function dic_foot($robot)
{
    echo dic_DataRead("尾巴",$robot,"text");
}