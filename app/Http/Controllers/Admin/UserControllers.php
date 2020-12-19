<?php


namespace App\Http\Controllers\Admin;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Controllers\Controller;
use App\Http\Model\User;
use App\Http\Services\UserServices;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserControllers extends Controller
{
    public function __construct(Request $request, UserServices $services)
    {
        $this->request = $request;
        $this->services = $services;
    }

    /**
     * FunctionName：login
     * Description：登陆
     * Author：cherish
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => "required",
            "password" => "required"
        ]);
        return $this->services->login($this->request->input('name'), $this->request->input('password'));
    }

    /**
     * FunctionName：detail
     * Description：获取用户详情
     * Author：cherish
     * @return mixed
     */
    public function detail()
    {
        return \Auth::user();
    }
}
