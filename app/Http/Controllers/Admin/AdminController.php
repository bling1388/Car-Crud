<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;

use App\Models\User;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users_total = User::pluck('id')->count();
        $cars_total = Car::pluck('id')->count();
        $purchase_total = Purchase::pluck('id')->count();

        $users = User::all();

        return view('admin.dashboard.dashboard', compact('users_total', 'cars_total', 'purchase_total', 'users'));
    }

    public function user_edit(Request $request, $id)
    {

        $user = User::find($id);


        return view('admin.dashboard.user_edit', compact('user'));
    }

    public function user_update(Request $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;

        $user->update();

        return redirect('/admin/dashboard')->with('success', 'User updated Successfully');
    }

    public function user_destroy(Request $request, $id)
    {
        $user = User::find($id);

        $user->delete();

        return redirect()->back()->with('success', 'User was deleted successfully');
    }


    public function cars()
    {
        $cars = Car::all();
        $categories = Category::with('subcategories')->get();


        return view('admin.cars.cars', compact('cars', 'categories'));
    }


    public function car_store(Request $request)
    {

        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'reg_date' => 'required|date',
            'eng_size' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'mimes:jpg,jpeg,png|max:2048',
            'tags' => 'required|array',

        ]);

        $car = new Car;
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->reg_date = $request->reg_date;
        $car->eng_size = $request->eng_size;
        $car->price = $request->price;

        // 
        if ($request->file('photo')) {

            $request->validate([
                'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $file = $request->file('photo');

            $filename = rand(100000000, 100000000000) . '.' . $file->extension();
            $file->move(public_path('uploads'), $filename);
            $car->photo = $filename;
        }




        if ($request->has('tags')) {
            $car->tags = json_encode($request->input('tags'));
        }

        $car->save();

        return redirect()->back()->with('success', 'Car created succesfully');
    }

    public function car_edit(Request $request, $id)
    {

        $car = Car::find($id);


        return view('admin.cars.car_edit', compact('car'));
    }

    public function car_update(Request $request, $id)
    {
        $car = Car::find($id);

        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->reg_date = $request->reg_date;
        $car->eng_size = $request->eng_size;
        $car->price = $request->price;
        if ($request->file('photo')) {

            $request->validate([
                'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $file = $request->file('photo');
            $filename = random_int('100000000', '100000000000') . '.' . $file->extension();
            $file->move(public_path('uploads'), $filename);
            $car['photo'] = $filename;
        }
        // $car->status = $request->status;

        $car->update();

        return redirect('/admin/cars')->with('success', 'Car updated Successfully');
    }

    public function car_destroy(Request $request, $id)
    {
        $car = Car::find($id);

        $car->delete();

        return redirect()->back()->with('success', 'Car deleted successfully');
    }

    public function changeStatus(Request $request, $id)
    {
        $car = Car::find($id);

        // Toggle the status
        $car->status = $car->status == 0 ? 1 : 0;

        $car->save();


        return back()->with('success', 'Status updated successfully.');
    }


    public function categories()
    {
        $categories = Category::all();

        return view('admin.categories.categories', compact('categories'));
    }


    public function category_store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;

        $category->save();

        return redirect()->back()->with('success', 'Category created succesfully');
    }

    public function category_edit(Request $request, $id)
    {

        $category = Category::find($id);


        return view('admin.categories.category_edit', compact('category'));
    }

    public function category_update(Request $request, $id)
    {
        $category = Category::find($id);

        $category->name = $request->name;

        $category->update();

        return redirect('/admin/categories')->with('success', 'Category updated Successfully');
    }

    public function category_destroy(Request $request, $id)
    {
        $category = Category::find($id);

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    public function subcategories()
    {
        $subcategories = Subcategory::with('category')->get();
        $categories = Category::all();

        return view('admin.subcategories.subcategories', compact('subcategories', 'categories'));
    }


    public function subcategory_store(Request $request)
    {
        $subcategory = new Subcategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;

        $subcategory->save();

        return redirect()->back()->with('success', 'Subcategory created succesfully');
    }

    public function subcategory_edit(Request $request, $id)
    {

        $subcategory = Subcategory::find($id);
        $categories = Category::all();

        return view('admin.subcategories.subcategory_edit', compact('subcategory', 'categories'));
    }

    public function subcategory_update(Request $request, $id)
    {
        $subcategory = Subcategory::find($id);

        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;

        $subcategory->update();

        return redirect('/admin/subcategories')->with('success', 'Subcategory updated Successfully');
    }

    public function subcategory_destroy(Request $request, $id)
    {
        $subcategory = Subcategory::find($id);

        $subcategory->delete();

        return redirect()->back()->with('success', 'Subcategory deleted successfully');
    }
}
