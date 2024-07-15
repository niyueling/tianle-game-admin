<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\common\model;


use app\common\service\FileService;

class PayWay extends BaseModel
{
    protected $name = 'dev_pay_way';

    public function getIconAttr($value,$data)
    {
        return FileService::getFileUrl($value);
    }

    /**
     * @notes 支付方式名称获取器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2021/7/28 4:02 下午
     */
    public static function getPayWayNameAttr($value,$data)
    {
        return PayConfig::where('id',$data['dev_pay_id'])->value('name');
    }

    /**
     * @notes 关联支配配置模型
     * @return \think\model\relation\HasOne
     * @author ljj
     * @date 2021/10/11 3:04 下午
     */
    public function payConfig()
    {
        return $this->hasOne(PayConfig::class,'id','dev_pay_id');
    }
}