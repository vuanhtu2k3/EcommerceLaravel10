@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text"
                            href="{{ route('account.profile') }}">{{ __('My Account') }}</a></li>
                    <li class="breadcrumb-item">{{ __('My Orders') }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">

                        <!-- Order Info -->
                        <div class="order-info  p-4 bg-light mb-3 m-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6 class="text-muted">{{ __('Order ID:') }}</h6>
                                    <p>{{ $order->id }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted">{{ __('Order Time:') }}</h6>
                                    <p>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y H:i') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted"> {{ __('Delivery Time: ') }}</h6>
                                    <p>
                                        @if ($order->status == 'shipped' || $order->status == 'delivered')
                                            {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                        @else
                                            {{ __('Not available yet') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted">{{ __('Status: ') }}</h6>
                                    <p>
                                        @if ($order->status == 'pending')
                                            <span class="badge bg-danger">{{ __('Pending') }}</span>
                                        @elseif ($order->status == 'shipped')
                                            <span class="badge bg-info">{{ __('Shipped') }}</span>
                                        @elseif ($order->status == 'delivered')
                                            <span class="badge bg-success">{{ __('Delivered') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('Cancelled') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress-container mt-4 p-4 mb-4">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width:
            @if ($order->status == 'pending') 33%
            @elseif ($order->status == 'shipped')
                66%
            @elseif ($order->status == 'delivered')
                100%
             @elseif ($order->status == 'cancelled')
                100% @endif
            ;"
                                    aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <!-- Progress Icons -->
                            <div class="progress-icons d-flex justify-content-between mt-3">
                                <div class="text-center">
                                    <i
                                        class="fas fa-check-circle
                                        @if (
                                            $order->status == 'pending' ||
                                                $order->status == 'confirmed' ||
                                                $order->status == 'shipped' ||
                                                $order->status == 'delivered') text-primary
                                        @elseif($order->status == 'cancelled') text-danger
                                        @else
                                            text-secondary @endif
                                    "></i>
                                    <p class="small">{{ __('Pending') }}</p>
                                </div>

                                <div class="text-center">
                                    <i
                                        class="fas fa-truck
                                        @if ($order->status == 'shipped' || $order->status == 'delivered') text-primary
                                        @elseif($order->status == 'cancelled') text-danger
                                        @else
                                            text-secondary @endif
                                    "></i>
                                    <p class="small">{{ __('Shipped') }}</p>
                                </div>

                                <div class="text-center">
                                    <i
                                        class="fas fa-check-circle
                                        @if ($order->status == 'delivered') text-primary
                                        @elseif($order->status == 'cancelled') text-danger
                                        @else
                                            text-secondary @endif
                                    "></i>
                                    <p class="small">{{ __('Delivered') }}</p>
                                </div>

                                <!-- Trạng thái "Cancelled" -->
                                <div class="text-center">
                                    <i
                                        class="fas fa-times-circle
                                        @if ($order->status == 'cancelled') text-danger
                                        @else
                                            text-secondary @endif
                                    "></i>
                                    <p class="small">{{ __('Cancelled') }}</p>
                                </div>
                            </div>







                            <div class="card-footer p-3">

                                <!-- Heading -->
                                <h6 class="mb-7 h5 mt-4">{{ __('Order Items') }} ({{ $orderItemsCount }})</h6>

                                <!-- Divider -->
                                <hr class="my-3">

                                <!-- List group -->
                                <ul>
                                    @foreach ($orderItems as $item)
                                        <li class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-4 col-md-3 col-xl-2">
                                                    <!-- Image -->
                                                    @php
                                                        $productImage = getProductImage($item->product_id);
                                                    @endphp
                                                    @if (!empty($productImage->image))
                                                        <img class="img-fluid"
                                                            src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                                    @else
                                                        <img class="img-fluid"
                                                            src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <!-- Title -->
                                                    <p class="mb-4 fs-sm fw-bold">
                                                        <a class="text-body" href="product.html">{{ $item->name }} x
                                                            {{ $item->qty }}</a>
                                                        <br>
                                                        <span
                                                            class="text-muted">{{ number_format($item->total, 3, '.', '.') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <div class="card card-lg mb-5 mt-3">
                            <div class="card-body">
                                <!-- Heading -->
                                <h6 class="mt-0 mb-3 h5">{{ __('Order Total') }}</h6>

                                <!-- List group -->
                                <ul>
                                    <li class="list-group-item d-flex">
                                        <span>{{ __('Subtotal') }}</span>
                                        <span class="ms-auto">{{ number_format($order->subtotal, 3, '.', '.') }} VND</span>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <span>{{ __('Discount') }}{{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}</span>
                                        <span class="ms-auto">{{ number_format($order->discount, 3) }} VND</span>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <span>{{ __('Shipping') }}</span>
                                        <span class="ms-auto">{{ number_format($order->shipping, 3, '.', '.') }} VND
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex fs-lg fw-bold">
                                        <span>{{ __('Grand Total') }}</span>
                                        <span class="ms-auto">{{ number_format($order->grand_total, 3, '.', '.') }}
                                            VND</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
