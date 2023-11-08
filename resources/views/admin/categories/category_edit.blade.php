@extends('layouts.admin.master')
@section('content')
    <div class="page-wrapper ">

        <div class="container-fluid">
            <form action="{{ route('admin.category.update', $category->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="card p-5 container">
                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $category->name }}">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
