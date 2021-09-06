<?php
namespace app\common\model;
//zh_user表的用户模型
use think\Model;

class User extends Model
{
    protected $pk = 'id';//指定主键
    protected $table = 'ch_user';//指定数据库表
    protected $autoWriteTimestamp = true;//开启自动时间戳
    protected $createTime = 'create_time';//创建时间
    protected $updateTime = 'update_time';//更新时间
    protected $dateFormat = 'Y年m月d日';//设置日期格式
    //获取器
    public function getStatusAttr($value){
        $status = ['1'=>'启用','0'=>'禁用'];
        return $status[$value];
    }
    public function getIsAdminAttr($value){
        $status = ['1'=>'管理员','0'=>'普通用户'];
        return $status[$value];
    }
    //修改器

}