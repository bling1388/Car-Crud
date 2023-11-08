<?php

namespace App\Models;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'brand', 'model', 'reg_date', 'eng_size', 'price', 'photo', 'tags', 'user_id', 'car_id'];

    protected $casts = [
        'tags' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class); // Assuming you have a Car model
    }
}
