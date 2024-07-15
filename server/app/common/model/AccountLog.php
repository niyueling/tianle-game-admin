<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * 账户流水记录模型
 * Class AccountLog
 * @package app\common\model
 */
class AccountLog extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
}