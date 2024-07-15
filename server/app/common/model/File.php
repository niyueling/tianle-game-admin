<?php


namespace app\common\model;


use think\model\concern\SoftDelete;

class File extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}