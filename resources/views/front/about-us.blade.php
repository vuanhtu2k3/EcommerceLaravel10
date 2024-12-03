@extends('front.layouts.app')
@section('content')
    <section class="section-2 py-5">
        <div class="container">
            <div class="row mb-5">
                <!-- Ảnh về cửa hàng -->
                <div class="col-md-6">
                    <img src="{{ asset('front-assets/images/bg1.jpg') }}" class="w-100 mb-4"
                        alt="Không gian cửa hàng Anhtustore">
                </div>
                <div class="text col-md-6">
                    <span>{{ __('ABOUT-US') }}</span>
                    <h2>{{ __('Anhtustore - Bringing technology closer to you') }}</h2>
                    <p>
                        <strong>Anhtustore </strong>
                        {{ __('is where you find the latest, most prestigious technology products from the world leading brands. We are committed to providing genuine phones, along with dedicated, professional customer care services.') }}
                    </p>
                </div>
            </div>

            <!-- Dòng sản phẩm chính -->
            <div class="row mb-5">
                <div class="col-md-4">
                    <img src="{{ asset('uploads/product/small/49-32-1730884330.jpg') }}" class="w-100 mb-3"
                        alt="Dòng sản phẩm chính - Điện thoại di động">
                    <h5 class="text-center">{{ __('Featured product lines available in store') }}</h5>
                    <p class="text-center">
                        {{ __('From iPhone, Samsung to Xiaomi and many other brands, we always provide genuine, diverse and rich products.') }}

                    </p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('uploads/about-us/warranty_process.jpg') }}" class="w-100 mb-3"
                        alt="Dịch vụ bảo hành tận tâm">
                    <h5 class="text-center">{{ __('Warranty service and technical support') }}</h5>
                    <p class="text-center">
                        {{ __('Dedicated to each product, we provide reputable warranty service and quick technical support, helping you feel secure in using it.') }}

                    </p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('uploads/about-us/Fast-Delivery.jpg') }}" class="w-100 mb-3"
                        alt="Giao hàng nhanh chóng">
                    <h5 class="text-center">{{ __('Fast delivery nationwide') }}</h5>
                    <p class="text-center">
                        {{ __('With nationwide delivery service, Anhtustore is committed to bringing products to you in the fastest time.') }}

                    </p>
                </div>
            </div>

            <!-- Đội ngũ chăm sóc khách hàng -->
            <div class="row">
                <div class="col-md-6 ">
                    <h4 class="mb-4 text-uppercase">{{ __('Dedicated team, ready to support') }}</h4>
                    <p>
                        {{ __('We are proud to have a dedicated staff, always ready to listen and answer all customer questions. From product selection advice to technical support, Anhtustore is committed to accompanying you in every stage of product use.') }}

                    </p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('uploads/about-us/cskh.jpg') }}" class="w-100"
                        alt="Đội ngũ chăm sóc khách hàng Anhtustore">
                </div>
            </div>
        </div>
        <style>
            /* Style cho phần About Us */
            .section-2 {
                background-color: #f8f9fa;
                /* Màu nền sáng nhẹ cho toàn bộ phần */
                padding: 50px 0;
                font-family: Arial, sans-serif;
            }

            .section-2 .row.mb-5 .col-md-4 img {
                width: 90%;
                height: 250px;
                object-fit: contain;
                border-radius: 8px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                background-color: #f8f8f8;
            }

            .section-2 h2 {
                color: #333;
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .section-2 .text span {
                display: inline-block;
                color: #007bff;
                font-size: 1.2rem;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .section-2 .text p {
                color: #555;
                font-size: 1rem;
                line-height: 1.6;
            }

            .section-2 .text h4 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 30px;
                color: #333;
            }

            .section-2 img {
                border-radius: 8px;
                transition: transform 0.3s ease;
            }

            .section-2 img:hover {
                transform: scale(1.05);
                /* Hiệu ứng phóng to nhẹ khi hover ảnh */
            }

            .section-2 .text-center {
                color: #444;
                font-weight: 600;
                font-size: 1.1rem;
            }

            .section-2 p.text-center {
                font-size: 0.95rem;
                color: #666;
                line-height: 1.6;
            }

            .section-2 .col-md-4 h5 {
                color: #007bff;
                font-size: 1.2rem;
                margin-top: 15px;
                margin-bottom: 20px;
                text-transform: uppercase;
                font-weight: bold;
            }

            .section-2 .row {
                margin-bottom: 30px;
            }

            .section-2 .container {
                max-width: 1140px;
            }

            .section-2 .col-md-6 img {
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            }

            .section-2 .col-md-6 h2,
            .section-2 .col-md-4 h5 {
                border-left: 5px solid #007bff;
                padding-left: 10px;
            }

            .section-2 .col-md-6 p {
                margin-bottom: 20px;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .section-2 h2 {
                    font-size: 1.75rem;
                }

                .section-2 .col-md-4 h5 {
                    font-size: 1.1rem;
                }
            }
        </style>
    </section>
@endsection
