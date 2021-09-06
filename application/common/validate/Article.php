<?php
//ch_article表的验证器
namespace app\common\validate;
use think\Validate;

class Article extends Validate
{

    protected $rule = [
        'title|标题'=>'require|length:2,20|chsAlphaNum',
        'content|文章内容'=>'require',
        'user_id|作者'=>'require',
        'cate_id|栏目作者'=>'require',
    ];
}