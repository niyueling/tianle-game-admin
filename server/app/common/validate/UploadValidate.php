<?php

namespace app\common\validate;
class UploadValidate extends BaseValidate
{

    protected $rule = [
        'file'              => 'fileExt:jpg,jpeg,gif,png,bmp,tga,tif,pdf,psd,avi,mp4,mp3,wmv,mpg,mpeg,mov,rm,ram,swf,flv',
    ];

    protected $message = [
        'file.fileExt'      => '该文件类型不允许上传',
    ];

}