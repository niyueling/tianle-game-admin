<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * 定时任务表
 * Class Crontab
 * @package app\common\model
 */
class Crontab extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $name = 'dev_crontab';

    /**
     * @notes 类型获取器
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/8/17 11:03
     */
    public function getTypeDescAttr($value)
    {
        $desc = [
            1 => '定时任务',
            2 => '守护进程',
        ];

        return $desc[$value] ?? '';
    }

    /**
     * @notes 状态获取器
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/8/17 11:04
     */
    public function getStatusDescAttr($value)
    {
        $desc = [
            1 => '运行',
            2 => '停止',
            3 => '错误',
        ];

        return $desc[$value] ?? '';
    }

    /**
     * @notes 最后执行时间获取器
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/8/17 14:35
     */
    public function getLastTimeAttr($value)
    {
        return empty($value) ? '' : date('Y-m-d H:i:s', $value);
    }
}