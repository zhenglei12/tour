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

}
