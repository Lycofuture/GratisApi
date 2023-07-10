<?php

/**
 * 这是一个示例插件
 *
 * 需要注意的几个默认规则：
 * 1.本插件类的文件名必须是Actions.php
 * 2.插件类的名称必须是{插件名_Actions}
 */
class Guanggao_Actions
{
    public function __construct(&$pluginManager)
    {
        $pluginManager->register('Plugin', $this, 'EventFun');
    }
    public function EventFun($MQ_MSG)
    {
        foreach ($MQ_MSG as $key => $value) {
            $$key = $value;
        }
        /*
        API列表：https://www.myqqx.cn/5.扩展开发/1.开发相关/2.API列表.html
        常量列表：https://www.myqqx.cn/5.扩展开发/1.开发相关/3.常量列表.html
        消息回调：https://www.myqqx.cn/6.HTTPAPI/5.消息回调.html

        变量名称 说明
        MQ_robot 用于判定哪个QQ接收到该消息
        MQ_type 接收到消息类型，该类型可在[常量列表]中查询具体定义
        MQ_type_sub 此参数在不同情况下，有不同的定义
        MQ_fromID 此消息的来源，如：群号、讨论组ID、临时会话QQ、好友QQ等
        MQ_fromQQ 主动发送这条消息的QQ，踢人时为踢人管理员QQ
        MQ_passiveQQ 被动触发的QQ，如某人被踢出群，则此参数为被踢出人QQ
        MQ_msg （此参数将被URL UTF8编码，您收到后需要解码处理）此参数有多重含义，常见为：对方发送的消息内容，但当消息类型为 某人申请入群，则为入群申请理由,当消息类型为收到财付通转账、收到群聊红包、收到私聊红包时为原始json，详情见[特殊消息]章节
        MQ_msgSeq 撤回别人或者机器人自己发的消息时需要用到
        MQ_msgID 撤回别人或者机器人自己发的消息时需要用到
        MQ_msgData UDP收到的原始信息，特殊情况下会返回JSON结构（入群事件时，这里为该事件data）
        MQ_timestamp 对方发送该消息的时间戳，引用回复消息时需要用到
         */

        if ($MQ_robot == '2830877581' and $MQ_type == '2' and ($MQ_fromID == '820323177' or $MQ_fromID == '820323177')) {
            $ban_rule = "/红包群|招.*?(兼职|学生|赚钱|打字|工作)|欢迎加入.*?群号码.*?\d{5,10}|操你妈|加群.*?\d{5,10}|分享.*?红包|招收|兼职|家教|代刷|慕课|网课|傻逼|工资|问卷|代考|扫码领红包|红包|支付宝发红包|日赚|微信解封|解封微信|辅助解封|下一个|代班会|拼多多|群号|移动校园卡|下载最新版|分享视频|免费分享|疫情尚未结束|咱们群|欢迎大家|需要的进来看看吧|代晚自习|腾讯安全中心|微信辅助|加入群聊|淘宝内部优惠券|复制本段|生活超市|扫码进群|http|pdd|redpack|！】|【互|\"app\"/";
            preg_match($ban_rule, $MQ_msg, $ban_result);
            if ($ban_result) {
                //$MQ_Api->Api_QQBOT('Api_WithdrawMsg', [$MQ_robot, $MQ_fromID, $MQ_msgSeq, $MQ_msgID]);
                //$MQ_Api->Api_QQBOT('Api_Shutup', [$MQ_robot, $MQ_fromID, $MQ_fromQQ, 60 * 60 * 24 * 1]);
                $MQ_Api->Api_QQBOT('Api_SendMsg', [$MQ_robot, '2', '820323177', '820323177', "违禁词：{$MQ_msg} \nQQ:$MQ_fromQQ \n群：$MQ_fromID"]);
                return;
            }
            $ch_rule = "/大家好，我是|红包群|招.*?(兼职|学生|赚钱|打字|工作)|欢迎加入.*?群号码.*?\d{5,10}|操你妈|加群.*?\d{5,10}|分享.*?红包|招收|兼职|家教|代刷|慕课|网课|傻逼|工资|问卷|代考|扫码领红包|红包|支付宝发红包|日赚|微信解封|下一个|代班会|拼多多|移动校园卡|大家好|美团会员|下载最新版|代写|加好友备用|分享视频|卖楼|免费分享|猫猫|助力|互助|需要的进来看看吧|代课|腾讯安全中心|人气|有互吗|互吗|微信辅助|找人代/";
            preg_match($ch_rule, $MQ_msg, $ch_result);
            if ($ch_result) {
                $MQ_Api->Api_QQBOT('Api_WithdrawMsg', [$MQ_robot, $MQ_fromID, $MQ_msgSeq, $MQ_msgID]);
                $MQ_Api->Api_QQBOT('Api_SendMsg', [$MQ_robot, '2', '820323177', '820323177', "撤回：{$MQ_msg} QQ:$MQ_fromQQ 群：$MQ_fromID"]);
                return;
            }
            $baiduCheck = $MQ_Api->checkContent($MQ_msg);
            if ($baiduCheck == false) {
                $MQ_Api->Api_QQBOT('Api_WithdrawMsg', [$MQ_robot, $MQ_fromID, $MQ_msgSeq, $MQ_msgID]);
                $MQ_Api->Api_QQBOT('Api_SendMsg', [$MQ_robot, '1', '', '123456', "百度撤回：{$MQ_msg} QQ:$MQ_fromQQ 群：$MQ_fromID"]);
                return;
            }
            if ($MQ_msg == '抽奖') {
                $MQ_Api->Api_QQBOT('Api_WithdrawMsg', [$MQ_robot, $MQ_fromID, $MQ_msgSeq, $MQ_msgID]);
                $MQ_Api->Api_QQBOT('Api_Shutup', [$MQ_robot, $MQ_fromID, $MQ_fromQQ, random_int(1, 6)]);
            }
            $MQ_Api->ret(1);
        }
        //复读机  
        if ($MQ_robot == '123456' and $MQ_type == '1') {
            $MQ_Api->Api_QQBOT('Api_SendMsg', [$MQ_robot, '1', '', $MQ_fromQQ, $MQ_msg]);
        }
    }
}
