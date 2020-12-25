<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Resources extends Model
{
    protected $table = "resources";

    public $fillable = [
        "nickname",
        "name",
        "phone",
        "address",
        "send_info",
        "man_name",
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
