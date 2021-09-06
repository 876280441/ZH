<?php
namespace app\common\model;
//ch_article表的用户模型
use think\Model;

class Article extends Model
{
    protected $pk = 'id';//指定主键
    protected $table = 'ch_article';//指定数据库表
    protected $autoWriteTimestamp = true;//开启自动时间戳
    protected $createTime = 'create_time';//创建时间
    protected $updateTime = 'update_time';//更新时间
    protected $dateFormat = 'Y年m月d日';//设置日期格式

    //开启自动设置
    protected $auto = [];//无论是更新还是新增都要设置的字段
    //仅新增的有效
    protected $insert = ['create_time','status'=>1,'is_top'=>0,'is_hot'=>0];
    //仅更新时有效
    protected $update = ['update_time'];
}