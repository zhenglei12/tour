<?php


namespace App\Http\Services;


use App\Http\Model\Trip;
use App\Http\Model\TripInfo;

class TripService
{
    /**
     * FunctionName：update
     * Description：更新数据
     * Author：cherish
     * @param $data
     */
    public function update($data)
    {
        $tripData = ['name' => $data['name'], 'area' => $data['area'], 'day' => $data['day']];
        Trip::where('id', $data['id'])->update($tripData);
        $this->deleteTripInfo($data['id']); //删除行程
        foreach ($data['info'] as $v) {  //再创建
            (Trip::find($data['id']))->tripInfo()->create($v);
        }
        return;
    }

    /**
     * FunctionName：delete
     * Description：删除数据
     * Author：cherish
     * @param $id
     */
    public function delete($id)
    {
        Trip::where('id', $id)->delete();
        $this->deleteTripInfo($id);
        return;
    }

    /**
     * FunctionName：deleteTripInfo
     * Description：删除行程详情数据
     * Author：cherish
     * @param $t_id
     * @return mixed
     */
    public function deleteTripInfo($t_id)
    {
        return TripInfo::where('t_id', $t_id)->delete();
    }
}
