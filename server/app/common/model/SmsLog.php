<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * 短信记录模型
 * Class SmsLog
 * @package app\common\model
 */
class SmsLog extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
}