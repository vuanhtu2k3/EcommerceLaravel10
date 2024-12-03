@extends('front.layouts.app')

@section('content')
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="500">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <picture>
                        <img src="{{ asset('front-assets/images/logodt.jpg') }}" alt="" />
                    </picture>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">{{ __('AnhTu Store') }}</h1>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">{{ __('Shop Now') }}</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <img src="{{ asset('front-assets/images/bg1.jpg') }}" alt="" />
                    </picture>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">{{ __('AnhTu Store') }}</h1>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">{{ __('Shop Now') }}</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <img src="{{ asset('front-assets/images/bg3.jpg') }}" alt="" />
                    </picture>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">{{ __('AnhTu Store') }}</h1>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">{{ __('Shop Now') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">{{ __('Quality Product') }}</h5>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">{{ __('Free Shipping') }}</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">{{ __('14-Day Return') }}</h2>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">{{ __('24/7 Support') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('CATEGORIES') }}</h2>
            </div>
            <div class="row pb-3">
                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <div class="col-lg-3">
                            <div class="cat-card">
                                <div class="left">
                                    @if ($category->image != '')
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt=""
                                            class="img-fluid">
                                    @endif
                                    {{-- <img src="images/cat-1.jpg" alt="" class="img-fluid"> --}}
                                </div>
                                <div class="right">
                                    <div class="cat-data">
                                        <h2>{{ $category->name }}</h2>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <section id="shop" class="w-200">
        <div class="row p-0 m-0">
            <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
                <img class="img-fluid" src="/uploads/banner/banner2.jpg">
                <div class="details">
                    <h2>{{ __('Phone Store') }}</h2>
                    <button class="text-uppercase rounded-2">{{ __('Shop Now') }}</button>
                </div>
            </div>
            <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
                <img class="img-fluid" src="/uploads/banner/banner3.jpg">
                <div class="details">
                    <h2>{{ __('Phone Store') }}</h2>
                    <button class="text-uppercase rounded-2">{{ __('Shop Now') }}</button>
                </div>
            </div>
            <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
                <img class="img-fluid" src="/uploads/banner/banner4.png">
                <div class="details">
                    <h2>{{ __('Phone Store') }}</h2>
                    <button class="text-uppercase rounded-2">{{ __('Shop Now') }}</button>
                </div>
            </div>
        </div>
    </section>
    <style>
        #shop .one img {
            width: 100%;
            height: 300px;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        #shop .one {
            position: relative;
        }

        #shop .one .details {
            top: 0;
            left: 0;
            transition: 0.4s ease;
            color: #FFFFFF;
            font-weight: bold;
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.7;
            background: #000000;
        }

        #shop .one .details:hover {
            background-color: cornsilk;
        }

        #shop :nth-child(1) .details {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;

        }

        #shop :nth-child(1) .details button {
            padding: 8px;
            margin-top: 10px;
            font-weight: bold;
        }

        #shop :nth-child(1) .details button:hover {
            background-color: chocolate;
        }

        #shop :nth-child(1) .img-fluid:hover {
            background-color: cornsilk;
        }

        #banner {
            background: url(/uploads/banner/bg4.jpg);
            width: 100%;
            height: 600px;
            background-position-x: center;
            background-position-y: 80px;
            background-size: 100%;
            background-attachment: fixed;
            background-repeat: no-repeat;
            flex-direction: column;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        #banner h1 {
            color: coral;
        }

        #banner button {
            background-color: coral;
            color: white;
        }

        #banner button:hover {
            background-color: #fff;
            color: black;
        }
    </style>


    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Featured Products') }}</h2>
            </div>
            <div class="row pb-3" id="featured-product-list">
                @foreach ($featuredProducts as $product)
                    <!-- Show only the first 4 products initially -->
                    @php
                        $productImage = $product->product_images->first();
                    @endphp
                    <div class="col-md-3 featured-product" style="display: {{ $loop->index < 4 ? 'block' : 'none' }}">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                    @if (!empty($productImage->image))
                                        <img class="card-img-top"
                                            src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                    @else
                                        <img class="card-img-top"
                                            src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                    @endif
                                </a>
                                <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                    href="javascript:void(0);">
                                    <i class="far fa-heart"></i>
                                </a>

                                <div class="product-action">
                                    @if ($product->track_qty == 'Yes')
                                        @if ($product->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i>{{ __('Add To Cart') }}
                                            </a>
                                        @else
                                            <a class="btn btn-dark"
                                                href="javascript:void(0);">{{ __('Out Of Stock') }}</a>
                                        @endif
                                    @else
                                        <a class="btn btn-dark" href="javascript:void(0);"
                                            onclick="addToCart({{ $product->id }});">
                                            <i class="fa fa-shopping-cart"></i> {{ __('Add To Cart') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body text-center mt-3">
                                <a class="h6 link"
                                    href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>{{ number_format($product->price, 3, '.', '.') }}
                                            VND</strong></span>
                                    @if ($product->compare_price > 0)
                                        <span class="h6 text-underline"><del>{{ number_format($product->compare_price, 3, '.', '.') }}
                                                VND</del></span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- See more button -->
            <button class="btn btn-primary text-center" id="seeMoreBtn"
                style="display: block; margin: 0 auto;">{{ __('See More Products') }}</button>

        </div>
    </section>

    <script>
        document.getElementById('seeMoreBtn').addEventListener('click', function() {
            const products = document.querySelectorAll('.featured-product');
            products.forEach(function(product) {
                product.style.display = 'block';
            });
            this.style.display = 'none';
        });
    </script>


    {{-- <div class="text-center pt-3">
        <!--Icon Left-->
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
            class="bi bi-arrow-left-circle-fill text-muted m-3" viewBox="0 0 16 16">
            <path
                d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
        <!--Icon Right-->
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
            class="bi bi-arrow-right-circle-fill text-muted m-3" viewBox="0 0 16 16">
            <path
                d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z" />
        </svg>

    </div> --}}







    <!--Mid Banner-->

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Latest Produsts') }}</h2>
            </div>
            <div class="row pb-3" id="latest-product-list">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3 latest-product" style="display: {{ $loop->index < 8 ? 'block' : 'none' }}">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        {{-- <img class="card-img-top"
                                    src="{{ asset('front-assets/images/product-1.jpg') }}"
                                    alt=""> --}}
                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                        @else
                                            <img class="card-img-top"
                                                src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                        @endif
                                    </a>
                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i>{{ __('Add To Cart') }}
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    {{ __(' Out Of Stock') }}
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> {{ __('Add To Cart') }}
                                            </a>
                                        @endif

                                    </div>
                                </div>

                                <div class="card-body text-center mt-3">
                                    <a class="h6 link"
                                        href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>{{ number_format($product->price, 3, '.', '.') }}
                                                VND</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{ number_format($product->compare_price, 3, '.', '.') }}
                                                    VND</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
            <button class="btn btn-primary text-center" id="seeMoreBtn1"
                style="display: block; margin: 0 auto;">{{ __('See More Products') }}</button>
        </div>
    </section>
    <script>
        document.getElementById('seeMoreBtn1').addEventListener('click', function() {
            // Show all products
            const products = document.querySelectorAll('.latest-product');
            products.forEach(function(product) {
                product.style.display = 'block';
            });

            // Hide the "See More" button after showing all products
            this.style.display = 'none';
        });
    </script>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('PHONE ACCESSORIES') }}</h2>
            </div>
            <div class="row pb-3">
                @if ($accessoryProducts->isNotEmpty())
                    @foreach ($accessoryProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        {{-- <img class="card-img-top"
                                    src="{{ asset('front-assets/images/product-1.jpg') }}"
                                    alt=""> --}}
                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                        @else
                                            <img class="card-img-top"
                                                src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                        @endif
                                    </a>
                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i>{{ __('Add To Cart') }}
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    {{ __(' Out Of Stock') }}
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> {{ __('Add To Cart') }}
                                            </a>
                                        @endif

                                    </div>
                                </div>

                                <div class="card-body text-center mt-3">
                                    <a class="h6 link"
                                        href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>{{ number_format($product->price, 3, '.', '.') }}
                                                VND</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{ number_format($product->compare_price, 3, '.', '.') }}
                                                    VND</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </section>

    <section class="section-5 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('BRAND WEEK') }}</h2>
            </div>
            <div class="img-banner">
                <img src="{{ asset('front-assets/images/banner9.png') }}" alt="">
            </div>
        </div>
    </section>
    <style>
        .section-5 .section-title {
            text-transform: uppercase;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-5 .img-banner {
            border-radius: 100px;
        }

        .section-6 .card-icon img {
            border-radius: 10px;
        }

        .suggestion-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 30px;

        }

        .tag {
            background-color: #ffffff;
            padding: 5px 20px;
            border-radius: 15px;
            font-size: 0.9em;
            color: #333;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .tag:hover {
            background-color: #f0f2f5;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }

        .text-limit {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 250px;

        }
    </style>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('SPECIAL OFFER TODAY') }}</h2>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='card shadow border-0 rounded-2'>
                        <div class='card-icon'>
                            <img src="{{ asset('front-assets/images/banner5.png') }}" width="200px" alt="Banner 5">
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card shadow border-0 '>
                        <div class='card-icon'>
                            <img src="{{ asset('front-assets/images/banner6.png') }}" width="200px" alt="Banner 6">
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card shadow border-0'>
                        <div class='card-icon'>
                            <img src="{{ asset('front-assets/images/banner7.png') }}" width="200px" alt="Banner 7">
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card shadow border-0 '>
                        <div class='card-icon'>
                            <img src="{{ asset('front-assets/images/banner8.png') }}" width="200px" alt="Banner 8">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-7 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('TECHNOLOGY NEWS') }}</h2>
            </div>
            <div class="container py-4" style="border: 2px solid #ddd; border-radius: 10px; padding: 20px;">
                <div class="row">
                    @if ($new_blogs->isNotEmpty())
                        @foreach ($new_blogs as $index => $blog)
                            <div class="col-sm-6 col-md-3 pb-4">
                                <div class="card shadow border-0">
                                    <div class="card-img-top">
                                        <td>
                                            @if (!empty($blog->image))
                                                <img src="{{ asset('uploads/news/thumb/' . $blog->image) }}"
                                                    alt="" class="img-fluid">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                    class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                    </div>
                                    <div class="card-body">
                                        <div class="pb-3">
                                            <p class="text-limit">
                                                {!! \Str::limit($blog->description, 50, '...') !!}
                                            </p>

                                        </div>
                                        <a href="{{ route('front.detailNew', $blog->id) }}" class="btn btn-primary"
                                            aria-label="Read more about {{ $blog->title }}">{{ __('READ MORE') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-12 text-center">
                            <h3>{{ __('No News Found') }}</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <section class="section-8 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('PEOPLE ALSO SEARCH') }}</h2>
            </div>
            <div class="search-suggestions">

                <div class="suggestion-tags bg-white">
                    <a href="http://127.0.0.1:8000/product/iphone-16-pro"><span class="tag">Iphone 16 Pro</span></a>
                    <a href="http://127.0.0.1:8000/product/iphone-15-promax"><span class="tag">Iphone 15
                            ProMax</span></a>
                    <a href="http://127.0.0.1:8000/product/iphone-14-promax"><span class="tag">Iphone 14
                            ProMax</span></a>
                    <a href="http://127.0.0.1:8000/product/xiaomi-redmi-note13-pro"><span class="tag">Redmi Note 13
                            Pro</span></a>
                    <a href="http://127.0.0.1:8000/product/redmi-k70"><span class="tag">Redmi K70</span></a>
                    <a href="http://127.0.0.1:8000/product/redmi-note-11"><span class="tag">Redmi Note 11</span></a>
                    <a href="http://127.0.0.1:8000/product/redmi-k60"><span class="tag">Redmi K60</span></a>
                    <a href="http://127.0.0.1:8000/product/xiao-mi-note-10"><span class="tag">Redmi Note 10</span></a>
                    <a href="http://127.0.0.1:8000/product/samsung-z-flip5"><span class="tag">SamSung
                            Z-Flip5</span></a>
                    <a href="http://127.0.0.1:8000/product/samsung-galaxy-s24-ultra"><span class="tag">SamSung Galaxy
                            S24 Ultra</span></a>
                    <a href="http://127.0.0.1:8000/product/samsung-z-fold-4"><span class="tag">Samsung
                            Z-fold-4</span></a>
                    <a href="http://127.0.0.1:8000/product/airpod-pro-3"><span class="tag">AirPod Pro 3</span></a>
                    <a href="http://127.0.0.1:8000/product/iphone-phone-case"><span class="tag">Iphone
                            phone-case</span></a>

                </div>
            </div>

        </div>

    </section>


@endsection
