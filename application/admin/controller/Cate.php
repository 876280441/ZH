<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
    //分类管理的首页
    public function index(){
        //检查用户是否登录
        $this->isLogin();
        //登录成功后直接登录到分类管理页面
        return $this->redirect('cateList');
    }
    //分类列表
    public function cateList(){
        //检查用户是否登录
        $this->isLogin();
        //获取到所有分类
        $cateList = CateModel::all();//这个是一个对象数组，不是普通数组
        //数组模板变量
        $this->view->assign('title','分类管理');
        $this->view->assign('empty','<span style="color: red;">没有分类</span>');
        $this->view->assign('cateList',$cateList);
        //渲染分类模板输出
        return $this->view->fetch('catelist');
    }
    //渲染编辑分类的页面
    public function cateEdit(){
        //获取到分类的id
        $cateId = Request::param('id');
        //根据主键查询要更新的分类信息
        $cateInfo  = CateModel::where('id',$cateId)->find();
        //设置模板变量
        $this->view->assign('title','分类编辑');
        $this->view->assign('cateInfo',$cateInfo);
        //渲染模板输出
        return $this->view->fetch('cateedit');
    }
    //执行更新操作
    public function doEdit(){
        //获取到用户提交的数据
        $data = Request::param();
        //取出主键
        $id = $data['id'];
        //删除主键
        unset($data['id']);
        //执行更新操作
        if (CateModel::where('id',$id)->data($data)->update()){
            return $this->success('信息更新成功','catelist');
        }
        //更新失败
        $this->error('没有更新或更新失败');
    }
    //删除操作
    public function doDelete(){
        //获取要删除的主键id
        $id = Request::param('id');
        //执行删除
        if (CateModel::where('id',$id)->delete()){
            return $this->success('删除成功','cateList');
        }
        //删除失败
        $this->error('删除失败');
    }
    //渲染添加页面
    public function cateAdd(){
        return $this->view->fetch('cateadd',['title'=>'添加分类']);
    }
    //添加分类
    public function doAdd(){
        //获取用户要添加的数据
        $data = Request::param();
        //判断是否添加成功
        if (CateModel::create($data)){
            $this->success('添加分类成功','cateList');
        }
        //添加失败
        $this->error('添加分类失败');
    }
}