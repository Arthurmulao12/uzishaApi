<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicesdetailsreasons extends Model
{
    use HasFactory;
    protected $fillabel=[
        'detail_id',
        'reason_id',
        'quantity',
        'observation'
    ];
}
