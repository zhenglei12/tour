<?php


namespace App\Http\Controllers\Admin;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\Order;
use App\Http\Model\Resources;
use Illuminate\Http\Request;

class AgentControllers extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * FunctionName：orderlist
     * Description：订单列表
     * Author：cherish
     * @return mixed
     */
    public function orderlist()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Agent())->getTable() . ',id'],
        ]);
        $page = $this->request->input('page') ?? 1;
        $pageSize = $this->request->input('pageSize') ?? 10;
        $agent = Order::where('a_id', $this->request->input('id'));
        return $agent->paginate($pageSize, ['*'], "page", $page);
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
        $agent = new Agent();
        if ($this->request->input('name')) {
            $agent = $agent->where('name', 'like', "%" . $this->request->input('name') . "%");
        }
        return $agent->paginate($pageSize, ['*'], "page", $page);
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
            'id' => ['required', 'exists:' . (new Agent())->getTable() . ',id'],
        ]);
        return Agent::find($this->request->input('id'));
    }

    /**
     * FunctionName：add
     * Description：添加
     * Author：cherish
     */
    public function add()
    {
        $this->request->validate([
            'name' => ["required", 'unique:' . (new Resources())->getTable() . ',name'],
            'phone' => 'required',
            'area' => 'required',
            'shop_name' => 'required',
            'address' => 'required',
            'merchants_name' => 'required',
        ]);
        return Agent::create($this->request->input());
    }

    /**
     * FunctionName：update
     * Description：更新
     * Author：cherish
     */
    public function update()
    {
        $id = $this->request->input('id');
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Agent())->getTable() . ',id'],
            'name' => ["required", 'unique:' . (new Agent())->getTable() . ',name,' . $id],
            'phone' => 'required',
            'area' => 'required',
            'shop_name' => 'required',
            'address' => 'required',
            'merchants_name' => 'required',
        ]);
        Agent::where('id', $id)->update($this->request->input());
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
            'id' => ['required', 'exists:' . (new Agent())->getTable() . ',id'],
        ]);
        $id = $this->request->input('id');
        $order = Order::where('a_id', $id)->first();
        if ($order)
            throw \ExceptionFactory::business(CodeMessageConstants::NAME_ERROR);
        return Agent::where('id', $id)->delete();
    }
}
