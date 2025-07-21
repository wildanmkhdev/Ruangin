<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory, SoftDeletes;
    // penting kalau pakai softdelets di maigration nya

    protected $fillable = [
        'name',
        'slug',
        'photo',


    ];
    // function buat slug otomatis
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value; //office bwa
        $this->attributes['slug'] = Str::slug($value); //office-bwa
    }
    //
    public function officeSpaces(): HasMany

    {
        return $this->hasMany(OfficeSpace::class,'city_id');
    }
}
