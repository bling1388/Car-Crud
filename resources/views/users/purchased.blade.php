@extends('layouts.front.master')

@section('content')
    <style>
        /* Default styles for larger screens */

        .page-title h4,
        .car-collections h1,
        .strapline-text {
            /* Default font sizes here */
        }

        /* Media query for mobile screens */
        @media (max-width: 768px) {
            .page-title h4 {
                /* Smaller font size for mobile */
                font-size: 1em;

            }

            .car-collections h1 {
                /* Smaller font size for mobile */
                font-size: 1.2em;

            }

            .strapline-text {
                /* Smaller font size for mobile */
                font-size: 5px;

            }
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between px-4 mb-3 header-container"
            style="background-color: rgba(255, 255, 255, 0.1)">
            <div class="text-white page-title">
                <h4>Purchase History</h4>
            </div>
            <div class="text-end car-collections">
                <h1 class="text-white fw-bold">Car Collections</h1>
                <div class="strapline">
                    <p class="text-white text-uppercase strapline-text" style="font-size: 0.7rem">Made for People Buying Cars™
                    </p>
                </div>
                <h2></h2>
            </div>
        </div>

        <div class="row">
            @foreach ($purchasedCars as $purchase)
                @php
                    $car = $purchase->car;
                    $tagIds = json_decode($car->tags); // Convert the JSON array to a PHP array
                    $tagNames = \App\Models\Subcategory::whereIn('id', $tagIds)
                        ->pluck('name')
                        ->implode(', '); // Get the tag names based on IDs
                @endphp
                <div class="col-md-3">
                    <div class="card mt-2" style="width: 19rem;background-color: rgba(255, 255, 255, 0.3);">
                        <img src="{{ asset('uploads/' . $car->photo) }}" class="card-img-top" alt="{{ $car->brand }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $car->brand }}</h5>
                            <p class="card-text"><strong>Model:</strong> {{ $car->model }}</p>
                            <p class="card-text"><strong>Registration Date:</strong> {{ $car->reg_date }}</p>
                            <p class="card-text"><strong>Engine Size:</strong> {{ $car->eng_size }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($car->price, 2) }}</p>

                            @if (!empty($tagNames))
                                <p class="card-text"><strong>Tags:</strong> {{ $tagNames }}</p>
                            @endif
                            <button class="btn btn-secondary" disabled>You purchased this on
                                {{ $purchase->created_at->format('Y-m-d') }}</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
