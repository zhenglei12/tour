<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class OrderTrip extends Model
{
    protected $table = "order_trip";

    public $fillable = [
        'ot_id',
        'order_id',
        'date',
        'meal',
        'stay',
        'content'
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
