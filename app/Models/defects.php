<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class defects extends Model
{
    use HasFactory;
    protected $fillable=[
       'name',
       'user_id',
       'enterprise_id'
    ];
}
