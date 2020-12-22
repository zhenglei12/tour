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


}
