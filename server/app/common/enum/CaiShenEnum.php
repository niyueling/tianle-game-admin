<?php

namespace app\common\enum;

/**
 * 财神方位枚举
 * Class DefaultEnum
 * @package app\common\enum
 */
class CaiShenEnum
{
    const CaiShenPosition = [
        "甲" => "西南", "乙" => "西南", "丙" => "正西", "丁" => "西北", "戊" => "东北",
        "己" => "正北", "庚" => "东北", "辛" => "东北", "壬" => "正东", "癸" => "正东"
    ];

    const BlessList = [
        "大吉" => 4,
        "上吉" => 3,
        "中吉" => 2,
        "下下" => 1,
    ];
}
