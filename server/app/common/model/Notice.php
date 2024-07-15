<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * 通知记录模型
 * Class Notice
 * @package app\common\model
 */
class Notice extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

}