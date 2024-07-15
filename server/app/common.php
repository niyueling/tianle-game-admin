<?php
declare(strict_types=1);
use think\facade\Db;

/**
 * @notes 生成密码加密密钥
 * @param string $plaintext
 * @param string $salt
 * @return string
 * @author 令狐冲
 * @date 2021/7/5 11:52
 */
function create_password(string $plaintext, string $salt): string
{
    return md5($salt . md5($plaintext . $salt));
}

function formatNumber($number) {
    if($number < 10000) return round($number / 1000, 2) . '千';
    if($number < 100000000) return round($number / 10000, 2) . '万';
    else return round($number / 100000000, 2) . '亿';
}

function formatPayType($rule, $clubName) {
        if(!empty($rule["clubOwnerPay"]) && $rule["clubOwnerPay"]) return "战队主付";
        if(!empty($rule["share"]) && $rule["share"]) return "AA付";
        if(!empty($rule["creatorPay"]) && $rule["creatorPay"]) return "房主付";
        if(!empty($rule["winnerPay"]) && $rule["winnerPay"]) return "赢家付";
        if($clubName != '/') return "战队主付";
        else return "/";
    }

function fuzzy_query(string $params) {
    return $params;
}

function nullResultReturn() {
    return [
        "count" => 0,
        "lists" => [],
        "page_no" => 1,
        "page_size" => 10
    ];
}

/**
 * @notes 截取某字符字符串
 * @param $str
 * @param string $symbol
 * @return string
 * @author 令狐冲
 * @date 2021/7/7 18:42
 */
function substr_symbol_behind($str, $symbol = '.')
{
    $result = strripos($str, $symbol);
    if ($result === false) {
        return $str;
    };
    return substr($str, $result + 1);
}

/**
 * @notes 随机生成token值
 * @param string $extra
 * @return string
 * @author 令狐冲
 * @date 2021/7/1 19:04
 */
function create_token(string $extra = ''): string
{
    return md5($extra . time());
}

/**
 * @notes 通过时间生成订单编号
 * @param $table
 * @param $field
 * @param string $prefix
 * @param int $rand_suffix_length
 * @param array $pool
 * @return string
 * @author 段誉
 * @date 2021/7/23 14:15
 */
function generate_sn($table, $field, $prefix = '', $rand_suffix_length = 6, $pool = []) : string
{
    $suffix = '';
    for ($i = 0; $i < $rand_suffix_length; $i++) {
        if (empty($pool)) {
            $suffix .= rand(0, 9);
        } else {
            $suffix .= $pool[array_rand($pool)];
        }
    }
    $sn = $prefix . date('Ymd') . $suffix;
    if (Db::connect('mongodb')->table($table)->where($field, $sn)->find()) {
        return generate_sn($table, $field, $prefix, $rand_suffix_length, $pool);
    }
    return $sn;
}

/**
 * @notes 随机生成邀请码
 * @param $length
 * @return string
 * @author Tab
 * @date 2021/7/26 11:17
 */
function generate_code($length = 6)
{
    // 去除字母IO数字012
    $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ3456789';
    // 随机起始索引
    $start = mt_rand(0, strlen($letters) - $length);
    // 打乱字符串
    $shuffleStr = str_shuffle($letters);
    // 截取字符
    $randomStr = substr($shuffleStr, $start, $length);
    // 判断是否已被使用
    $count = Db::connect('mongodb')->table('gms')->where("inviteCode", $randomStr)->count();
    if($count == 0) {
        return $randomStr;
    }
    generate_code($length);
}

/**
 * @notes 获取文件扩展名
 * @param $file
 * @return array|string|string[]
 * @author 段誉
 * @date 2021/8/14 15:24
 */
function get_extension($file)
{
    return pathinfo($file, PATHINFO_EXTENSION);
}

/**
 * @notes 删除目标目录
 * @param $path
 * @param $delDir
 * @return bool|void
 * @author 段誉
 * @date 2021/8/14 15:33
 */
