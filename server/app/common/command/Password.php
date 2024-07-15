<?php


namespace app\common\command;

use app\common\model\Admin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Config;

/**
 * 修改超级管理员密码
 */
class Password extends Command
{

    protected function configure()
    {
        $this->setName('password')
            ->addArgument('password', Argument::OPTIONAL, "please input new password")
            ->setDescription('修改超级管理员密码');
    }

    protected function execute(Input $input, Output $output)
    {
        $password = trim($input->getArgument('password'));
        if (empty($password)) {
            $output->error('请输入密码');
            return;
        }
        $passwordSalt = Config::get('project.unique_identification');
        $newPassword = create_password($password, $passwordSalt);
        $rootAdmin = Admin::where('root', 1)->findOrEmpty();
        if ($rootAdmin->isEmpty()) {
            $output->error('超级管理员不存在');
            return;
        }
        $rootAdmin->password = $newPassword;
        $rootAdmin->save();
        $output->info('超级管理修改密码成功！');
        $output->info('账号：' . $rootAdmin->account);
        $output->info('密码：' . $password);
    }
}