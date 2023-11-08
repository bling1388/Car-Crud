<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($credentials)) {
            // Retrieve the session cart
            $cart = session('cart', []);

            // Check if there is a session cart
            if (!empty($cart)) {
                // Iterate over the session cart items
                foreach ($cart as $itemId => $itemDetails) {
                    // Save cart items to the database associated with the authenticated user
                    auth()->user()->cartItems()->create([
                        'car_id' => $itemId,
                        'quantity' => $itemDetails['quantity'],
                        'brand' => $itemDetails['brand'] ?? null,
                        'model' => $itemDetails['model'] ?? null,
                        'reg_date' => $itemDetails['reg_date'] ?? null,
                        'eng_size' => $itemDetails['eng_size'] ?? null,
                        'price' => $itemDetails['price'] ?? null,
                        'photo' => $itemDetails['photo'] ?? null,
                        'tags' => $itemDetails['tags'] ?? null,
                    ]);
                }
                // Clear the session cart
                session()->forget('cart');
            }

            if (Auth::user()->role == 1) {
                return redirect('/admin/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        // Redirect back with an error if login fails
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }



    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the registration request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user and automatically log them in
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Auto-login the user
        auth()->login($user);

        // Check if there is a session cart and transfer items to the user's cart in the database
        if (session()->has('cart')) {
            $cart = session('cart');

            foreach ($cart as $itemId => $itemDetails) {
                // Create cart items associated with the user
                auth()->user()->cartItems()->create([
                    'car_id' => $itemId,
                    'quantity' => $itemDetails['quantity'],
                    'brand' => $itemDetails['brand'] ?? null,
                    'model' => $itemDetails['model'] ?? null,
                    'reg_date' => $itemDetails['reg_date'] ?? null,
                    'eng_size' => $itemDetails['eng_size'] ?? null,
                    'price' => $itemDetails['price'] ?? null,
                    'photo' => $itemDetails['photo'] ?? null,
                    'tags' => $itemDetails['tags'] ?? null,
                ]);
            }

            // Clear the session cart
            session()->forget('cart');
        }

        // Redirect with success message
        return redirect('/')->with('success', 'Registration successful. You are now logged in.');
    }



    public function logout(Request $request)
    {
        auth()->logout(); // Log the user out

        $request->session()->invalidate(); // Invalidate the session

        return redirect('/');
    }
}
