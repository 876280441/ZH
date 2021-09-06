<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
use think\facade\Session;

class Site extends Base
{
    //站点的管理首页
    public function index(){
        //获取站点信息
        $siteInfo = SiteModel::get(['status'=>1]);
        //模板赋值
        $this->view->assign('siteInfo',$siteInfo);
        //渲染模板
        return $this->fetch('index');
    }
    //保存站点的修改信息
    public function save(){
        //获取到数据
        $data = Request::param();
        //更新数据
        if (SiteModel::update($data)){
            $this->success('更新网站信息成功!','index');
        }
        //更新失败
        $this->success('更新网站信息失败!','index');
    }
}