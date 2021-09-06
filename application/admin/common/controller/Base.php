<?php
namespace app\admin\common\controller;
use think\Controller;
use think\facade\Session;
//后台公共控制器
class Base extends Controller
{
    //初始化方法
    protected function initialize()
    {

    }
    //检测用户是否登录
    public  function isLogin(){
        if (!Session::has('admin_id')){
            $this->error("还未登录!",'admin/user/login');
        }
    }
}