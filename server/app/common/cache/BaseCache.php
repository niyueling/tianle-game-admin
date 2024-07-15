<?php

declare(strict_types=1);

namespace app\common\cache;


use think\App;
use think\Cache;

/**
 * 缓存基础类，用于管理缓存
 * Class BaseCache
 * @package app\common\cache
 */
abstract class BaseCache extends Cache
{
    protected $tagName;//缓存标签

    public function __construct()
    {

        parent::__construct(app());
        $this->tagName = get_class($this);

    }

    /**
     * @notes 重写父类set，自动打上标签
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     * @author cjhao
     * @date 2021/10/13 17:07
     */
    public function set($key, $value, $ttl = null): bool
    {

        return $this->store()->tag($this->tagName)->set($key, $value, $ttl);
    }
    /**
     * @notes 清除缓存类所有缓存
     * @return bool
     * @author 令狐冲
     * @date 2021/8/19 16:38
     */
    public function deleteTag()
    {
        return $this->tag($this->tagName)->clear();
    }

}