<?php
//货币读取
function Currency_Reading($groupid, $sendid)
{
    $read=dic_DataRead("货币", $groupid."_".$sendid, "text");
    return $read?$read:"0";
}
//货币写入
function Currency_Write($groupid, $sendid, $num)
{
    dic_DataWrite("货币", $groupid."_".$sendid, $num);
}
//增长货币
function Currency_Inc($groupid, $sendid, $num)
{
    $data = Currency_Reading($groupid, $sendid);
    Currency_Write($groupid, $sendid, $data + $num);
}
//货币单位
function Currency_Unit()
{
     return "金币";
}