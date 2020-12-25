<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected  $table = "agent";

    public $fillable = [
        'name',
        'area',
        'phone',
        "shop_name",
        "address",
        "merchants_name"
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
