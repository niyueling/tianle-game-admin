<?php

namespace app\common\exception;
use app\common\enum\WechatErrorEnum;
use think\Exception;
use Throwable;

/**
 * 微信异常类
 * Class WechatException
 * @package app\common\exception
 */
class WechatException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $wechatMessage = WechatErrorEnum::wechatErrorMessage($code);
        if(empty($wechatMessage)){
            $wechatMessage .= $message.'; 错误码:'.$code;
        }
        $this->message = '微信提示:'.$wechatMessage;
//        $this->code = '错误码：'.$code;
        parent::__construct($this->message, $code, $previous);
    }



}