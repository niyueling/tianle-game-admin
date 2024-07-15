<?php


namespace app\common\model;


use think\model\concern\SoftDelete;

class FileCate extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}