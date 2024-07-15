<?php

namespace app\common\service;

use app\common\logic\BaseLogic;
use EasyWeChat\{
    Factory,
    Kernel\Exceptions\Exception,

};


/**
 * 微信功能类
 * Class WeChatService
 * @package app\common\service
 */
class WeChatService extends BaseLogic
{
    /**
     * @notes 公众号-根据code获取微信信息
     * @param array $params
     * @return array
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Overtrue\Socialite\Exceptions\AuthorizeFailedException
     * @author cjhao
     * @date 2021/8/16 14:55
     */
    public static function getOaResByCode(array $params)
    {
        $config = WeChatConfigService::getOaConfig();
        $app = Factory::officialAccount($config);

        $response = $app->oauth
            ->scopes(['snsapi_userinfo'])
            ->userFromCode($params['code'])
            ->getRaw();

        if (!isset($response['openid']) || empty($response['openid'])) {
            throw new Exception('获取openID失败');
        }
        return $response;
    }

    /**
     * @notes 公众号跳转url
     * @param $url
     * @return string
     * @author cjhao
     * @date 2021/8/16 15:00
     */
    public static function getCodeUrl($url)
    {
        $config = WeChatConfigService::getOaConfig();
        $app = Factory::officialAccount($config);
        $response = $app->oauth->scopes(['snsapi_userinfo'])->redirect($url);

        return $response;
    }

}
