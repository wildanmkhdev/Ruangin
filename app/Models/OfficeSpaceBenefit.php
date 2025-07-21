<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeSpaceBenefit extends Model
{
    //
    use HasFactory, SoftDeletes;
    // penting kalau pakai softdelets di maigration nya

    protected $fillable   = [
        'name',
        'office_space_id',


    ];
}
