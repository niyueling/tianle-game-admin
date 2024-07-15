<?php

namespace app\common\enum;

/**
 * 通过枚举类，枚举只有两个值的时候使用
 * Class YesNoEnum
 * @package app\common\enum
 */
class YesNoEnum
{
    const YES = 1;
    const NO = 0;

    /**
     * @notes 获取禁用状态
     * @param bool $value
     * @return string|string[]
     * @author 令狐冲
     * @date 2021/7/8 19:02
     */
    public static function getDisableDesc($value = true)
    {
        $data = [
            self::YES => '禁用',
            self::NO => '正常'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }

    /**
     * @notes 获取显示隐藏状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2021/7/31 6:05 下午
     */
    public static function getIsShowDesc($value = true)
    {
        $data = [
            self::YES => '显示',
            self::NO => '隐藏'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }

    /**
     * @notes 获取启用停用状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2021/8/11 8:01 下午
     */
    public static function getIsOpenDesc($value = true)
    {
        $data = [
            self::YES => '启用',
            self::NO => '停用'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }
}
