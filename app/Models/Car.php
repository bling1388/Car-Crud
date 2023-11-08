<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['brand', 'model', 'reg_date', 'eng_size', 'price', 'photo', 'tags'];


    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'car_subcategory');
    }


}
