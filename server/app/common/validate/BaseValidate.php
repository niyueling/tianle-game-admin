<?php



namespace app\common\validate;


use app\common\service\JsonService;
use think\Validate;

class BaseValidate extends Validate
{
    public string $method = 'GET';

    /**
     * @notes 设置请求方式
     * @author 令狐冲
     * @date 2021/7/13 00:07
     */
    public function post()
    {
        if (!$this->request->isPost()) {
            JsonService::throw('请求方式错误，请使用post请求方式');
        }
        $this->method = 'POST';
        return $this;
    }

    /**
     * @notes 设置请求方式
     * @author 令狐冲
     * @date 2021/7/13 00:07
     */
    public function get()
    {
        if (!$this->request->isGet()) {
            JsonService::throw('请求方式错误，请使用get请求方式');
        }
        return $this;
    }


    /**
     * @notes 切面验证接收到的参数
     * @param null $scene 场景验证
     * @param array $validate_data 验证参数，可追加和覆盖掉接收的参数
     * @return array
     * @author 令狐冲
     * @date 2021/6/29 21:05
     */
    public function goCheck($scene = null, $validate_data = [])
    {
        //接收参数
        if ($this->method == 'GET') {
            $params = request()->get();
        } else {
            $params = request()->post();
        }
        //合并验证参数
        $params = array_merge($params, $validate_data);

        //场景
        if ($scene) {
            $result = $this->scene($scene)->check($params);
        } else {
            $result = $this->check($params);
        }


        if (!$result) {
            $exception = is_array($this->error) ? implode(';', $this->error) : $this->error;
            JsonService::throw($exception);
        }
        // 3.成功返回数据
        return $params;
    }
}