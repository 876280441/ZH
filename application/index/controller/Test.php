<?php
//专用测试的

namespace app\index\controller;
use think\Db;
use app\common\controller\Base;
use app\common\model\User;
class Test extends Base
{
    //测试用户的验证器
    public function test1(){
        $data = [
            'name'=>'小信的老婆',
            'email'=>'8762@qq.com',
            'mobile'=>'15768866720',
            'password'=>'123456',
        ];
        $rule = 'app\common\validate\User';
        return $this->validate($data,$rule);
    }
    public function gb(){
        $data = ['name'=>'大信','email'=>'87628@qq.com','moblie'=>'15768867788','password'=>'1234566'];
        Db::name('ch_user')->data($data)->insert();
    }
    public function te(){
        dump(User::get(1));
    }
    public function se(){
        var_dump(phpinfo());
    }
}