<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Model\User;
use Illuminate\Http\Request;
use App\Http\Model\Permission;

class PermissionControllers extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        $permission = Permission::where("guard_name", "admin");
        if ($this->request->input('alias')) {
            $permission->where('alias', $this->request->input('alias'));
        }
        return $permission->paginate($pageSize, ['*'], "page", $page);
    }

    /**
     * FunctionName：detail
     * Description：详情
     * Author：cherish
     * @return mixed
     */
    public function detail()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Permission())->getTable() . ',id'],
        ]);
        return Permission::find($this->request->input('id'));
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
            'name' => ['required', 'unique:' . (new Permission())->getTable() . ',name'],
            'alias' => ['required', 'unique:' . (new Permission())->getTable() . ',alias'],
        ]);
        return Permission::create([
                'name' => $this->request->input('name'),
                'guard_name' => 'admin',
                'alias' => $this->request->input('alias'),
            ]
        );
    }

    /**
     * FunctionName：update
     * Description：更新
     * Author：cherish
     * @return mixed
     */
    public function update()
    {
        $id = $this->request->input('id');
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Permission())->getTable() . ',id'],
            'name' => ['required', 'unique:' . (new Permission())->getTable() . ',name,' . $id],
            'alias' => ['required', 'unique:' . (new Permission())->getTable() . ',alias,' . $id],
        ]);
        return Permission::where('id', $id)->update([
                'name' => $this->request->input('name'),
                'alias' => $this->request->input('alias')
            ]
        );
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
            'id' => ['required', 'exists:' . (new Permission())->getTable() . ',id'],
        ]);
        return Permission::where('id', $this->request->input('id'))->delete();
    }

    /**
     * FunctionName：all
     * Description：所有权限
     * Author：cherish
     * @return mixed
     */
    public function all()
    {
        return Permission::get();
    }

}
