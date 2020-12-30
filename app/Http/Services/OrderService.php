<?php


namespace App\Http\Services;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Model\Order;
use App\Http\Model\OrderStaff;
use App\Http\Model\OrderTrip;
use App\Http\Model\Trip;
use App\Http\Model\TripInfo;
use Illuminate\Support\Carbon;

class OrderService
{

    public function diffBetweenTwoDays($day1, $day2, $data)
    {
        $day = (floor((strtotime($day2) - strtotime($day1)) / 86400)) + 1;
        $count = count($data['trip_info']);
        if ($day - $count != 0) {
            throw \ExceptionFactory::business(CodeMessageConstants::CHECK_DAY);
        }

        return;
    }

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
        $this->diffBetweenTwoDays($data['up_group_date'], $data['off_group_date'], $data);
        $order = Order::create($data);
        $this->updateTO($order, $data);
        return $order;
    }

    /**
     * FunctionName：update
     * Description：更新
     * Author：cherish
     * @param $data
     */
    public function update($data)
    {
        $order = $this->checkOrderStatus($data['id']);
        $this->diffBetweenTwoDays($data['up_group_date'], $data['off_group_date'], $data);
        Order::where('id', $data['id'])->update(self::initData($data));
        $this->updateTO($order, $data);
        return;
    }

    /**
     * FunctionName：audit
     * Description：审核订单
     * Author：cherish
     * @param $data
     * @return mixed
     */
    public function audit($data)
    {
        $this->checkOrderStatus($data['id']);
        return Order::where('id', $data['id'])->update(['status' => $data['status']]);
    }

    /**
     * FunctionName：statistics
     * Description：统计
     * Author：cherish
     * @return mixed
     */
    public function statistics()
    {
        $data['count'] = Order::sum('tour_fee_amount');
        $data['month_count'] = Order::whereBetween('created_at', [date('Y-m-01'), date('Y-m-t')])->sum('tour_fee_amount');
        return $data;
    }

    /**
     * FunctionName：checkOrderStatus
     * Description：检查订单状态
     * Author：cherish
     * @param $id
     * @return mixed
     */
    private function checkOrderStatus($id)
    {
        $order = Order::find($id);
        if ($order['status'] == 1)
            throw \ExceptionFactory::business(CodeMessageConstants::ORDER_CHECK);
        return $order;
    }

    /**
     * FunctionName：updateTO
     * Description：更新数据
     * Author：cherish
     * @param $order
     * @param $data
     */
    private function updateTO($order, $data)
    {
        if (isset($data['trip_info']) && count($data['trip_info']) > 0) {
            OrderTrip::where('order_id', $order['id'])->delete();
            foreach ($data['trip_info'] as $v) {
                $order->orderT()->create($v);
            }
        }
        if (isset($data['order_staff']) && count($data['order_staff']) > 0) {
            OrderStaff::where('order_id', $order->id)->delete();
            foreach ($data['order_staff'] as $v) {
                $order->orderStaff()->create($v);
            }
        }
        return;
    }

    /**
     * FunctionName：initData
     * Description：初始化数据
     * Author：cherish
     * @param $data
     * @return array
     */
    private function initData($data)
    {
        $initData = [];
        $initData['enter_date'] = $data['enter_date'];
        $initData['name'] = $data['name'];
        $initData['enter_date'] = $data['enter_date'];
        $initData['t_id'] = $data['t_id'];
        $initData['vip_card'] = $data['vip_card'];
        $initData['tour_fee_amount'] = $data['tour_fee_amount'] ?? 0;
        $initData['deposit_amount'] = $data['deposit_amount'] ?? 0;
        $initData['rebate_amount'] = $data['rebate_amount'] ?? 0;
        $initData['balance_amount'] = $data['balance_amount'] ?? 0;
        $initData['collection_amount'] = $data['collection_amount'] ?? 0;
        $initData['numbers'] = $data['numbers'];
        $initData['meet_day'] = $data['meet_day'] ?? '';
        $initData['leave_day'] = $data['leave_day'] ?? '';
        $initData['leave_day'] = $data['leave_day'] ?? '';
        $initData['up_group_date'] = $data['up_group_date'] ?? '';
        $initData['off_group_date'] = $data['off_group_date'] ?? '';
        $initData['leave_number'] = $data['leave_number'] ?? '';
        $initData['remark'] = $data['remark'] ?? '';
        return $initData;
    }

    /**
     * FunctionName：getOrdersn
     * Description：生产订单号
     * Author：cherish
     * @return string
     */
    public function getOrdersn()
    {
        $ordersn = "121" . date('ymdHis') . mt_rand(1000, 9999);
        return $ordersn;
    }
}
