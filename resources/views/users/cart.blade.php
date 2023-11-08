@extends('layouts.front.master')
@section('content')
    <style>
        /* Default styles for larger screens */

        .page-title h4,
        .car-collections h1,
        .strapline-text {}

        .full-width-btn {
            width: 100%;
            padding-left: 0;
            padding-right: 0;
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

            .full-width-btn {
                width: 100%;
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between px-4 mb-3 header-container"
            style="background-color: rgba(255, 255, 255, 0.1)">
            <div class="text-white page-title">
                <h4>Shopping Cart</h4>
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

        <div class="row ">
            @foreach ($cartItems as $item)
                <div class="col-md-3">
                    <div class="card mt-2" style="width: 19rem;background-color: rgba(255, 255, 255, 0.3);">
                        <img src="{{ asset('uploads/' . $item->photo) }}" class="card-img-top">
                        <div class="card-body">
                            <h5>{{ $item->brand }} - {{ $item->model }}</h5>

                            <p class="card-text"><strong>Registration Date:</strong> {{ $item->reg_date }}</p>
                            <p class="card-text"><strong>Engine Size:</strong> {{ $item->eng_size }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                            <p class="card-text"><strong>Tags:</strong> {{ $item->tagsString }}</p>
                            <!-- Display the quantity -->
                            <p class="card-text"><strong>Quantity:</strong> {{ $item->quantity }}</p>
                        </div>
                        <div class="card-footer d-flex flex-column justify-content-center gap-2">

                            <button onclick="showUpdateModal({{ $item->car_id }}, {{ $item->quantity }})"
                                class="btn btn-info btn-sm">Update Quantity</button>

                            <form action="{{ route('cart.delete', $item->car_id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this item from the cart?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger full-width-btn btn-sm">Remove from
                                    Cart</button>

                            </form>

                            @if (auth()->check())
                                @if (auth()->user()->purchases()->where('car_id', $item->car_id)->exists())
                                    <button class="btn btn-secondary btn-sm" disabled>You already bought this car</button>
                                @else
                                    <div class="d-flex gap-2">
                                        <!-- Purchase Form -->
                                        <form action="{{ url('/purchase') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="car_id" value="{{ $item->car_id }}">
                                            <button type="submit"
                                                class="btn btn-primary btn-sm full-width-btn ">Buy</button>

                                        </form>
                                    </div>
                                @endif
                            @else
                                <!-- Trigger Sweet Alert if user is not logged in -->
                                <button type="button" class="btn btn-primary btn-sm px-5 full-width-btn "
                                    onclick="loginAlert()">
                                    Buy
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal fade" id="updateQuantityModal" tabindex="-1" role="dialog"
            aria-labelledby="updateQuantityModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateQuantityModalLabel">Update Quantity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="number" id="modalQuantityInput" value="1" min="1" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary " id="updateQuantityButton">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUpdateModal(carId, currentQuantity) {
            // Set the current quantity in the modal
            document.getElementById('modalQuantityInput').value = currentQuantity;

            // Save carId in the update button for later use
            document.getElementById('updateQuantityButton').setAttribute('data-car-id', carId);

            // Show the modal
            $('#updateQuantityModal').modal('show');
        }

        document.getElementById('updateQuantityButton').addEventListener('click', function() {
            var carId = this.getAttribute('data-car-id');
            var newQuantity = document.getElementById('modalQuantityInput').value;

            // Make the AJAX request to the server to update the quantity
            fetch('/update-cart-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        car_id: carId,
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide the modal
                    $('#updateQuantityModal').modal('hide');

                    // Show SweetAlert message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Car quantity updated!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // This will reload the page
                        }
                    })

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
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
