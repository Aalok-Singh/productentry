<?php

namespace App\Imports;

use App\Models\products;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new products([
            'name'     => $row[0],
            'title'    => $row[1], 
            'description' => $row[2],
            'price' =>$row[3]
        ]);
    }
}
