<?php

namespace app\common\enum;

/**
 * 短信枚举
 * Class SmsEnum
 * @package app\common\enum
 */
class SmsEnum
{
    /**
     * 发送状态
     */
    const SEND_ING = 0;
    const SEND_SUCCESS = 1;
    const SEND_FAIL = 2;

    /**
     * 短信平台
     */
    const ALI = 1;
    const TENCENT = 2;
}