<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicesdetailsdefects extends Model
{
    use HasFactory;
    protected $fillabel=[
        'detail_id',
        'defect_id',
        'quantity',
        'observation'
    ];
}
