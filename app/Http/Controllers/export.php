<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

class export implements FromArray
{
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function array(): array
    {
        return $this->array;
    }
}
