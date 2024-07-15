<?php



namespace app\common\service;


use think\facade\Cache;

class FileService
{
    /**
     * @notes 补全路径
     * @param $uri
     * @param bool $type
     * @return string
     * @author 张无忌
     * @date 2021/7/28 15:08
     */
    public static function getFileUrl($uri = '', $type = false)
    {
//        if(empty(trim($uri))) return $uri;
        if (strstr($uri, 'http://'))  return $uri;
        if (strstr($uri, 'https://')) return $uri;

        $default = Cache::get('STORAGE_DEFAULT');
        if (!$default) {
            $default = ConfigService::get('storage', 'default', 'local');
            Cache::set('STORAGE_DEFAULT', $default);
        }

        if ($default === 'local') {
            if($type == 'share') {
                return public_path(). $uri;
            }
            return request()->domain() . '/' . $uri;
        } else {
            $storage = Cache::get('STORAGE_ENGINE');
            if (!$storage) {
                $storage = ConfigService::get('storage', $default);
                Cache::set('STORAGE_ENGINE', $storage);
            }
            return $storage ?  $storage['domain'] . '/' . $uri : $uri;
        }
    }

    /**
     * @notes 转相对路径
     * @param $uri
     * @return mixed
     * @author 张无忌
     * @date 2021/7/28 15:09
     */
    public static function setFileUrl($uri)
    {
        $default = ConfigService::get('storage', 'default', 'local');
        if ($default === 'local') {
            $domain = request()->domain();
            return str_replace($domain.'/', '', $uri);
        } else {
            $storage = ConfigService::get('storage', $default);
            return str_replace($storage['domain'].'/', '', $uri);
        }
    }
}