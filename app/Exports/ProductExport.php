<?php

namespace App\Exports;

use App\Models\products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return products::all();
    }
    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'title',
            'description',
            'price',
            'created_at',
            'updated_at',
        ];
    }
}
