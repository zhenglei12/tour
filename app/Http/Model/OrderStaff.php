<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class OrderStaff extends Model
{
    protected $table = "order_staff";

    public $fillable = [
        "order_id",
        "name",
        "id_crad",
        "phone",
        "type",
        "card_type"
    ];

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
