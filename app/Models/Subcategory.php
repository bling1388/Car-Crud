<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',


    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
