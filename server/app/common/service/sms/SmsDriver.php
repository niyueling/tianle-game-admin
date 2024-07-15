<?php

namespace app\common\service\sms;

use app\common\enum\NoticeEnum;
use app\common\enum\SmsEnum;
use app\common\enum\YesNoEnum;
use app\common\model\Notice;
use app\common\model\SmsLog;
use app\common\service\ConfigService;

/**
 * 短信驱动
 * Class SmsDriver
 * @package app\common\service\sms
 */
class SmsDriver
{
    /**
     * 错误信息
     * @var
     */
    protected $error = null;

    /**
     * 默认短信引擎
     * @var
     */
    protected $defaultEngine;

    /**
     * 短信引擎
     * @var
     */
    protected $engine;

    /**
     * 架构方法
     * SmsDriver constructor.
     */
    public function __construct()
    {
        // 初始化
        $this->initialize();
    }

    /**
     * @notes 初始化
     * @return bool
     * @author Tab
     * @date 2021/8/19 14:43
     */
    public function initialize()
    {
        try {
            $defaultEngine = ConfigService::get('sms', 'engine', false);
            if($defaultEngine === false) {
                throw new \Exception('请开启短信配置');
            }
            $this->defaultEngine = $defaultEngine;
            $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst(strtolower($defaultEngine)) . 'Sms';
            if (!class_exists($classSpace)) {
                throw new \Exception('没有相应的短信驱动类');
            }
            $engineConfig = ConfigService::get('sms', strtolower($defaultEngine), false);
            if($engineConfig === false) {
                throw new \Exception($defaultEngine . '未配置');
            }
            if ($engineConfig['status'] != 1) {
                throw new \Exception('短信服务未开启');
            }
            $this->engine = new $classSpace($engineConfig);
            if(!is_null($this->engine->getError())) {
                throw new \Exception($this->engine->getError());
            }
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 获取错误信息
     * @return mixed
     * @author Tab
     * @date 2021/8/19 14:42
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @notes 发送短信
     * @param $mobile
     * @param $data
     * @return false
     * @author Tab
     * @date 2021/8/19 16:51
     */
    public function send($mobile, $data)
    {
        try {
            // 发送频率限制
            $this->sendLimit($mobile);
            // 开始发送
            $result = $this->engine
                ->setMobile($mobile)
                ->setTemplateId($data['template_id'])
                ->setTemplateParams($data['params'])
                ->send();
            if(false === $result) {
                throw new \Exception($this->engine->getError());
            }
            return $result;
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 发送频率限制
     * @param $mobile
     * @throws \Exception
     * @author Tab
     * @date 2021/8/20 10:31
     */
    public function sendLimit($mobile)
    {
        $smsLog = SmsLog::where([
            ['mobile', '=', $mobile],
            ['send_status', '=', SmsEnum::SEND_SUCCESS],
            ['scene_id', 'in', NoticeEnum::SMS_SCENE],
            ])
            ->order('send_time', 'desc')
            ->findOrEmpty()
            ->toArray();
        if(!empty($smsLog) && ($smsLog['send_time'] > time() - 60)) {
            throw new \Exception('同一手机号1分钟只能发送1条短信');
        }
    }

    /**
     * @notes 校验手机验证码
     * @param $mobile
     * @param $code
     * @return bool
     * @author Tab
     * @date 2021/8/20 10:47
     */
    public function verify($mobile, $code)
    {
        $smsLog = SmsLog::where([
            ['mobile', '=', $mobile],
            ['send_status', '=', SmsEnum::SEND_SUCCESS],
            ['scene_id', 'in', NoticeEnum::SMS_SCENE],
            ['is_verify', '=', YesNoEnum::NO],
        ])
            ->order('send_time', 'desc')
            ->findOrEmpty();
        // 没有验证码 或 最新验证码已校验 或 已过期(有效期：5分钟)
        if($smsLog->isEmpty() || $smsLog->is_verify || ($smsLog->send_time < time() - 5 * 60) ) {
            return false;
        }
        if($smsLog->code == $code) {
            // 更新校验状态
            $smsLog->check_num = $smsLog->check_num + 1;
            $smsLog->is_verify = YesNoEnum::YES;
            $smsLog->save();
            return true;
        }
        // 更新验证次数
        $smsLog->check_num = $smsLog->check_num + 1;
        $smsLog->save();
        return false;
    }
}
