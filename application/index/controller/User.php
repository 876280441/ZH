<?php


namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\User as UserModel;
use think\Db;
use think\facade\Request;
use think\response\json;
use think\facade\Session;

class User extends Base
{
    //注册页面
    public function register(){
        //检测是否开启注册
        $this->is_reg();
        $this->assign('title','用户注册');
        return $this->fetch();
    }
    //处理用户提交的注册信息
    public function insert(){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];
        $data = [
            'name'=>$name,
            'email'=>$email,
            'mobile'=>$mobile,
            'password'=>$password,
        ];
        $datas = Request::post();//要验证的数据
        $rule = 'app\common\validate\User';//自定义的验证规则
        $res = $this->validate($datas,$rule);
        if (true!==$res){
            $this->success("{$res}",'index/user/register');
        }else{
//            $db = UserModel::create($data);//使用模型添加数据
            if ($db = UserModel::create($data)){
                //数据保存在对象中
                $res = UserModel::get($db->id);
                //利用对象中的数据直接session值，从而达到登录的状态
                Session::set('user_id',$res->id);
                Session::set('user_name',$res->name);
                $this->success('注册成功','index/index');
            }else{
                $this->success('注册失败','index/user/register');
            }
        }
    }
    //用户登录
    public function login(){
        $this->logined();//防止用户重复登录
        $this->assign('title','用户登录');
        return $this->view->fetch('login');
    }
    //处理用户登录的信息
    public function loginCheck(){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $data = [
            'email'=>$email,
            'password'=>$password,
        ];
        $datas = Request::post();//要验证的数据
        //自定义的验证规则
        $rule =[
            'email|邮箱'=>'require|email',
            'password|密码'=>'require|alphaNum',
        ];
        $res = $this->validate($datas,$rule);
        if (true!==$res){
            return ['status'=>-1,'message'=>$res];
        }else{
            $result = UserModel::get(function ($query) use ($data){
                $query->where('email',$data['email'])
                    ->where('password',$data['password']);
            });
            if (null==$result){
                $this->success('登录失败','index/user/login');

            }else{
                //登录成功后把用户信息保存到session中
                Session::set('user_id',$result->id);
                Session::set('user_name',$result->name);
                $this->success('登录成功','index/index');
            }
        }
    }
    //退出登录
    public function logout(){
        Session::delete('user_id');
        Session::delete('user_name');
        $this->success("退出成功",'index/index');
    }
}