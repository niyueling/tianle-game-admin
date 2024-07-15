<?php


declare(strict_types=1);

namespace app\common\service;

use app\common\model\Config;
use MongoDB\BSON\ObjectId;
use think\facade\Cache;
use think\facade\Db;

class ConfigService
{

    /**
     * @notes
     * @param string $type
     * @param string $name
     * @return string
     * @author 令狐冲
     * @date 2022/10/19 16:13
     */
    public static function getCacheKey($type, $name): string
    {
        return 'config' . '-' . $type . '-' . $name;
    }

    /**
     * @notes 设置配置值
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @author Tab
     * @date 2021/7/15 14:54
     */
    public static function set($type, $name, $value)
    {
        //刷新缓存
        $cacheKey = self::getCacheKey($type, $name);
        Cache::delete($cacheKey);

        $original = $value;
        // 数组数据进行json编码
        if (is_array($value)) {
            // JSON_UNESCAPED_UNICODE 不对中文进行unicode编码
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $data = Config::where(['type' => $type, 'name' => $name])->findOrEmpty();
        if ($data->isEmpty()) {
            // 没有则添加
            Config::create([
                'type' => $type,
                'name' => $name,
                'value' => $value,
            ]);
        } else {
            // 有则修改
            $data->value = $value;
            $data->save();
        }
        // 返回原始值
        return $original;
    }

    /**
     * @notes 设置配置值
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @author Tab
     * @date 2021/7/15 14:54
     */
    public static function setGlobal($type, $name, $value)
    {
        $data = Db::connect('mongodb')->table('globalconfigs')->where(['type' => $type, 'name' => $name])->findOrEmpty();
        if (empty($data)) {
            Db::connect('mongodb')->table('globalconfigs')->insert([
                'type' => $type,
                'name' => $name,
                'value' => $value,
            ]);
        } else {
            $data["value"] = $value;
            Db::connect('mongodb')->table('globalconfigs')->where(['type' => $type, 'name' => $name])->update(["value" => $data["value"]]);
        }
        // 返回原始值
        return $value;
    }

    /**
     * @notes
     * @param $type
     * @param string $name
     * @param null $defaultValue
     * @return mixed
     * @author 令狐冲
     * @date 2022/10/19 16:16
     */
    public static function get($type, $name = '', $defaultValue = null)
    {
        //单项配置
        if ($name) {
            $result = Config::where(['type' => $type, 'name' => $name])->value('value');
            //json类型解析
            if (!is_null($result)) {
                $json = json_decode($result, true);
                $result = json_last_error() === JSON_ERROR_NONE ? $json : $result;
            }
            //获取默认配置
            if ($result === null) {
                $result = $defaultValue;
            }
            //获取本地配置文件配置
            if ($result === null) {
                $result = config('project.' . $type . '.' . $name);
            }
            return $result;
        }

        //多项配置
        $result = Config::where(['type' => $type])->column('value', 'name');
        if (is_array($result)) {
            foreach ($result as $k => $v) {
                $json = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $result[$k] = $json;
                }
            }
        }
        //读取默认配置
        if ($result === null) {
            $result = $defaultValue;
        }
        return $result;
    }

    /**
     * @notes
     * @param $type
     * @param string $name
     * @param null $defaultValue
     * @return mixed
     * @author 令狐冲
     * @date 2022/10/19 16:16
     */
    public static function getGlobal($type, $name = '')
    {
        $result = Db::connect('mongodb')->table('globalconfigs')->where(['type' => $type, 'name' => $name])->find();
        return $result["value"] ?? "";
    }

    /**
     * @notes
     * @param $type
     * @param string $name
     * @param null $defaultValue
     * @return mixed
     * @author 令狐冲
     * @date 2022/10/19 16:16
     */
    public static function getIosSandboxConfig()
    {
        $result = Db::connect('mongodb')->table('versions')->where("_id", new ObjectId("63b7b8cfa0c8500ebf0d38e3"))->find();
        return $result["auditVersion"] ?? "";
    }

    /**
     * @notes 设置配置值
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @author Tab
     * @date 2021/7/15 14:54
     */
    public static function setIosSandboxConfig($value)
    {
        Db::connect('mongodb')->table('versions')->where("_id", new ObjectId("63b7b8cfa0c8500ebf0d38e3"))->update(["auditVersion" => $value]);
        return $value;
    }
}
