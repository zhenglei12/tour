<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class TripInfo extends Model
{
    protected $table = "trip_info";

    public $fillable = [
        't_id',
        'date',
        'meal',
        'stay',
        'content'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
