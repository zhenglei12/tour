<?php


namespace App\Http\Services;


use App\Http\Model\Order;
use App\Http\Model\OrderStaff;
use App\Http\Model\TripInfo;

class OrderService
{
    /**
     * FunctionName：add
     * Description：添加
     * Author：cherish
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data['ordersn'] = $this->getOrdersn();
        $order = Order::create($data);
        if (isset($data['trip_info']) && count($data['trip_info']) > 0) {
            TripInfo::where('t_id', $data['t_id'])->delete();
            foreach ($data['trip_info'] as $v) {
                $order->orderTripInfo()->create($v);
            }
        }
        if (isset($data['order_staff']) && count($data['order_staff']) > 0) {
            OrderStaff::where('order_id', $order->id)->delete();
            foreach ($data['order_staff'] as $v) {
                $order->oderStaff()->create($v);
            }
        }
        return $order;
    }

    /**
     * FunctionName：getOrdersn
     * Description：生产订单号
     * Author：cherish
     * @return string
     */
    public function getOrdersn()
    {
       $ordersn =  "121" . date('ymdHis') . mt_rand(1000, 9999);
       return $ordersn;
    }
}
