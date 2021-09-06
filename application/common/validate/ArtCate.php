<?php
//ch_article表的验证器
namespace app\common\validate;
use think\Validate;

class ArtCate extends Validate
{

    protected $rule = [
        'name|标题'=>'require|length:2,20|chsAlpha',
        'cate_id|栏目作者'=>'require',
    ];
}