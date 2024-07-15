<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        // 计算用户胜率
        'game_ju_count' => 'app\common\command\GameJuCount',
        // 计算4王局
        'game_four_joker' => 'app\common\command\GameFourJoker',
        // 每日赠送3次抽奖机会
        'player_lottery_count' => 'app\common\command\updatePlayerLotteryCount',
        // 提现系统预警
        'system_warn' => 'app\common\command\SystemWarn',
        //获取微信access_token
        'wechat_access_token' => 'app\common\command\WechatAccessToken',
        //客服推送充值卡片
        'wechat_send_message' => 'app\common\command\AutoSendWechatMessage',
        // 修改超级管理员密码
        'password' => 'app\common\command\Password',
        // 定时任务
        'crontab' => 'app\common\command\Crontab',
        //上报排行榜
        'player_set_storage' => 'app\common\command\updateUserStorage'
    ],
];
