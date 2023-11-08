@extends('layouts.admin.master')
@section('content')
    <div class="page-wrapper ">

        <div class="container-fluid">
            <form action="{{ route('admin.car.update', $car->id) }}" method="POST" class="form-horizontal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card p-5 container">
                    <div class="row mb-3">
                        <label for="brand" class="col-sm-2 col-form-label">Brand</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="brand" name="brand"
                                value="{{ $car->brand }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="model" name="model"
                                value="{{ $car->model }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="reg_date" class="col-sm-2 col-form-label">Registration date</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="reg_date" name="reg_date"
                                value="{{ $car->reg_date }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="eng_size" class="col-sm-2 col-form-label">Engine size</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="eng_size" name="eng_size"
                                value="{{ $car->eng_size }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="price" class="col-sm-2 col-form-label">Price ($)</label>
                        <div class="col-sm-10">
                            <input type="integer" class="form-control" id="price" name="price"
                                value="{{ $car->price }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="photo" name="photo"
                                value="{{ $car->photo }}"$>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
