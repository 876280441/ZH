<?php
namespace app\admin\common\model;
use think\Model;
class Article extends Model
{
    protected $pk = 'id';
    protected $table = 'ch_article';
    protected $autoWriteTimestamp = true;
}