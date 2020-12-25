<?php


namespace App\Http\Constants;


class CodeMessageConstants
{
    const SUCCESS = ['code' => 0, 'message' => "操作成功"];
    const NAME_ERROR = ['code' => 1, 'message' => "账号或密码错误"];
    const NO_LOGIN = ['code' => 401, 'message' => "请先登陆"];
    const FORBIDDEN= ['code' => 40, 'message' => "您没有权限"];
    const IS_ADMIN = ['code' => 2, 'message' => "不能操作系统管理员系统管理员"];
    const ORDER_CHECK = ['code' => 3, 'message' => "不能操作已经审核的订单"];
    const TRIP_CHECK = ['code' => 4, 'message' => "行程下面有订单关联不能删除"];
    const AGENT_CHECK = ['code' => 4, 'message' => "代理下面有订单关联不能删除"];
}
