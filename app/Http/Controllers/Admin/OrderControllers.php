<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\Order;
use App\Http\Model\Trip;
use App\Http\Services\ExportsOrderService;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class OrderControllers extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function list()
    {
        $page = $this->request->input('page') ?? 1;
        $pageSize = $this->request->input('pageSize') ?? 10;
        $order = new Order();
        if ($this->request->input('name')) {
            $order = $order->where('name', 'like', "%" . $this->request->input('name') . "%");
        }
        if ($this->request->input('status')) {
            $order = $order->where('status', $this->request->input('status'));
        }
        if ($this->request->input('vip_card')) {
            $order = $order->where('vip_card', 'like', "%" . $this->request->input('vip_card') . "%");
        }
        return $order->select([
            'id', "t_id", "ordersn", "up_group_date",'a_id',
            "off_group_date", "vip_card", "numbers", "tour_fee_amount",
            "rebate_amount", "status", "name", "created_at"
        ])->with([
                'orderTrip' => function ($query) {
                    $query->select('id', 'name', 'area');
                }, 'orderStaff' => function ($query) {
                    $query->select('order_id', 'name');
                }, 'orderAgent' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->paginate($pageSize, ['*'], "page", $page);
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
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
        ]);
        return Order::where('id', $this->request->input('id'))->with('orderTrip', 'orderStaff', 'orderT', "orderAgent")->first();
    }

    /**
     * FunctionName：audit
     * Description：审核订单
     * Author：cherish
     * @param OrderService $service
     * @return mixed
     */
    public function audit(OrderService $service)
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
            'status' => ['required', Rule::in(['1', '-2'])],
        ]);
        return $service->audit($this->request->input());
    }

    /**
     * FunctionName：statistics
     * Description：统计
     * Author：cherish
     * @param OrderService $service
     * @return mixed
     */
    public function statistics(OrderService $service)
    {
        return $service->statistics();
    }

    /**
     * FunctionName：update
     * Description：更新
     * Author：cherish
     * @param OrderService $service
     * @return mixed
     */
    public function update(OrderService $service)
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
            "enter_date" => 'required|date',
            "name" => 'required',
            "area" => 'required',
            "up_group_date" => 'required|date',
            "t_id" => ['required', 'exists:' . (new Trip())->getTable() . ',id'],
            "vip_card" => 'required',
            "off_group_date" => 'required|date',
            "numbers" => 'required',
            'trip_info' => ['array'],
            'trip_info.*.date' => 'required|date',
            'trip_info.*.meal' => 'required',
            'trip_info.*.stay' => 'required',
            'trip_info.*.content' => 'required',
            'order_staff' => ['array'],
            'order_staff.*.id_crad' => 'required',
            'order_staff.*.phone' => 'required',
            'order_staff.*.type' => 'required',
            'order_staff.*.name' => 'required',
            'order_staff.*.card_type' => 'required',
        ]);
        return DB::transaction(function () use ($service) {
            return $service->update($this->request->input());
        });
    }

    /**
     * FunctionName：add
     * Description：创建
     * Author：cherish
     * @param OrderService $service
     * @return mixed
     */
    public function add(OrderService $service)
    {
        $this->request->validate([
            "enter_date" => 'required|date',
            "name" => 'required',
            "area" => 'required',
            "up_group_date" => 'required|date',
            "t_id" => ['required', 'exists:' . (new Trip())->getTable() . ',id'],
            "a_id" => ['required', 'exists:' . (new Agent())->getTable() . ',id'],
            "vip_card" => 'required',
            "off_group_date" => 'required|date',
            "numbers" => 'required',
            'trip_info' => ['array'],
            'trip_info.*.date' => 'required|date',
            'trip_info.*.meal' => 'required',
            'trip_info.*.stay' => 'required',
            'trip_info.*.content' => 'required',
            'order_staff' => ['array'],
            'order_staff.*.id_crad' => 'required',
            'order_staff.*.phone' => 'required',
            'order_staff.*.type' => 'required',
            'order_staff.*.name' => 'required',
            'order_staff.*.card_type' => 'required',
        ]);
        return DB::transaction(function () use ($service) {
            return $service->add($this->request->input());
        });
    }

    /**
     * FunctionName：exports
     * Description：导出
     * Author：cherish
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exports()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
        ]);
        return Excel::download(new ExportsOrderService($this->request->input('id')), '计划确认书' . date('Y:m:d') . '.xls');
    }
}
