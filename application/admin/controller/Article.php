<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use think\facade\Request;
use think\facade\Session;
use app\admin\common\model\Cate;

class Article extends Base
{
    //文章管理的首页
    public function index(){
        //检查用户是否登录
        $this->isLogin();
        //登录成功后直接登录到文章管理页面
        return $this->redirect('artList');
    }
    //文章列表
    public function artList(){
        //检查用户是否登录
        $this->isLogin();
        //获取当前用户的id和用户级别
        $userId = Session::get('user_id');
        $isAdmin = Session::get('admin_level');
        //获取当前用户发布的文章
        $artList = ArtModel::where('user_id',$userId)->paginate(5);
        //如果是超级管理员，就显示所有文章
        if ($isAdmin==1){
            $artList = ArtModel::paginate(5);
        }

        //获取到所有文章
        //数组模板变量
        $this->view->assign('title','文章管理');
        $this->view->assign('empty','<span style="color: red;">没有文章</span>');
        $this->view->assign('artList',$artList);
        //渲染文章模板输出
        return $this->view->fetch('artList');
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
    //渲染文章编辑的页面
    public function artEdit(){
        //获取到文章的id
        $artId = Request::param('id');
        //根据主键查询要更新的文章信息
        $artInfo  = ArtModel::where('id',$artId)->find();
        //获取栏目信息
        $cateList = Cate::all();
        //设置模板变量
        $this->view->assign('title','文章编辑');
        $this->view->assign('artInfo',$artInfo);
        $this->view->assign('cateList',$cateList);
        //渲染模板输出
        return $this->view->fetch('artedit');
    }
    //处理文章的编辑操作
    public function doEdit()
    {
        //获取用户提交的数据
        $data = Request::param();
        //获取一下图片的上传信息
        $file = Request::file('title_img');
        //文件信息验证成功后，再上传到服务器上指定的文件夹，文件目录以public开始的
        $info = $file->validate([
            'size' => 10000000,
            'ext' => 'jpeg,png,gif,jpg',
        ])->move('uploads/');
        if ($info) {
            $data['title_img'] = $info->getSaveName();
        } else {
            $this->error($file->getError());
        }
//把数据写到数据表中
        if (ArtModel::update($data)) {
            $this->success('文章更新成功', 'artList');
        } else{
            $this->error('文章更新失败');
        }
    }
    //执行文章的上传操作
    public function doDelete(){
        //获取文章id
        $artId = Request::param('id');
        //删除并判断
        if (ArtModel::destroy($artId)){
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }
    }