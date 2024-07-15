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
 * 支付
 * Class PayrEnum
 * @package app\common\enum
 */
class PayEnum
{


    //支付类型
    const BALANCE_PAY   = 1; //余额支付
    const WECHAT_PAY    = 2; //微信支付
    const ALI_PAY       = 3; //支付宝支付
    const BYTE_PAY       = 4; //字节支付


    //支付状态
    const UNPAID = 0; //未支付
    const ISPAID = 1; //已支付


    //退款状态
    const NOT_REFUND = 0;//未退款
    const REFUND_ING = 1;//退款中
    const REFUND_SUCCESS = 1;//退款成功
    const REFUND_ERROR = 1;//退款失败

    //支付场景
    const SCENE_H5 = 1;//H5
    const SCENE_OA = 2;//微信公众号
    const SCENE_MNP = 3;//微信小程序
    const SCENE_APP = 4;//APP
    const SCENE_PC = 5;//PC商城
    const SCENE_BYTE = 7;//字节小程序

    /**
     * @notes 获取支付类型
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2021/7/27 11:46 上午
     */
    public static function getPayDesc($value = true)
    {
        $data = [
            self::BALANCE_PAY => '余额支付',
            self::WECHAT_PAY => '微信支付',
            self::ALI_PAY => '支付宝支付',
            self::BYTE_PAY => '字节支付',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 支付状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2021/8/4 2:37 下午
     */
    public static function getPayStatusDesc($value = true)
    {
        $data = [
            self::UNPAID => '未支付',
            self::ISPAID => '已支付',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }


    /**
     * @notes 支付场景
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2021/9/13 2:44 下午
     */
    public static function getPaySceneDesc($value = true)
    {
        $data = [
            self::SCENE_H5 => 'H5',
            self::SCENE_OA => '微信公众号',
            self::SCENE_MNP => '微信小程序',
            self::SCENE_APP => 'APP',
            self::SCENE_PC => 'PC商城',
            self::SCENE_BYTE => '字节小程序',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }


}