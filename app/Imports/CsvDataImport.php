<?php
namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
class CsvDataImport implements ToModel
{
    public function model(array $row)
    {
    	$data[]=$row;
    	return $data;
    }
}