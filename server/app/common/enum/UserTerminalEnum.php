<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------


namespace app\common\enum;


/**
 * 管理后台登录终端
 * Class terminalEnum
 * @package app\common\enum
 */
class UserTerminalEnum
{
    const WECHAT_MMP = 1; //微信小程序
    const WECHAT_OA  = 2; //微信公众号
    const H5         = 3;//手机H5登录
    const IOS        = 4;//苹果app
    const ANDROID    = 5;//安卓app
    const WEB         = 6;//网页
    const PC         = 7;//PC

    const ALL_TERMINAL = [
        self::WECHAT_MMP,
        self::WECHAT_OA,
        self::H5,
        self::IOS,
        self::ANDROID
    ];


    /**
     * @notes 获取终端
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/7/30 18:09
     */
    public static function getTermInalDesc($from = true)
    {
        $desc = [
            self::WECHAT_MMP    => '微信小程序',
            self::WECHAT_OA     => '微信公众号',
            self::H5            => '手机H5',
            self::IOS           => '苹果APP',
            self::ANDROID       => '安卓APP',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 通过用户终端获取支付场景
     * @param $scene
     * @return int|mixed
     * @author cjhao
     * @date 2022/6/10 18:50
     */
    public static function trueerminalEnumByScene($scene)
    {
        $desc = [
            self::WECHAT_MMP    => 1,
            self::WECHAT_OA     => 2,
            self::H5            => 3,
            self::IOS           => 4,
            self::ANDROID       => 5,
        ];
        return $desc[$scene] ?? 0;

    }
}
