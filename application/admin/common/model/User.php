<?php
namespace app\admin\common\model;
use think\Model;

class User extends Model
{
    protected $pk = 'id';
    protected $table = 'ch_user';
    protected $autoWriteTimestamp = true;
}
