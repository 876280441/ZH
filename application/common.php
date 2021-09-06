<?php
use think\Db;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//根据文章表的用户主键，查询用户名
if (!function_exists('getUserName')){
    function getUserName($id){
        return Db::table('ch_user')->where('id',$id)->value('name');
    }
}
//过滤文章摘要
function getArtContent($content){
    return mb_substr(strip_tags($content),0,40).'...查看更多';
}
if(!function_exists('getCateName')){
    function getCateName($id){
        return Db::table('ch_article_category')->where('id',$id)->value('name');
    }
}
//详细页的文章
function getArtContents($content){
    return mb_substr(strip_tags($content),0,4000);
}
