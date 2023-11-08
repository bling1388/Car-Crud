<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CartItem;
use App\Models\Purchase;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function cart()
    {
        $cartItems = [];

        // Check if the user is logged in
        if (auth()->check()) {
            // Fetch cart items from the database
            $cartItems = auth()->user()->cartItems()->with('car')->get()->map(function ($cartItem) {
                // Decode the JSON string into a PHP array
                $tagNames = json_decode($cartItem->car->tags, true);

                // If the tags are stored as IDs and you need their names, you'd have to look them up:
                $tagNames = Subcategory::whereIn('id', $tagNames)->pluck('name')->toArray();

                // Convert the array of tag names into a string
                $cartItem->tagsString = implode('- ', $tagNames);

                return $cartItem;
            });
        } else {
            // Fetch cart items from session
            $sessionCart = session('cart', []);
            foreach ($sessionCart as $itemId => $itemDetails) {
                $car = Car::find($itemId);
                if ($car) {
                    $tagNames = Subcategory::whereIn('id', $itemDetails['tags'])
                        ->pluck('name')
                        ->toArray();


                    $cartItems[] = (object) [
                        'car_id' => $itemId,
                        'quantity' => $itemDetails['quantity'],
                        'brand' => $car->brand,
                        'model' => $car->model,
                        'reg_date' => $car->reg_date,
                        'eng_size' => $car->eng_size,
                        'price' => $car->price,
                        'photo' => $car->photo,
                        'tagsString' => implode('- ', $tagNames),

                    ];

                }
            }
        }
        return view('users.cart', compact('cartItems'));
    }


    public function showPurchasedCars()
    {
        $user = auth()->user();
        $purchasedCars = $user->purchases()->with('car')->get();

        // dd($purchasedCars);

        return view('users.purchased', compact('purchasedCars'));
    }

    public function purchase(Request $request)
    {


        $user = auth()->user();
        $carId = $request->input('car_id');


        // Create a new purchase record
        $purchase = new Purchase([
            'user_id' => $user->id,
            'car_id' => $carId,

        ]);

        // Save the purchase record to the database
        $purchase->save();

        // Redirect or return response
        return redirect('/purchased-cars')->with('success', 'Purchase successful.');
    }

    // Inside CartController.php
    public function addToCart(Request $request)
    {
        $carId = $request->input('car_id');
        $quantity = $request->input('quantity');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $price = $request->input('price');
        $reg_date = $request->input('reg_date');
        $eng_size = $request->input('eng_size');
        $photo = $request->input('photo');

        $tags = $request->input('tags');
        $tagsArray = json_decode($tags, true);

        $cart = session()->get('cart', []);

        // Check if the cart has an item with this car_id
        if (isset($cart[$carId])) {
            // Update the quantity if the item already exists in the cart
            $cart[$carId]['quantity'] += $quantity;
        } else {
            // Add a new item to the cart
            $cart[$carId] = [
                "brand" => $brand,
                "model" => $model,
                "price" => $price,
                "quantity" => $quantity,
                "reg_date" => $reg_date,
                "eng_size" => $eng_size,
                "photo" => $photo,
                "tags" => $tagsArray
            ];
        }

        // Update the session with the cart data
        session()->put('cart', $cart);

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => auth()->id(),
                // assumes you have user authentication in place
                'car_id' => $carId
            ],
            [
                'quantity' => $quantity,
                // This could be adjusted if you're summing quantities
                'brand' => $brand,
                'model' => $model,
                'price' => $price,
                'reg_date' => $reg_date,
                'eng_size' => $eng_size,
                'photo' => $photo,
                'tags' => $tagsArray // ensure your database column can store an array or convert it to JSON/string
            ]
        );

        // Check if you need to update the quantity rather than set a new one
        if (!$cartItem->wasRecentlyCreated) {
            $cartItem->increment('quantity', $quantity);
        }

        return response()->json([
            'car_id' => $carId,
            'quantity' => $cart[$carId]['quantity']
        ]);
    }

    public function quantity_update(Request $request)
    {
        $carId = $request->input('car_id');
        $newQuantity = $request->input('quantity');

        // Check if the user is authenticated
        if (Auth::check()) {
            // Handle the authenticated user's cart
            // Assuming you have a Cart model where you store cart items for users
            $userId = Auth::id();
            $cartItem = CartItem::where('user_id', $userId)->where('car_id', $carId)->first();

            if ($cartItem) {
                if ($newQuantity > 0) {
                    $cartItem->quantity = $newQuantity;
                    $cartItem->save();
                } else {
                    $cartItem->delete();
                }
            }

            // You might want to return the updated cart items for the user
            $updatedCart = CartItem::where('user_id', $userId)->get();

            return response()->json(['message' => 'Cart updated', 'cart' => $updatedCart]);
        } else {
            // If not authenticated, use session storage

            $cart = session()->get('cart', []);

            if (isset($cart[$carId])) {
                if ($newQuantity > 0) {
                    $cart[$carId]['quantity'] = $newQuantity;
                } else {
                    // If the quantity is 0, remove the item from the cart
                    unset($cart[$carId]);
                }

                session()->put('cart', $cart);
            }


            return response()->json(['message' => 'Cart updated', 'cart' => $cart]);
        }
    }



    public function deleteFromCart($car_id)
    {
        if (auth()->check()) {

            $cartItem = auth()->user()->cartItems()->where('car_id', $car_id)->first();
            if ($cartItem) {
                $cartItem->delete();
            }
        } else {
            // Remove the item from the session cart using 'car_id' as identifier
            $cart = session('cart', []);
            if (isset($cart[$car_id])) {
                unset($cart[$car_id]);
                session(['cart' => $cart]);
            }
        }
        return back()->with('success', 'Car removed from cart!');
    }


}
