<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;

class User extends Base
{
    //渲染登录页面
    public function Login()
    {
        $this->view->assign('title','管理员登录');
        return $this->view->fetch('login');
    }
    //验证后台登录
    public function checkLogin(){
        //将获取到的值放到data数组中
        $data = Request::param();
        //查询条件
        $map[] = ['email','=',$data['email']];
        $map[] = ['password','=',$data['password']];
        $result = UserModel::where($map)->find();
        if ($result){
            Session::set('admin_id',$result['id']);
            Session::set('admin_name',$result['name']);
            Session::set('admin_level',$result['is_admin']);
            $this->success('管理员登录成功','admin/user/userlist');
        }
            $this->error('登录失败，请检查是否为管理员或找号是否正确!');
    }
    //退出登录功能
    public function logout(){
        //清除登录Session
        Session::clear();
        //退出登录要跳转到后台登录页面
        $this->success('退出成功','admin/user/login');
    }
    //用户列表
    public function userList(){
        //获取到当前用户的id和级别is_admin
        $data['admin_id'] = Session::get('admin_id');
        $data['admin_level'] = Session::get('admin_level');
        //获取当前用户的信息
        $userList = UserModel::where('id',$data['admin_id'])->select();
        //如果是超级管理员，那么就获取到全部信息
        if($data['admin_level']==1){
            $userList = UserModel::select();
        }
        //模板赋值
        $this->view->assign('title','用户管理');
        $this->view->assign('empty','<span style="color: red">没有任何数据</span>');
        $this->view->assign('userList',$userList);
        //渲染出用户列表的模板
        return $this->view->fetch('userList');
    }
    //渲染编辑用户的页面
    public function userEdit(){
        //获取要更新用户信息的主键
        $userId = Request::param('id');
        //根据主键进行查询
        $userInfo = UserModel::where('id',$userId)->find();
        //设置编辑页面的模板变量
        $this->view->assign('title','编辑用户');
        $this->view->assign('userInfo',$userInfo);
        //渲染出编辑页面
        return $this->view->fetch('useredit');
    }
    //执行用户修改的信息的保存
    public function doEdit(){
        //获取到用户提交的数据
        $data = Request::param();
        //取出主键
        $id = $data['id'];
        //删除主键
        unset($data['id']);
        //执行更新操作
        if (UserModel::where('id',$id)->data($data)->update()){
            return $this->success('信息更新成功','logouts');
        }
        //更新失败
        $this->error('没有更新或更新失败');
    }
    public function logouts(){
        //清除登录Session
        Session::clear();
        //退出登录要跳转到后台登录页面
        $this->success('请重新登录','admin/user/login');
    }
    //执行用户的删除操作
    public function doDelete(){
        //获取要删除的主键id
        $id = Request::param('id');
        //执行删除
        if (UserModel::where('id',$id)->delete()){
            return $this->success('删除成功','userList');
        }
        //删除失败
        $this->error('删除失败');
    }
}