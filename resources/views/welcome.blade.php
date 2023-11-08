@extends('layouts.front.master')

@section('content')
    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            /* Adjust the value as needed */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add a subtle shadow on hover */

        }

        /* Default styles for larger screens can go outside the media query */

        .header-container h4,
        .car-collections h1,
        .strapline-text {
            /* You can place your default font sizes here if you want */
        }

        /* Media query for mobile screens */
        @media (max-width: 768px) {
            .header-container h4 {
                /* Smaller font size for mobile */
                font-size: 1.2em;
                /* Adjust as needed */
            }

            .car-collections h1 {
                /* Smaller font size for mobile */
                font-size: 1.5em;
                /* Adjust as needed */
            }

            .strapline-text {
                /* Smaller font size for mobile */
                font-size: 0.6em;
                /* Adjust as needed */
            }
        }

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
                <h4>Cars Page</h4>
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
            @foreach ($cars as $car)
                <div class="col-md-3">
                    <div class="card mt-2" style="width: 19rem; background-color: rgba(255, 255, 255, 0.3);">
                        <img src="{{ asset('uploads/' . $car->photo) }}" class="card-img-top" alt="{{ $car->brand }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $car->brand }}</h5>
                            <p class="card-text"><strong>Model:</strong> {{ $car->model }}</p>
                            <p class="card-text"><strong>Registration Date:</strong> {{ $car->reg_date }}</p>
                            <p class="card-text"><strong>Engine Size:</strong> {{ $car->eng_size }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($car->price, 2) }}</p>
                            @if ($car->tagNames)
                                <p class="card-text"><strong>Tags:</strong> {{ $car->tagNames->join('- ') }}</p>
                            @endif
                            <p class="card-text"><strong>Quantity:</strong> {{ $quantities[$car->id] ?? 0 }}</p>
                            @if (auth()->check())
                                @if (auth()->user()->purchases()->where('car_id', $car->id)->exists())
                                    <button class="btn btn-secondary" disabled>You already bought this car</button>
                                @else
                                    <div class="d-flex gap-2">
                                        <!-- Purchase Form -->
                                        <form action="{{ url('/purchase') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                                            <button type="submit" class="btn btn-primary">Buy</button>
                                        </form>
                                        <!-- Add to Cart Button -->
                                        <button type="button" class="btn btn-outline-primary"
                                            data-car-id="{{ $car->id }}" data-car-brand="{{ $car->brand }}"
                                            data-car-model="{{ $car->model }}" data-car-price="{{ $car->price }}"
                                            data-car-photo="{{ $car->photo }}" data-car-reg_date="{{ $car->reg_date }}"
                                            data-car-eng_size="{{ $car->engine_size }}"
                                            data-car-tags='@json($car->tags)' onclick="addToCart(this)">Add to
                                            cart
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                                                <path
                                                    d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @else
                                <!-- Trigger Sweet Alert if user is not logged in -->
                                <button type="button" class="btn btn-primary" onclick="loginAlert()">
                                    Buy
                                </button>
                                <!-- Add to Cart Button -->
                                <button type="button" class="btn btn-outline-primary" data-car-id="{{ $car->id }}"
                                    data-car-brand="{{ $car->brand }}" data-car-model="{{ $car->model }}"
                                    data-car-price="{{ $car->price }}" data-car-photo="{{ $car->photo }}"
                                    data-car-reg_date="{{ $car->reg_date }}" data-car-eng_size="{{ $car->eng_size }}"
                                    data-car-tags="{{ json_encode($car->tags) }}" onclick="addToCart(this)">Add
                                    to cart
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17"
                                        fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                                        <path
                                            d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        function addToCart(button) {
            // Retrieve car data from data- attributes
            var carId = button.getAttribute('data-car-id');
            var carBrand = button.getAttribute('data-car-brand');
            var carModel = button.getAttribute('data-car-model');
            var carPrice = button.getAttribute('data-car-price');
            var carPhoto = button.getAttribute('data-car-photo');
            var carReg_date = button.getAttribute('data-car-reg_date');
            var carEng_size = button.getAttribute('data-car-eng_size');
            var carTags = JSON.parse(button.getAttribute('data-car-tags'));



            // Prepare the data to send to the server
            var data = {
                car_id: carId,
                brand: carBrand,
                model: carModel,
                price: carPrice,
                eng_size: carEng_size,
                photo: carPhoto,
                reg_date: carReg_date,
                tags: carTags,
                quantity: 1
            };

            // Make an AJAX request to your Laravel backend
            fetch('/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Item added to cart:', data);

                    var quantityElement = document.getElementById('quantity-' + data.car_id);
                    if (quantityElement) {
                        quantityElement.textContent = data.quantity;
                    }

                    Swal.fire({
                        title: 'Success!',
                        text: 'Car added to cart!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // This will reload the page
                        }
                    })
                })
                .catch((error) => {
                    console.error('Error:', error);

                    console.error('Error:', error);
                    // Optionally, show an error notification
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem adding the car to the cart.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                });
        }
    </script>
    <script>
        function loginAlert() {
            Swal.fire({
                title: 'Login Required',
                text: 'You need to be logged in to buy cars!',
                icon: 'warning',
                confirmButtonText: 'Login',
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        }
    </script>
@endsection
