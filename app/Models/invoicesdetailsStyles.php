<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicesdetailsStyles extends Model
{
    use HasFactory;
    protected $fillabel=[
        'detail_id',
        'style_id',
        'quantity',
        'observation'
    ];
}