function del_target_dir($path, $delDir)
{
    //没找到，不处理
    if (!file_exists($path)) {
        return false;
    }

    //打开目录句柄
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    del_target_dir("$path/$item", $delDir);
                } else {
                    unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        if ($delDir) {
            return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}

function day_time(int $days = 7,bool $timestamp = false,bool $isBefore = true,$starting = '')
{
    empty($starting) && $starting = strtotime(date('Y-m-d'));

    $time = $starting;
    if(false === $timestamp){
        $time = date('Y-m-d',$starting);
    }
    $dayList[] = $time;

    while ($days > 1){
        if($isBefore){
            $starting -= 86400;
        }else{
            $starting += 86400;
        }

        $time = $starting;
        if(false === $timestamp){
            $time = date('Y-m-d',$starting);
        }
        $dayList[] = $time;
        $days--;
    }
    return $dayList;

}

function checkQuantity($quantity, $source) {
    if($source == "redpocket") return $quantity * 100;
    else return $quantity;
}

/**
 * @notes 获取服务端ip
 * @return array|false|mixed|string
 * @author 段誉
 * @date 2021/10/9 15:29
 */
function get_server_ip()
{
    if (!isset($_SERVER)) {
        return getenv('SERVER_ADDR');
    }

    if($_SERVER['SERVER_ADDR']) {
        return $_SERVER['SERVER_ADDR'];
    }

    return $_SERVER['LOCAL_ADDR'];
}

function is_mobile()
{
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    if (isset($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'textml') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'textml')))) {
            return true;
        }
    }
    return false;
}

function curl_get($url){

    $header = array(
        'Accept: application/json',
    );
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 超时设置,以秒为单位
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //执行命令
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
        return json_decode($data, true);
    }
}

function curl_post($url, $postdata) {

    $header = array(
        'Accept: application/json',
    );

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false );

    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata, JSON_UNESCAPED_UNICODE));
    //执行命令
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
        return json_decode($data, true);
    }
}

/**
 * 多级线性结构排序
 * 转换前：
 * [{"id":1,"pid":0,"name":"a"},{"id":2,"pid":0,"name":"b"},{"id":3,"pid":1,"name":"c"},
 * {"id":4,"pid":2,"name":"d"},{"id":5,"pid":4,"name":"e"},{"id":6,"pid":5,"name":"f"},
 * {"id":7,"pid":3,"name":"g"}]
 * 转换后：
 * [{"id":1,"pid":0,"name":"a","level":1},{"id":3,"pid":1,"name":"c","level":2},{"id":7,"pid":3,"name":"g","level":3},
 * {"id":2,"pid":0,"name":"b","level":1},{"id":4,"pid":2,"name":"d","level":2},{"id":5,"pid":4,"name":"e","level":3},
 * {"id":6,"pid":5,"name":"f","level":4}]
 * @param array $data 线性结构数组
 * @param string $symbol 名称前面加符号
 * @param string $name 名称
 * @param string $id_name 数组id名
 * @param string $parent_id_name 数组祖先id名
 * @param int $level 此值请勿给参数
 * @param int $parent_id 此值请勿给参数
 * @return array
 */
function linear_to_tree($data, $sub_key_name = 'sub', $id_name = 'id', $parent_id_name = 'pid', $parent_id = 0)
{
    $tree = [];
    foreach ($data as $row) {
        if ($row[$parent_id_name] == $parent_id) {
            $temp = $row;
            $child = linear_to_tree($data, $sub_key_name, $id_name, $parent_id_name, $row[$id_name]);
            if($child){
                $temp[$sub_key_name] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
}

/**
 * 将图片切成圆角
 */
function rounded_corner($src_img)
{
    $w = imagesx($src_img);//微信头像宽度 正方形的
    $h = imagesy($src_img);//微信头像宽度 正方形的
    $w = min($w, $h);
    $h = $w;
    $img = imagecreatetruecolor($w, $h);
    //这一句一定要有
    imagesavealpha($img, true);
    //拾取一个完全透明的颜色,最后一个参数127为全透明
    $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
    imagefill($img, 0, 0, $bg);
    $r = $w / 2; //圆半径
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgbColor = imagecolorat($src_img, $x, $y);
            if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                imagesetpixel($img, $x, $y, $rgbColor);
            }
        }
    }
    unset($src_img);
    return $img;
}

/**
 * Notes:去掉名称中的表情
 * @param $str
 * @return string|string[]|null
 */
function filterEmoji($str)
{
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);
    return $str;
}

function formatNumberStr($number) {
    if (abs($number) >= 10000 && abs($number) < 100000000) return round($number / 10000, 2) . '万';
    if (abs($number) >= 100000000 && abs($number) < 1000000000000) return round($number / 100000000, 2) . '亿';
    if (abs($number) >= 1000000000000 && abs($number) < 10000000000000000) return round($number / 1000000000000, 2) . '万亿';
    if (abs($number) >= 10000000000000000 && abs($number) < 100000000000000000000) return round($number / 10000000000000000, 2) . '兆';
    if (abs($number) >= 100000000000000000000 && abs($number) < 1000000000000000000000000) return round($number / 100000000000000000000, 2) . '万兆';
    else return $number;
}
