<?php


namespace App\Http\Services;


use App\Http\Constants\CodeMessageConstants;
use App\Http\Model\Resources;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ResouseService
{
    /**
     * FunctionName：distribute
     * Description：分配
     * Author：cherish
     * @param $name
     * @param $row
     * @return mixed
     */
    public function distribute($name, $row)
    {
        $rowRe = Resources::where('man_name', '=', null)->count();
        if ($rowRe <= 0)
            throw \ExceptionFactory::business(CodeMessageConstants::R_NULL);
        if ($row > 1000)
            throw \ExceptionFactory::business(CodeMessageConstants::R_LIMIT_ROW);
        if (($rowRe - $row <= 0))
            throw \ExceptionFactory::business(['code' => 22, 'message' => "剩余可分配" . $rowRe]);
        $reData = Resources::where('man_name', '=', null)->limit($row)->get();
        $ids = array_column($reData->toArray(), 'id');
        return Resources::whereIn('id', $ids)->update(['man_name' => $name]);
    }

    /**
     * FunctionName：importCreate
     * Description：倒入创建数据
     * Author：cherish
     * @param $file
     */
    public function importCreate($file)
    {
        $sExtName = $file->getClientOriginalExtension();
        $sExt = strtolower($sExtName);
        if (!in_array($sExt, ['xls', 'csv', 'xlsx'])) {
            throw \ExceptionFactory::business(CodeMessageConstants::FILE_CHECK); //检查文件类型
        }
        try {
            $data = \Excel::toArray(new ImportResouseService(), request()->file('excel'));

        }catch (\Exception $e){
            throw \ExceptionFactory::business(CodeMessageConstants::FILE_ERROR); //检查文件类型
        }
        if (count($data[0]) <= 1)
            throw \ExceptionFactory::business(CodeMessageConstants::FILE_CHECK_ZERO);
        if (count($data[0]) > 2000)
            throw \ExceptionFactory::business(CodeMessageConstants::FILE_CHECK_SIZE);

        return $this->createData($data);
    }

    /**
     * FunctionName：createData
     * Description：处理数据
     * Author：cherish
     * @param $data
     */
    public function createData($data)
    {
        unset($data[0][0]);
        foreach ($data[0] as $key => $v) {
            if (!$v[0]) {  //跳过null数据
                continue;
            }
            $info[$key] = [
                "name" => $v[5],
                "nickname" => $v[4],
                "phone" => $v[6],
                "address" => $v[7],
                "send_info" => $v[10],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        Resources::insert($info);
        return;
    }
}
