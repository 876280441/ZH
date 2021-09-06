<?php
namespace app\common\controller;
use app\common\model\ArtCate;
use app\common\model\Article;
use think\Controller;
use think\facade\Session;
use think\facade\Request;
use app\admin\common\model\Site;
//基础控制器，必须继承于think\Controller
class Base extends Controller
{

    protected function initialize()
    {
        //调用分类导航，然后把导航显示出来
        $this->nav();
        //检测网站是否关闭
        $this->is_open();
        //获取右侧数据，就是排行榜
        $this->getHotArt();
    }
    //检查是否已登录：防止重复登录，放在验证方法中调用11
    protected function logined(){
        if (Session::has('user_id')){
            $this->error("你已登录，请勿重复登录!",'index/index');
        }
    }
    //检查是否登录，放在需要登录操作的方法前面
    public  function isLogin(){
        if (!Session::has('user_id')){
            $this->error("还未登录!",'index/index');
        }
    }
    //显示分类导航
    protected function nav(){
        //查询分类表获取到的所有分类信息
        $cateList = ArtCate::all(function ($query){
            $query->where('status',1)->order('sort','asc');
        });
        //将分类信息赋给模板，nav.html
        $this->view->assign('cateList',$cateList);
    }
    //检测站点是否关闭
    public function is_open(){
        //获取当前站点的状态
        $isOpen = Site::where('status',1)->value('is_open');
        //如果站点已经关闭，那我们只允许关闭前台，不允许关闭后台
        if ($isOpen==0&&Request::module()=='index'){
            //关闭网站
            $info = <<< 'INFO'
<body style="background-color: #333">
<h1 style="color: red;text-align: center;margin: 200px">站点维护中</h1>
</body>
INFO;
        exit($info);
        }
    }
    //检测注册是否关闭
    public function is_reg(){
        //当前注册状态
        $isReg = Site::where('status',1)->value('is_reg');
        //如果已经处于关闭状态下，直接跳到首页
        if ($isReg==0){
            $this->error('注册关闭','index/index');
        }
    }
    //首页排行页，根据pv来获取内容
    public function getHotArt(){
        $hotArtList = Article::where('status','1')->order('pv','desc')->limit(12)->select();
         $this->view->assign('hotArtList',$hotArtList);
    }
}