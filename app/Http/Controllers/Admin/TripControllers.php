<?php


namespace App\Http\Controllers\Admin;


use App\Http\Model\Trip;
use App\Http\Services\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripControllers
{
    public function __construct(Request $request, TripService $tripService)
    {
        $this->request = $request;
        $this->tripService = $tripService;
    }

    /**
     * FunctionName：list
     * Description：列表
     * Author：cherish
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        $page = $this->request->input('page') ?? 1;
        $pageSize = $this->request->input('pageSize') ?? 10;
        $trip = new Trip();
        if ($this->request->input('name')) {
            $trip = $trip->where('name', 'like', "%" . $this->request->input('name') . "%");
        }
        if ($this->request->input('day')) {
            $trip = $trip->where('day', $this->request->input('day'));
        }
        return $trip->with('tripInfo')->paginate($pageSize, ['*'], "page", $page);
    }

    /**
     * FunctionName：detail
     * Description：行程详情
     * Author：cherish
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function detail()
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Trip())->getTable() . ',id'],
        ]);
        return Trip::with('tripInfo')->where('id', $this->request->input('id'))->first();
    }


    /**
     * FunctionName：add
     * Description：添加数据
     * Author：cherish
     * @return mixed
     */
    public function add()
    {
        $this->request->validate([
            'name' => "required",
            'day' => 'required',
            'area' => 'required',
            'info' => ['required', 'array'],
            'info.*.date' => 'required|date',
            'info.*.meal' => 'required',
            'info.*.stay' => 'required',
            'info.*.content' => 'required',
        ]);
        return DB::transaction(function () {
            $trip = Trip::create($this->request->input());
            $info = $this->request->input('info');
            foreach ($info as $v) {
                $trip->tripInfo()->create($v);
            }
            return;
        });
    }

    /**
     * FunctionName：update
     * Description：更新数据
     * Author：cherish
     * @param TripService $service
     * @return mixed
     */
    public function update(TripService $service)
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Trip())->getTable() . ',id'],
            'name' => "required",
            'day' => 'required',
            'area' => 'required',
            'info' => ['required', 'array'],
            'info.*.date' => 'required|date',
            'info.*.meal' => 'required',
            'info.*.stay' => 'required',
            'info.*.content' => 'required',
        ]);
        return DB::transaction(function () use ($service) {
            return $service->update($this->request->input());
        });
    }

    /**
     * FunctionName：delete
     * Description：删除
     * Author：cherish
     * @param TripService $service
     * @return mixed
     */
    public function delete(TripService $service)
    {
        $this->request->validate([
            'id' => ['required', 'exists:' . (new Trip())->getTable() . ',id'],
        ]);
        return DB::transaction(function () use ($service) {
            return $service->delete($this->request->input('id'));
        });
    }
}
