<?php

namespace app\adminapi\validate\wechat;
use app\common\validate\BaseValidate;

class WechatLoginValidate extends BaseValidate
{
    protected $rule = [
        'code'          => 'require',
        'nickname'      => 'require',
        'headimgurl'    => 'require',
        'openid'        => 'require',
        'access_token'  => 'require',
        'terminal'      => 'require',
        'avatar'      => 'require',
    ];

    protected $message = [
        'code.require'          => 'code缺少',
        'nickname.require'      => '昵称缺少',
        'headimgurl.require'    => '头像缺少',
        'openid.require'        => 'opendid缺少',
        'access_token.require'  => 'access_token缺少',
        'terminal.require'      => '终端参数缺少',
        'avatar.require'    => '头像缺少',
    ];

    public function sceneSilent(){
        return $this->only(['code']);
    }

    public function sceneOa()
    {
        return $this->only(['code']);
    }

    public function sceneAuth()
    {
        return $this->only(['code','nickname','headimgurl']);
    }

    public function sceneWechatAuth()
    {
        return $this->only(['code']);
    }

    public function sceneUninapp()
    {
        return $this->only(['openid','access_token','terminal']);
    }

    public function sceneUpdateUser()
    {
        return $this->only(['nickname','avatar']);
    }

}
