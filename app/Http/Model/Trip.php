<?php


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
  protected  $table = "trip";

  public $fillable = [
      'name',
      'area',
      'day'
  ];


  public function tripInfo()
  {
      return $this->hasMany(TripInfo::class, 't_id', 'id');
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
