<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\ArtCate;
use app\common\model\Comment;
use think\facade\Request;
use think\Db;
use app\common\validate\Article;
class Index extends Base
{
    //首页
    public function index()
    {

        //全局查询条件
        $map = [];//将所有的查询条件封装到这个数组中
        //条件1：
        $map[] = ['status','=',1];//这里的等号不允许省略
        //实现搜索功能
        $keywords = Request::param('keywords');
        if (!empty($keywords)){
            //条件2：
            $map[] = ['title','like','%'.$keywords.'%'];
        }




        //分类标题信息显示
        //param直接获取本栏目的id
        $cateId = Request::param('cate_id');
        //如果存在这个分类id
        if(isset($cateId)){
            //条件三：
            $map[] = ['cate_id','=',$cateId];
            $res = ArtCate::get($cateId);
            $artList = Db::table('ch_article')
                ->where($map)
                ->order('create_time','desc')->paginate(3);
            $this->view->assign('cateName',$res->name);
        }else{
            $this->view->assign('cateName','首页');
            $artList = Db::table('ch_article')->where($map)->order('create_time','desc')->paginate(3);
        }
        //列表信息的分页显示
//        $artList = \app\common\model\Article::all(function($query) use ($cateId){
//            if (isset($cateId)) {
//                //paginate分页函数
//                $query->where('status',1)->where('cate_id', $cateId)->order('create_time','desc')->paginate(1);
//            }
//                $query->where('status',1)->order('create_time','desc')->paginate(1);
//            });
        $this->view->assign('empty',"<h2>暂时没有文章哦！</h2>");
        $this->view->assign('artList',$artList);
        //fetch的第一个参数为指定模板，第二个为传递值
        return $this->fetch('index',['title'=>'社区问答']);
    }
    //添加文章界面
    public function insert(){
        //1.登录才允许发布文章
        $this->isLogin();
        //2.设置页面标题
        $this->view->assign('title','发布文章');
        //3.获取栏目的信息
        $cateList = ArtCate::all();
        if (count($cateList)>0){
            //将查询到的栏目信息赋值给模板
            $this->assign('cateList',$cateList);
        }else{
            $this->error('请先添加栏目','index/index');
        }
        //4.发布界面
        return $this->view->fetch('insert');
    }
    //保存文章
    public function save(){
        //判断提交类型
        if (Request::isPost()){
        //获取用户提交的数据
        $data = Request::post();
        $res = $this->validate($data,'Article');
        if ($res!==true){
            //验证失败
            echo "<script>alert('".$res."');location.back()</script>";
        }else{
            //验证成功
            //获取一下上传图片信息
            $file = Request::file('title_img');
            //文件信息验证成功后，再上传到服务器上指定的文件夹，文件目录以public开始的
            $info = $file->validate([
                'size'=>10000000,
                'ext'=>'jpeg,png,gif,jpg',
            ])->move('uploads/');
            if ($info){
                $data['title_img'] = $info->getSaveName();
            }else{
                $this->error($file->getError());
            }
        }
        }else{
            $this->error('请求类型错误');
        }
        //把数据写到数据表中
        if (\app\common\model\Article::create($data)){
            $this->success('文章发布成功','index/index');
        }else{
            $this->error('文章发布失败');
        }
    }
    //文章详细页
    public function detail(){
        $artId = Request::param('id');
        $art  = \app\common\model\Article::get(function ($query) use ($artId){
           $query->where('id','=',$artId)->setInc('pv');
        });
        if (!is_null($art)){
            $this->view->assign('art',$art);
        }
        //添加评论
        $this->view->assign('commentList',Comment::all(function ($query) use($artId){
            $query->where('status',1)//状态为显示才允许显示
                ->where('art_id',$artId)//获取当前文档id
                ->order('create_time','desc');//获取评论在最前面
        }));
        $this->view->assign('title','详细页');
        return $this->view->fetch('detail');
    }
    //收藏
    public function fav(){
        if (!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求类型错误'];
        }
        //获取从前端传递过来的数据
        $data = Request::param();
        if (empty($data['session_id'])){
            return ['status'=>-2,'message'=>'请登录后再收藏'];
        }
        //查询条件
        $map[] = ['user_id','=',$data['user_id']];
        $map[] = ['article_id','=',$data['article_id']];
        $fav = Db::table('ch_user_fav')->where($map)->find();
        if (is_null($fav)){
            Db::table('ch_user_fav')->data([
                'user_id'=>$data['user_id'],
                'article_id'=>$data['article_id'],
            ])->insert();
            return ['status'=>1,'message'=>'收藏成功'];
        }else{
            Db::table('ch_user_fav')->where($map)->delete();
            return ['status'=>0,'message'=>'已取消收藏'];
        }

    }
    //点赞
    public function good(){
        if (!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求类型错误'];
        }
        //获取从前端传递过来的数据
        $da = Request::param();
        if (empty($da['session_id'])){
            return ['status'=>-2,'message'=>'请登录后再点赞'];
        }
        //查询条件
        $ma[] = ['user_id','=',$da['user_id']];
        $ma[] = ['article_id','=',$da['article_id']];
        $good = Db::table('ch_user_like')->where($ma)->find();
        if (is_null($good)){
            Db::table('ch_user_like')->data([
                'user_id'=>$da['user_id'],
                'article_id'=>$da['article_id'],
            ])->insert();
            return ['status'=>1,'message'=>'点赞成功'];
        }else{
            Db::table('ch_user_like')->where($ma)->delete();
            return ['status'=>0,'message'=>'已取消点赞'];
        }

    }
    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    //处理留言数据
    public function insertComment(){
        if (Request::isAjax()){
            //获取到评论
            $data = Request::param();
            //将用户留言存到表中
            if (Comment::create($data,true)){
                return ['status'=>1,'message'=>'评论发表成功'];
            }
            //发表失败
            return ['status'=>0,'message'=>'评论发表失败'];
        }
    }
    //处理留言数据
    public function loy(){

    }
}
