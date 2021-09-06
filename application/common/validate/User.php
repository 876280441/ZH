<?php
//ch_user表的验证器
namespace app\common\validate;
use think\Validate;

class User extends Validate
{
    /*
    protected $rule = [
      'name|用户名'=>[
          'require'=>'require',
          'length'=>'5,20',
          'chsAlphaNum'=>'chsAlphaNum',//只允许汉字，字母，和数字
      ],
        'email|邮箱'=>[
            'require'=>'require',
            'email'=>'email',
            'unique'=>'ch_user',//该字段必须在ch_user表中是唯一的
        ],
        'moblie|手机号'=>[
            'require'=>'require',
            'moblie'=>'moblie',
            'unique'=>'ch_user',//该字段必须在ch_user表中是唯一的
            'number'=>'number',
        ],
        'password|密码'=>[
            'require'=>'require',
            'length'=>'6,20',
            'alphaNum'=>'alphaNum',//只允许字母+数字
            'confirm'=>'confirm',//自动与password_confirm字段进行自动相等验
        ],
    ];
    */
    protected $rule = [
      'name|用户名'=>'require|length:2,20|chsAlphaNum',
        'email|邮箱'=>'require|email|unique:ch_user',
        'mobile|手机号'=>'require|mobile|unique:ch_user',
        'password|密码'=>'require|length:6,20|alphaNum',
    ];
}