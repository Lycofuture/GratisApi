<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(钱包)$/',$msg)){

//$data = dic_money($qq);

dic_str();

if(dic_money($qq)<=0){

echo "不仅一毛都没有\r而且袜子都穿不起了吧\r╮( •́ω•̀ )╭";

}else

if(dic_money($qq)<300 && dic_money($qq)>0){

echo "才".dic_money($qq)."枚".dic_money_name();

echo "\n是个穷鬼(￢_￢)";

}else

if(dic_money($qq)<1000){

echo "诶鸭，都有".dic_money($qq)."枚".dic_money_name()."了鸭";

}else

if(dic_money($qq)<10000){

echo "妈耶".dic_money($qq)."枚".dic_money_name()."\r富婆抱抱我";

}else{

echo "我滴妈耶Σ(ŎдŎ|||)ﾉﾉ\n都有".dic_money($qq)."枚".dic_money_name()."了";

}}