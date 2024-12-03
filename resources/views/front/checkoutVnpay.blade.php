@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('HOME') }}</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">{{ __('Shop Now') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Checkout VN-Pay') }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" id="orderForm" name="orderForm" method="post">
                @csrf
                {{-- <input type="hidden" name="total_vnpay"
                    value="{{ number_format((float) Cart::subtotal() * 1000, 3, '.', '.') }}"> --}}
                <input type="hidden" name="total_vnpay"
                    value="{{ is_numeric(Cart::subtotal()) ? number_format((float) Cart::subtotal() * 1000, 3, '.', '.') : '0.000' }}">

                <div class="row">
                    <div class="col-md-12">
                        <div class="sub-title mb-4">
                            <h2 class="text-center">{{ __('Shipping Address') }}</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="{{ __('First Name') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="{{ __('Last Name') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="{{ __('Mobile') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
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
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="{{ __('City') }}"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="{{ __('Order Notes') }}"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card payment-form">
                    <h3 class="card-title h5 mb-3">{{ __('Payment Method') }}</h3>

                    <div>
                        <input type="radio" name="payment_method" value="vnpay" id="payment_method_one" checked>
                        <label for="payment_method_one" class="form-check-label">VN-Pay</label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn-dark btn btn-block w-100">{{ __('Pay Now') }}</button>
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

        });
        $("#orderForm").submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize(); // Lấy toàn bộ dữ liệu form
            console.log(formData); // Kiểm tra xem dữ liệu có đầy đủ không

            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('front.checkoutVnpay') }}',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('button[type="submit"]').prop('disabled', false);
                    if (response.code === '00') {
                        window.location.href = response.data; // Chuyển hướng đến URL VNPay
                    } else {
                        alert("Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.");
                    }
                }
            });
        });
    </script>
@endsection
