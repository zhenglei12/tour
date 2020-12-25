<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    protected $table = "salesman";

    public $fillable = [
        "name"
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
