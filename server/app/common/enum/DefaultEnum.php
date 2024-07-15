<?php

namespace app\common\enum;

/**
 * 默认值枚举
 * Class DefaultEnum
 * @package app\common\enum
 */
class DefaultEnum
{
    // 排序：默认255
    const SORT = 255;
    const GAMElIST = [
        "zhadan" => "炸弹",
        "biaofen" => "标分",
        "shisanshui" => "十三水",
        "majiang" => "麻将",
        "paodekuai" => "跑得快",
        "xmmajiang" => "厦门麻将",
    ];

    const GiftTitles = [
        "CBgAAoXb6-hwkVtoYKCeyIyzcG2f3a8oin_KuTS51RKHAf_5YlRoF9hXLVRsmS3_4ejD-sWJ8KCOi9TA" => "每日签到礼包",
        "CBgAAoXb6-hwkVtoYKCeyIyzcG2f3a8ojiULr7kaknJqcgdvC68Ks8wRvXKh6dxlwF-Wo8VE5-PxwMUo" => "每日登录礼包",
        "CBgAAoXb6-hwkVtoYKCeyIyzcG2f3a8p4tWIPUhk-qLnSbRkYHvk2OwJAqPlrTDirSLiXiPt6EDbWnOM" => "周六福利礼包"
    ];

    const HitLists = [
        true => "中奖",
        false => "未中奖"
    ];

    const ReceiveLists = [
        true => "已领取",
        false => "未领取"
    ];

    const openDoubleLists = [
        true => "开启",
        false => "关闭"
    ];

    const DIAMONDLISTS = [
        1 => "注册赠送",
        2 => "绑定手机赠送",
        3 => "实名赠送",
        4 => "分享赠送",
        5 => "微信充值增加",
        6 => "后台充值增加",
        7 => "礼物赠送",
        8 => "公共礼物赠送",
        9 => "房费AA付扣除",
        10 => "房费房主付扣除",
        11 => "房费大赢家扣除",
        14 => "抽奖奖励",
        18 => "战队添加游戏扣除",
        19 => "洗牌扣除",
        21 => "房费战队主付扣除",
        22 => "钻石兑换金豆",
        23 => "祈福扣除",
        24 => "求签扣除",
        28 => "机器人设置对局金豆",
        29 => "钻石兑换金豆",
        30 => "每日领取商城免费金豆",
        31 => "购买复活礼包",
        32 => "对局扣除",
        33 => "对局获得",
        34 => "机器人自动加金豆",
        35 => "扣除房费",
        36 => "领取开运红包",
        37 => "领取7日登陆",
        38 => "转盘抽奖获得",
        39 => "代金券兑换钻石",
        40 => "后台充值金豆",
        41 => "领取新手签到",
        42 => "领取新手指引",
        43 => "购买新手礼包",
        44 => "兑换头像框",
        45 => "兑换靓号",
        46 => "领取充值派对奖励",
        47 => "领取任务奖励",
        48 => "结算免输或双倍",
        49 => "领取VIP奖励",
        50 => "起风扣除",
        51 => "起风收入",
    ];

    const HELPCLOSE = 0;

    const HELPTYPELISTS = [
      1 => "充值宝箱",
      2 => "胜率宝箱",
      3 => "烧鸡宝箱"
    ];

    const HELPSTATELISTS = [
        1 => "可补助",
        0 => "不可补助"
    ];

    const GAMECATEGORYlIST = [
        "ruby" => "金豆场"
    ];

    const RECHARGEORDERSTATUS = [
      "pending" => "待支付",
        "finish" => "已支付"
    ];
    const WITHDRAWSTATE = [
        "finished" => "成功",
        "403" => "失败",
    ];

    const CLUBACTIONTYPE = [
      1 => "增加",
      0 => "减少"
    ];

    const CARDTYPE = [
        0 => "王牌",
        1 => "黑桃",
        2 => "红心",
        3 => "梅花",
        4 => "方块",
    ];
    const POINTTYPE = [
        11 => "J",
        12 => "Q",
        13 => "K",
        14 => "A",
        15 => "2",
        16 => "小王",
        17 => "大王",
    ];

    const CARDlIST = [
        1 => "4星",
        2 => "5星",
        3 => "6星",
        4 => "7星",
        5 => "8星",
        6 => "9星",
        7 => "10星",
        8 => "11星",
        9 => "12星",
    ];

    const HELPLIST = [
        0 => "失败",
        1 => "成功",
    ];

    const REDPOCKET = 3;
    const REDPOCKETRANK = 100;
    const USERRECHARGESOURCE = [
      "ADMINRECHARGE" => "后台充值",
      "WECHATRECHARGE" => "微信充值",
        "AUTORECHARGE" => "自助充值"
    ];
    const USERRECHARGECURRENCY = [
        "gem" => "充值钻石",
        "ruby" => "充值金豆",
        "cash" => "现金充值"
    ];
    const ISNOTRESULT = "/";

    const ROOMSTATE = [
        "NORMALLAST" => "normal_last",
        "NORMAL" => "normal",
        "ZEROJU" => "zero_ju",
        "DISSOLVE" => "dissolve",
        "DISSOLVELAST" => "dissolve_last",
    ];
    const GOODSTATUS = [
        0 => "未付款",
        1 => "已支付"
    ];

    const ROOMSTATETEXT = [
        "normal_last" => "正常结束",
        "normal" => "正在对局",
        "zero_ju" => "未开局",
        "dissolve" => "断线",
        "dissolve_last" => "尾局解散",
    ];

    const AGENCYRECHARGELEVEL = [
        0 => "无级别",
        1 => "1级代理",
        2 => "2级代理",
        3 => "3级代理",
        4 => "金牌代理",
    ];

    const AGENCYNEXTRECHARGEAMOUNT = [
        0 => 0,
        1 => 1000,
        2 => 10000,
        3 => 50000,
        4 => 100000,
    ];

    const AGENCYNEXTRECHARGEINFO = [
        1 => ["one" => 0, "two" => 0],
        2 => ["one" => 66, "two" => 266],
        3 => ["one" => 166, "two" => 566],
        4 => ["one" => 300, "two" => 1000],
    ];
    const IOSPRICE = [
        "tianle.majiang.pay_1" => 2500,
        "tianle.majiang.pay_2" => 6000,
        "tianle.majiang.pay_3" => 11800,
        "tianle.majiang.pay_4" => 23800,
        "tianle.majiang.pay_5" => 58800,
        "tianle.majiang.pay_6" => 119800,
        "tianle.majiang.pay_7" => 100000,
        "tianle.majiang.pay_8" => 300000,
    ];
    const GOODSTYPE = [
        0 => "钻石"
    ];
    const REDPOCKETTYPE = [
        "lucky" => "红包"
    ];

    const CURRENCYlIST = [
      1 => "gem",
      2 => "ruby",
      3 => "redpocket"
    ];

    const ROOMTYPE = [
        1 => "公共房",
        2 => "金币房",
        3 => "个人房",
        4 => "个人房",
    ];

    const EMAILTYPE = [
        "message" => "普通邮件",
        "gift" => "礼物邮件",
        "notice" => "普通邮件",
        "noticeGift" => "礼物邮件",
    ];

    const EMAILSTATE = [
        1 => "未读",
        2 => "已读"
    ];

    const GIFTSTATE = [
        1 => "未领取",
        2 => "已领取"
    ];

    const JOKERTYPE = [
        1 => "带王局",
        0 => "不带王局",
        2 => "红中赖子",
        3 => "无赖子",
    ];

    const GMPRICES = [
      1428 => 1000,
      4782 => 3000
    ];

}
