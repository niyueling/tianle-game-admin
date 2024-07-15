<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * 首页访问浏览记录
 * Class Visitor
 * @package app\common\model
 */
class IndexVisit extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

}