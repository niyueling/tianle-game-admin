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

namespace app\adminapi\validate\settings\pay;


use app\common\enum\PayEnum;
use app\common\model\PayConfig;
use app\common\validate\BaseValidate;

class PayConfigValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkId',
        'name' => 'require|checkName',
        'icon' => 'require',
        'sort' => 'require|number|max:5',
    ];

    protected $message = [
        'id.require' => 'id不能为空',
        'name.require' => '支付名称不能为空',
        'icon.require' => '支付图标不能为空',
        'sort.require' => '排序不能为空',
        'sort,number' => '排序必须是纯数字',
        'sort.max' => '排序最大不能超过五位数',
    ];

    public function sceneGet()
    {
        return $this->only(['id'])
            ->remove('id','checkId');
    }

    /**
     * @notes 检查ID是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/7/27 6:01 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = PayConfig::where('id',$value)->find();
        if (empty($result)) {
            return '支付方式不存在';
        }
        if ($result['pay_way'] == PayEnum::WECHAT_PAY) {
            if (!isset($data['interface_version']) || empty($data['interface_version'])) {
                return '微信支付接口版本不能为空';
            }
            if (!isset($data['merchant_type']) || empty($data['merchant_type'])) {
                return '商户类型不能为空';
            }
            if (!isset($data['mch_id']) || empty($data['mch_id'])) {
                return '微信支付商户号不能为空';
            }
            if (!isset($data['pay_sign_key']) || empty($data['pay_sign_key'])) {
                return '商户API密钥不能为空';
            }
            if (!isset($data['apiclient_cert']) || empty($data['apiclient_cert'])) {
                return '微信支付证书不能为空';
            }
            if (!isset($data['apiclient_key']) || empty($data['apiclient_key'])) {
                return '微信支付证书密钥不能为空';
            }
        }
        if ($result['pay_way'] == PayEnum::ALI_PAY) {
            if (!isset($data['mode']) || empty($data['mode'])) {
                return '模式不能为空';
            }
            if (!isset($data['merchant_type']) || empty($data['merchant_type'])) {
                return '商户类型不能为空';
            }
            if (!isset($data['app_id']) || empty($data['app_id'])) {
                return '应用ID不能为空';
            }
            if (!isset($data['private_key']) || empty($data['private_key'])) {
                return '应用私钥不能为空';
            }
            if (!isset($data['ali_public_key']) || empty($data['ali_public_key'])) {
                return '支付宝公钥不能为空';
            }
        }
        return true;
    }

    /**
     * @notes 检查支付名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @author ljj
     * @date 2021/7/27 5:50 下午
     */
    public function checkName($value,$rule,$data)
    {
        $result = PayConfig::where('name', $value)->where('id','<>', $data['id'])->findOrEmpty();
        if (!$result->isEmpty()) {
            return '支付名称已存在';
        }
        return true;
    }
}