<?php
namespace app\admin\common\model;
use think\Model;
class Cate extends Model
{
    protected $pk = 'id';
    protected $table = 'ch_article_category';
    protected $autoWriteTimestamp = true;
}