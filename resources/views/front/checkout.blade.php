@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('HOME') }}</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('SHOP') }}</a></li>
                    <li class="breadcrumb-item">{{ __('Checkout') }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" id="orderForm" name="orderForm" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>{{ __('Shipping Address') }}</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="{{ __('First Name') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">

                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="{{ __('Last Name') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">{{ __('Select a Country') }}</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}
                                                            value="{{ $country->id }}"> {{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="{{ __('Address') }}"
                                                class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="{{ __('City') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="{{ __('Mobile') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="{{ __('Order Notes') }}"
                                                class="form-control"></textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>{{ __('Order Summary') }}</h2>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">{{ number_format($item->price * $item->qty, 3, '.', '.') }}
                                            VND</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>{{ __('Subtotal') }}</strong></div>
                                    <div class="h6">
                                        <strong>{{ number_format((float) Cart::subtotal() * 1000, 3, '.', '.') }}
                                            VND</strong></div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>{{ __('Discount') }}</strong></div>
                                    <div class="h6"><strong id="discount_value">{{ $discount }} %</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>{{ __('Shipping') }}</strong></div>
                                    <div class="h6"><strong
                                            id="shippingAmount">{{ number_format($totalShippingCharge, 3) }} VND</strong>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>{{ __('Total') }}</strong></div>
                                    <div class="h5"><strong
                                            id="grandTotal">{{ number_format($grandTotal, 3, '.', '.') }}
                                            VND</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="{{ __('Coupon Code') }}" class="form-control"
                                name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button"
                                id="apply_discount">{{ __('Apply Coupon') }}</button>
                        </div>
                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class=" mt-4" id="discount-response">
                                    <strong>{{ Session::get('code')->code }}</strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                                </div>
                            @endif
                        </div>


                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">{{ __('Payment Method') }}</h3>

                            <div>
                                <input checked type="radio" name="payment_method" value="cod"
                                    id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>
                            {{-- <div>
                                <input checked type="radio" name="payment_method" value="vnpay"
                                    id="payment_method_two">
                                <label for="payment_method_one" class="form-check-label">VN-Pay</label>
                            </div> --}}



                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">{{ __('Pay Now') }}</button>
                            </div>
                        </div>











                    </div>
                </div>
            </form>

        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#payment_method_one').on('click', function() {
                if ($(this).is(":checked") == true) {
                    $("#card-payment-form").addClass("d-none");
                }
            });

            $('#payment_method_two').on('click', function() {
                if ($(this).is(":checked") == true) {
                    $("#card-payment-form").removeClass("d-none");
                }
            });
        });
        $("#orderForm").submit(function(event) {
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('front.processCheckout') }}',
                type: 'POST',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled', false);

                    if (response.status == false) {
                        if (errors.first_name) {
                            $('#first_name')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.first_name)

                        } else {
                            $('#first_name')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.last_name) {
                            $('#last_name')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.last_name)

                        } else {
                            $('#last_name')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.email) {
                            $('#email')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email)

                        } else {
                            $('#email')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.country) {
                            $('#country')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.country)

                        } else {
                            $('#country')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.address) {
                            $('#address')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.address)

                        } else {
                            $('#address')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.city) {
                            $('#city')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.city)

                        } else {
                            $('#city')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.state) {
                            $('#state')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.state)

                        } else {
                            $('#state')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        if (errors.zip) {
                            $('#zip')
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.zip)

                        } else {
                            $('#zip')
                                .removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                    } else {
                        window.location.href = "{{ url('/thanks/') }}/" + response.orderId;
                    }


                }
            });

        });
        $("#country").change(function() {
            $.ajax({
                url: '{{ route('front.getOrderSummery') }}',
                type: 'post',
                data: {

                    country_id: $(this).val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html(response.shippingCharge + 'VND');
                        $("#grandTotal").html(response.grandTotal + 'VND');
                    }
                }
            });
        })

        $("#apply_discount").click(function() {
            $.ajax({
                url: '{{ route('front.applyDiscount') }}',
                type: 'post',
                data: {
                    code: $("#discount_code").val(),
                    country_id: $("#country").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html(response.shippingCharge + 'VND');
                        $("#grandTotal").html(response.grandTotal + 'VND');
                        $("#discount_value").html(response.discount + 'VND');
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        $("#discount-response-wrapper").html("<span class='text-danger'>" + response
                            .message + "</span>");

                    }
                }
            });
        });
        $('body').on('click', "#remove-discount", function() {
            $.ajax({
                url: '{{ route('front.removeCoupon') }}',
                type: 'post',
                data: {
                    country_id: $("#country").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html(response.shippingCharge + 'VND');
                        $("#grandTotal").html(response.grandTotal + 'VND');
                        $("#discount_value").html(response.discount + 'VND');
                        $("#discount-response").html('');
                        $("#discount_code").val('');

                    }
                }
            });
        });
    </script>
@endsection
