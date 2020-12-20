<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Model\Order;
use App\Http\Model\Trip;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $order = $order->where('name', $this->request->input('name'));
        }
        if ($this->request->input('status')) {
            $order = $order->where('status', $this->request->input('status'));
        }
        if ($this->request->input('vip_card')) {
            $order = $order->where('vip_card', $this->request->input('vip_card'));
        }
        return $order->select(['id', "t_id", "ordersn", "area", "up_group_date",
            "off_group_date", "vip_card", "numbers", "tour_fee_amount",
            "rebate_amount", "status", "name"])
            ->with(['orderTrip' => function ($query) {
                $query->select('id', 'name');
            }, 'oderStaff' => function($query){
                $query->select('order_id', 'name');
            }])->paginate($pageSize, ['*'], $page);
    }


    public function detail()
    {
    }

    public function update()
    {
    }

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
}
