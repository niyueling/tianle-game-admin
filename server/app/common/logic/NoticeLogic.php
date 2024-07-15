<?php


namespace app\common\logic;

use app\common\enum\NoticeEnum;
use app\common\enum\YesNoEnum;
use app\common\model\Notice;
use app\common\model\NoticeSetting;
use app\common\service\sms\SmsMessageService;
use think\facade\Db;

/**
 * 通知逻辑层
 * Class NoticeLogic
 * @package app\common\logic
 */
class NoticeLogic extends BaseLogic
{
    /**
     * @notes 根据不同的场景发送通知
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/19 9:26
     */
    public static function noticeByScene($params)
    {
        try {
            $noticeSetting = NoticeSetting::where('scene_id', $params['scene_id'])->findOrEmpty()->toArray();
            if(empty($noticeSetting)) {
                throw new \Exception('找不到对应场景的配置');
            }
            // 合并额外参数
            $params = self::mergeParams($params);
            $res = false;
            self::setError('通知功能未开启');
            // 系统通知
            if(isset($noticeSetting['system_notice']['status']) &&  $noticeSetting['system_notice']['status'] == YesNoEnum::YES) {
                $content = self::contentFormat($noticeSetting['system_notice']['content'], $params);
                $notice = self::addNotice($params, $noticeSetting, NoticeEnum::SYSTEM, $content);
                if($notice) {
                    $res = true;
                }
            }
            // 短信通知
            if (isset($noticeSetting['sms_notice']['status']) &&  $noticeSetting['sms_notice']['status'] == YesNoEnum::YES) {
                $res = (new SmsMessageService())->send($params);
            }

            return $res;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 拼装额外参数
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/8/19 9:25
     */
    public static function mergeParams($params)
    {
        // 用户相关
        if(!empty($params['params']['user_id'])) {
            $user = Db::connect('mongodb')->table('players')->where("_id", $params['params']['user_id'])->find();
            $params['params']['nickname'] = $user['name'];
            $params['params']['user_name'] = $user['name'];
            $params['params']['user_sn'] = $user['shortId'];
            $params['params']['mobile'] = $params['params']['mobile'] ?? $user['phone'];
        }

        // 跳转路径
        $jumpPath = self::getPathByScene($params['scene_id'], $params['params']['order_id'] ?? 0);
        $params['url'] = $jumpPath['url'];
        $params['page'] = $jumpPath['page'];
        return $params;
    }

    /**
     * @notes 根据场景获取跳转链接
     * @param $sceneId
     * @param $extraId
     * @return string[]
     * @author Tab
     * @date 2021/8/19 9:26
     */
    public static function getPathByScene($sceneId, $extraId)
    {
        // 小程序主页路径
        $page = '/pages/index/index';
        // 公众号主页路径
        $url = '/mobile/pages/index/index';
        if(in_array($sceneId, NoticeEnum::ORDER_SCENE)) {
            $url = '/mobile/pages/order_detail/order_detail?order_id='.$extraId;
            $page = '/pages/order_detail/order_detail?order_id='.$extraId;
        }
        return [
            'url' => $url,
            'page' => $page,
        ];
    }

    /**
     * @notes 格式化消息内容(替换内容中的变量占位符)
     * @param $content
     * @param $params
     * @return array|mixed|string|string[]
     * @author Tab
     * @date 2021/8/19 9:39
     */
    public static function contentFormat($content, $params)
    {
        foreach($params['params'] as $k => $v) {
            $search = '{' . $k . '}';
            $content = str_replace($search, $v, $content);
        }
        return $content;
    }

    /**
     * @notes 添加通知记录
     * @param $params
     * @param $noticeSetting
     * @param $sendType
     * @param $content
     * @param string $extra
     * @return Notice|\think\Model
     * @author Tab
     * @date 2021/8/19 10:07
     */
    public static function addNotice($params, $noticeSetting, $sendType, $content, $extra = '')
    {
        $data = [
            'user_id' => $params['params']['user_id'] ?? 0,
            'title' => self::getTitleByScene($sendType, $noticeSetting),
            'content' => $content,
            'scene_id' => $noticeSetting['scene_id'],
            'read' => YesNoEnum::NO,
            'recipient' => $noticeSetting['recipient'],
            'send_type' => $sendType,
            'notice_type' => $noticeSetting['type'],
            'extra' => $extra,
        ];

        return Notice::create($data);
    }

    /**
     * @notes 根据场景获取标题
     * @param $sendType
     * @param $noticeSetting
     * @return string
     * @author Tab
     * @date 2021/8/19 9:51
     */
    public static function getTitleByScene($sendType, $noticeSetting)
    {
        switch ($sendType)
        {
            case NoticeEnum::SYSTEM:
                $title = $noticeSetting['system_notice']['title'] ?? '';
                break;
            case NoticeEnum::SMS:
                $title = '';
                break;
            case NoticeEnum::OA:
                $title = $noticeSetting['oa_notice']['name'] ?? '';
                break;
            case NoticeEnum::MNP:
                $title = $noticeSetting['mnp_notice']['name'] ?? '';
                break;
            default:
                $title = '';
        }

        return $title;
    }

}
