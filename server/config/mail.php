<?php

// +----------------------------------------------------------------------
// | 邮件设置
// +----------------------------------------------------------------------
return [
    'secure'      => "ssl",//指定使用的加密方式。常见的有ssl和tls两种。
    'host'        => "smtp.qq.com",//指定发送邮件的SMTP服务器地址。
    'port'        => 465,//指定SMTP服务器的端口号，一般默认为25或465。
    'username'    => "1061451899@qq.com",//指定SMTP服务器的用户名
    "from_name"   => "凡盟系统警报",//发件人名称
    'password'    => "verzxgzhedtobbba",//指定SMTP服务器的密码
];
