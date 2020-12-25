<?php


namespace App\Http\Services;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportResouseService implements ToArray
{
    use Importable;

    public function array(array $array)
    {
        return $array;
    }
}
