<?php


namespace App\Http\Controllers\Admin;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Controllers\Controller;
use App\Http\Model\Resources;
use App\Http\Model\Salesman;
use App\Http\Services\ImportResouseService;
use App\Http\Services\ResouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourcesController extends Controller
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
        $res = new Resources();
        if ($this->request->input('name')) {
            $res = $res->where('name', 'like', "%" . $this->request->input('name') . "%");
        }
        return $res->where('man_name', '=', null)->paginate($pageSize, ['*'], "page", $page);
    }

    /**
     * FunctionName：distributeList
     * Description：历史分配列表
     * Author：cherish
     * @return mixed
     */
    public function distributeList()
    {
        $page = $this->request->input('page') ?? 1;
        $pageSize = $this->request->input('pageSize') ?? 10;
        $res = new Resources();
        if ($this->request->input('name')) {
            $res = $res->where('name', 'like', "%" . $this->request->input('name') . "%");
        }
        if ($this->request->input('man_name')) {
            $res = $res->where('man_name', 'like', "%" . $this->request->input('man_name') . "%");
        }
        return $res->where('man_name', '!=', null)->paginate($pageSize, ['*'], "page", $page);
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
            'id' => ['required', 'exists:' . (new Resources())->getTable() . ',id'],
        ]);
        return Resources::find($this->request->input('id'));
    }

    /**
     * FunctionName：add
     * Description：添加
     * Author：cherish
     * @return mixed
     */
    public function add()
    {
        $this->request->validate([
            'name' => ["required"],
            'phone' => 'required',
            'send_info' => 'required',
            'address' => 'required',
        ]);
        return Resources::create($this->request->input());

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
            'id' => ['required', 'exists:' . (new Resources())->getTable() . ',id'],
            'name' => ["required"],
            'phone' => 'required',
            'send_info' => 'required',
            'address' => 'required',
        ]);
        return Resources::where('id', $id)->update(self::initData($this->request->input()));


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
            'id' => ['required', 'exists:' . (new Resources())->getTable() . ',id'],
        ]);
        $id = $this->request->input('id');
        return Resources::where('id', $id)->delete();

    }

    /**
     * FunctionName：initData
     * Description：初始化数据
     * Author：cherish
     * @param $data
     * @return array
     */
    public function initData($data)
    {
        return $list = [
            "name" => $data['name'],
            "phone" => $data['phone'],
            "address" => $data['address'],
            "send_info" => $data['send_info'],
            "nickname" => $data['nickname'] ?? '',
            "man_name" => $data['nickname'] ?? '',
        ];
    }

    /**
     * FunctionName：import
     * Description：上传成功
     * Author：cherish
     * @param ResouseService $service
     * @return mixed
     */
    public function import(ResouseService $service)
    {
        $this->request->validate([
            'excel' => ['required', 'file']
        ]);
        $file = $this->request->file('excel');
        return DB::transaction(function () use ($service, $file) {
            return $service->importCreate($file);
        });

    }
}
