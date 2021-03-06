<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\Order;
use App\Http\Model\OrderStaff;
use App\Http\Model\OrderTrip;
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
            'id', "t_id", "ordersn", "up_group_date", 'a_id', "area",
            "off_group_date", "vip_card", "numbers", "tour_fee_amount",
            "rebate_amount", "status", "name", "created_at", "enter_date"
        ])->with([
            'orderTrip' => function ($query) {
                $query->select('id', 'name', 'area');
            }, 'orderStaff' => function ($query) {
                $query->select('order_id', 'name', "id_crad");
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
     * FunctionName：edit
     * Description：编辑返利
     * Author：cherish
     * @return mixed
     */
    public function edit()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
            'rebate_amount' => 'required'
        ]);
        return Order::where('id', $this->request->input('id'))->update(['rebate_amount' => $this->request->input('rebate_amount')]);
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
        $order = new Order();
        $user = \Auth::user();
        if ($user->roles->pluck('alias')[0] == 'staff') {
            $order = $order->where('name', $user['name']);
        }
        if ($this->request->input('staff_name')) {
            $order = $order->where('name', $this->request->input('staff_name'));
        }
        $data['count'] = $order->sum('tour_fee_amount') + $order->sum('rebate_amount');
        $m = $order->whereBetween('created_at', [date('Y-m-01'), date('Y-m-t')])->sum('tour_fee_amount');
        $n = $order->whereBetween('created_at', [date('Y-m-01'), date('Y-m-t')])->sum('rebate_amount');
        $data['month_count'] = $m + $n;
        return $data;
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
        $order = Order::where('id', $this->request->input('id'))->with('orderStaff', 'orderTrip', 'orderT')->first();
        $staff = [];
        if ($order->orderStaff) {
            $staff = array_column($order->orderStaff->toArray(), 'name');
        }
        $filename = $staff[0] . ($order['enter_date']) . '.xls';
        return Excel::download(new ExportsOrderService($this->request->input('id')), $filename);
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
            'id' => ['required', 'exists:' . (new Order())->getTable() . ',id'],
        ]);
        return DB::transaction(function () {
            $id = $this->request->input('id');
            Order::where('id', $id)->delete();
            OrderStaff::where('order_id', $id)->delete();
            OrderTrip::where('order_id', $id)->delete();
            return;
        });

    }
}
