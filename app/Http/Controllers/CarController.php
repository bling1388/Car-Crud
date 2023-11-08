<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    public function cars(Request $request)
    {
        $query = Car::where('status', 1);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('brand', 'LIKE', "%{$search}%")
                ->orWhere('model', 'LIKE', "%{$search}%")
                ->orWhere('eng_size', 'LIKE', "%{$search}%")
                ->orWhere('price', $search);
        }

        $cars = $query->get();


        // Fetch quantities for each car.
        $quantities = [];
        if (auth()->check()) {
            // If user is authenticated, get quantities from user's cart.
            $userCartItems = auth()->user()->cartItems;
            foreach ($userCartItems as $item) {
                $quantities[$item->car_id] = $item->quantity;
            }
        } else {
            // If not authenticated, get quantities from session.
            $sessionCart = session('cart', []);
            foreach ($sessionCart as $carId => $item) {
                $quantities[$carId] = $item['quantity'];
            }
        }

        // Fetch tags for each car.
        foreach ($cars as $car) {
            // Decode the JSON tags from the car's tags column
            $tagIds = json_decode($car->tags);

            // Fetch the tag names using the decoded IDs.
            $car->tagNames = Subcategory::whereIn('id', $tagIds)->pluck('name');
        }


        return view('welcome', compact('cars', 'quantities'));

    }


}
