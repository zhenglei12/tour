<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";

    public $fillable = [
        "enter_date",
        "name",
        'ordersn',
        'rebate_amount',
        "area",
        "t_id",
        "vip_card",
        "tour_fee_amount",
        "deposit_amount",
        "balance_amount",
        "collection_amount",
        "up_group_date",
        "off_group_date",
        "numbers",
        "meet_day",
        "meet_number",
        "leave_day",
        "leave_number",
        "remark",
        "status"
    ];

    public function oderStaff()
    {
        return $this->hasMany(OrderStaff::class, 'order_id', 'id');
    }

    public function orderTrip()
    {
        return $this->hasOne(Trip::class, "id", "t_id");
    }

    public function orderTripInfo()
    {
        return $this->hasMany(TripInfo::class, "t_id", "t_id");
    }

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
