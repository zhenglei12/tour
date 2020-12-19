<?php


namespace App\Http\Constants;


class CodeMessageConstants
{
    const SUCCESS = ['code' => 0, 'message' => "操作成功"];
    const NAME_ERROR = ['code' => 1, 'message' => "账号或密码错误"];
    const NO_LOGIN = ['code' => 401, 'message' => "请先登陆"];
    const IS_ADMIN = ['code' => 2, 'message' => "不能操作系统管理员系统管理员"];
}
