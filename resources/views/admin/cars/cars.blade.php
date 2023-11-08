@extends('layouts.admin.master')

@section('content')
    <style>
        .dropdown-toggle::after {
            /* Adjust size, e.g., smaller */
            height: 0.2em;
            width: 0.2em;
        }

        .dropdown-item {
            padding: 0.5rem 0.6rem;

        }
    </style>
    <div class="page-wrapper ">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">

                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <div class="card-header">
                                <h4>Cars</h4>
                            </div>
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Add
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="table_id">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">#id</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Registration date</th>
                                            <th>Engine size</th>
                                            <th>Price</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cars as $car)
                                            <tr>
                                                <td>{{ $car->id }}</td>
                                                <td>{{ $car->brand }}</td>
                                                <td>{{ $car->model }}</td>
                                                <td>{{ $car->reg_date }}</td>
                                                <td>{{ $car->eng_size }}</td>
                                                <td>{{ $car->price }}$</td>
                                                <td>
                                                    <img src="{{ asset('uploads/' . $car->photo) }}" alt="Car Photo"
                                                        style="width:100px; height:auto;">
                                                </td>

                                                <td class="d-flex">
                                                    <div>
                                                        @if ($car->status == 1)
                                                            <span class="btn btn-success btn-sm">Active</span>
                                                        @else
                                                            <span class="btn btn-danger btn-sm">Inactive</span>
                                                        @endif
                                                    </div>



                                                    <div class="dropdown ms-2">
                                                        <button class="btn btn-white dropdown-toggle btn-sm" type="button"
                                                            id="statusDropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false">

                                                        </button>

                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="statusDropdownMenuButton">
                                                            <li>
                                                                <form action="{{ route('cars.changeStatus', $car->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item">
                                                                        {{ $car->status == 1 ? 'Deactivate' : 'Activate' }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="d-flex space-x-2 justify-content-center">
                                                        <a href="{{ route('admin.car.edit', $car->id) }}"
                                                            class="btn btn-sm btn-primary me-2">
                                                            <i class="fas fa-edit"></i> Edit</a>
                                                        <form method="POST"
                                                            action="{{ route('admin.car.destroy', $car->id) }}"
                                                            onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger me-2"><i
                                                                    class="fas fa-trash"></i> Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal add cars --}}


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add a car</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.car.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand">

                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model">

                        </div>
                        <div class="mb-3">
                            <label for="reg_date" class="form-label">Registration date</label>
                            <input type="date" class="form-control" id="reg_date" name="reg_date">

                        </div>
                        <div class="mb-3">
                            <label for="eng_size" class="form-label">Engine size</label>
                            <input type="text" class="form-control" id="eng_size" name="eng_size">

                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($)</label>
                            <input type="integer" class="form-control" id="price" name="price">

                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">

                        </div>
                        <div class="mb-3 d-flex flex-column">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="js-example-basic-multiple" name="tags[]" multiple="multiple">
                                @foreach ($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach ($category->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection
