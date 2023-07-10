<?php
include "./assist/parameter.php";
include_once "./dic/扩展/货币.php";
include_once "./dic/扩展/头部.php";
if ($msgtext == "银行系统") {
    dic_headBasic();
    echo "钱包余额";
    echo "\n";
    echo "账户余额";
    echo "\n";
    echo "存款 [存款金额]";
    echo "\n";
    echo "取款 [取款金额]";
} elseif (preg_match("/^(钱包余额|钱包)\$/", $msgtext, $match)) {
    $wallet = Currency_Reading($groupid, $sendid);
    dic_head();
    echo "[操作服务]：钱包余额查询";
    echo "\n";
    echo "[操作状态]：成功";
    echo "\n";
    echo "[钱包余额]：" . $wallet . Currency_Unit();
} elseif (preg_match("/^(账户余额|账户)\$/", $msgtext, $match)) {
    $BankAccount = dic_DataRead("银行账户", $groupid . '_' . $sendid, "text");
    dic_head();
    echo "[操作服务]：账户余额查询";
    echo "\n";
    echo "[操作状态]：成功";
    echo "\n";
    echo "[账户余额]：" . $BankAccount . Currency_Unit();
} elseif (preg_match("/^(存款|存) ?([0-9]+)\$/", $msgtext, $match)) {
    $wallet = Currency_Reading($groupid, $sendid);
    $BankAccount = dic_DataRead("银行账户", $groupid . '_' . $sendid, "text");
    if ($match[2] < $wallet) {
        Currency_Inc($groupid, $sendid, -$match[2]);
        dic_DataWrite("银行账户", $groupid . '_' . $sendid, $BankAccount + $match[2]);
        dic_head();
        echo "[操作服务]：存款";
        echo "\n";
        echo "[操作状态]：成功";
        echo "\n";
        echo "[操作金额]：" . $match[2] . Currency_Unit();
        echo "\n";
        echo "[钱包余额]：" . ($wallet - $match[2]) . Currency_Unit();
        echo "\n";
        echo "[账号余额]：" . ($BankAccount + $match[2]) . Currency_Unit();
    } else {
        dic_head();
        echo "[操作服务]：存款";
        echo "\n";
        echo "[操作状态]：失败";
    }
} elseif (preg_match("/^(取款|取) ?([0-9]+)\$/", $msgtext, $match)) {
    $wallet = Currency_Reading($groupid, $sendid);
    $BankAccount = dic_DataRead("银行账户", $groupid . '_' . $sendid, "text");
    if ($match[2] < $BankAccount) {
        Currency_Inc($groupid, $sendid, $match[2]);
        dic_DataWrite("银行账户", $groupid . '_' . $sendid, $BankAccount - $match[2]);
        dic_head();
        echo "[操作服务]：取款";
        echo "\n";
        echo "[操作状态]：成功";
        echo "\n";
        echo "[操作金额]：" . $match[2] . Currency_Unit();
        echo "\n";
        echo "[钱包余额]：" . ($wallet + $match[2]) . Currency_Unit();
        echo "\n";
        echo "[账号余额]：" . ($BankAccount - $match[2]) . Currency_Unit();
    } else {
        dic_head();
        echo "[操作服务]：取款";
        echo "\n";
        echo "[操作状态]：失败";
    }
}