<?php


namespace App\Http\Controllers\Admin;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Controllers\Controller;
use App\Http\Model\User;
use App\Http\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserControllers extends Controller
{
    public function __construct(Request $request, UserServices $services)
    {
        $this->request = $request;
        $this->services = $services;
    }

    /**
     * FunctionName：list
     * Description：列表
     * Author：cherish
     * @return mixed
     */
    public function list()
    {
        $page = $this->request->input('page') ?? 1;
        $pageSize = $this->request->input('pageSize') ?? 10;
        $user = new User();
        if ($this->request->input('name')) {
            $user->where('name', $this->request->input('name'));
        }
        return $user->paginate($pageSize, ['*'], $page);
    }

    /**
     * FunctionName：personalDetail
     * Description：用户详情
     * Author：cherish
     * @return mixed
     */
    public function personalDetail()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new User())->getTable() . ',id'],
        ]);
        return User::find($this->request->input('id'));
    }

    /**
     * FunctionName：update
     * Description：更新
     * Author：cherish
     * @return mixed
     */
    public function update()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new User())->getTable() . ',id'],
            'name' => ['unique:' . (new User())->getTable() . ',name'],
            'email' => ['unique:' . (new User())->getTable() . ',email']
        ]);
        $data['name'] = $this->request->input('name');
        if ($this->request->input('password'))
            $data['password'] = Hash::make($this->request->input('password'));
        return User::where('id', $this->request->input('id'))->update($data);
    }

    /**
     * FunctionName：add
     * Description：创建
     * Author：cherish
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function add()
    {
        $this->request->validate([
            'name' => ['required', 'unique:' . (new User())->getTable() . ',name'],
            'password' => 'required',
            'email' => ['required', 'unique:' . (new User())->getTable() . ',email']
        ]);
        return User::create([
            'name' => $this->request->input('name'),
            'password' => Hash::make($this->request->input('password')),
            'email' => $this->request->input('email')
        ]);
    }

    /**
     * FunctionName：delete
     * Description：删除
     * Author：cherish
     * @return mixed
     */
    public function delete()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new User())->getTable() . ',id'],
        ]);
        return User::where('id', $this->request->input('id'))->delete();
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
            'username' => "required",
            "password" => "required"
        ]);
        return $this->services->login($this->request->input('username'), $this->request->input('password'));
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

    /**
     * FunctionName：roleList
     * Description：用户所属
     * Author：cherish
     * @return mixed
     */
    public function roleList()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new User())->getTable() . ',id'],
        ]);
        $user = User::findOrFail($this->request->input('id'));
        return $user->roles;
    }

    /**
     * FunctionName：addRole
     * Description：添加角色
     * Author：cherish
     */
    public function addRole()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new User())->getTable() . ',id'],
        ]);
        $user = User::findOrFail($this->request->input('id'));
        $user->syncRoles($this->request->input('roles', []));
        return;
    }

    /**
     * FunctionName：permission
     * Description：获取登陆用户的权限
     * Author：cherish
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission()
    {
        return response()->json(['data' => \Auth::user()->getAllPermissions()]);
    }
}
