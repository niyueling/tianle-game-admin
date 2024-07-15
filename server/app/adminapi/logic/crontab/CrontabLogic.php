<?php

namespace app\adminapi\logic\crontab;

use app\common\enum\CrontabEnum;
use app\common\logic\BaseLogic;
use app\common\model\Crontab;

/**
 * 定时任务逻辑层
 * Class CrontabLogic
 * @package app\adminapi\logic\crontab
 */
class CrontabLogic extends BaseLogic
{
    /**
     * @notes 添加定时任务
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/17 11:47
     */
    public static function add($params)
    {
        try {
            $params['remark'] = $params['remark'] ?? '';
            $params['params'] = $params['params'] ?? '';
            $params['last_time'] = time();

            Crontab::create($params);

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 查看定时任务详情
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/8/17 11:58
     */
    public static function detail($params)
    {
        $field = 'id,name,type,type as type_desc,command,params,status,status as status_desc,expression,remark';
        $crontab = Crontab::field($field)->findOrEmpty($params['id']);
        if($crontab->isEmpty()) {
            return [];
        }
        return $crontab->toArray();
    }

    /**
     * @notes 编辑定时任务
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/17 12:02
     */
    public static function edit($params)
    {
        try {
            $params['remark'] = $params['remark'] ?? '';
            $params['params'] = $params['params'] ?? '';

            Crontab::update($params);

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除定时任务
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/17 14:13
     */
    public static function delete($params)
    {
        try {
            Crontab::destroy($params['id']);

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 操作定时任务
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/17 15:28
     */
    public static function operate($params)
    {
        try {
            $crontab = Crontab::findOrEmpty($params['id']);
            if($crontab->isEmpty()) {
                throw new \think\Exception('定时任务不存在');
            }
            switch($params['operate']) {
                case 'start';
                    $crontab->status = CrontabEnum::START;
                    break;
                case 'stop':
                    $crontab->status = CrontabEnum::STOP;
                    break;
            }
            $crontab->save();

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取规则执行时间
     * @param $params
     * @return array|string
     * @author Tab
     * @date 2021/8/17 15:42
     */
    public static function expression($params)
    {
        try {
            $cron = new \Cron\CronExpression($params['expression']);
            $result = $cron->getMultipleRunDates(5);
            $result = json_decode(json_encode($result), true);
            $lists = [];
            foreach ($result as $k => $v) {
                $lists[$k]['time'] = $k + 1;
                $lists[$k]['date'] = str_replace('.000000', '', $v['date']);
            }
            $lists[] = ['time' => 'x', 'date' => '……'];
            return $lists;
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}