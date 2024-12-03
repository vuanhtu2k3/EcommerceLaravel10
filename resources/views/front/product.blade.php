@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">{{ __('HOME') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">{{ __('SHOP') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                @include('front.account.common.message')
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">

                            @if ($product->product_images)
                                @foreach ($product->product_images as $key => $productImage)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }} ">
                                        <img class="w-100 h-100"
                                            src="{{ asset('uploads/product/large/' . $productImage->image) }}"
                                            alt="Image">
                                    </div>
                                @endforeach
                            @endif

                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>


                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="star-rating product mt-2" title="">
                                <div class="back-stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="pt-2 ps-1">(
                                {{ $product->product_ratings_count > 1 ? $product->product_ratings_count . 'Reviews' : $product->product_ratings_count . 'Review' }}
                                )</small>
                        </div>

                        @if ($product->compare_price > 0)
                            <h2 class="price text-secondary">
                                <del>{{ number_format($product->compare_price, 3, '.', '.') }}VND</del>
                            </h2>
                        @endif
                        <h2 class="price ">{{ number_format($product->price, 3, '.', '.') }} VND</h2>
                        <div class="colors">
                            <label class="text-uppercase p-2">Chọn màu:</label>
                            <div class="color">
                                @foreach ($product->colors as $color)
                                    <label class="color-item">
                                        <input type="radio" name="color" value="{{ $color->name }}" />
                                        <div class="color-box" style="background-color: {{ $color->name }};"></div>
                                        <span class="color-name">{{ ucfirst($color->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="sizes">
                            <label class="text-uppercase p-2 mt-2">Chọn Dung Lượng:</label>
                            <div class="size">
                                @foreach ($product->sizes as $size)
                                    <label class="size-item">
                                        <input type="radio" name="size" value="{{ $size->name }}" />
                                        <span class="size-box">
                                            {{ $size->name }}
                                            <p class="size-price">{{ number_format($size->price) }} VND</p>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>


                        <style>
                            .colors {
                                display: flex;
                                flex-direction: column;
                                gap: 10px;
                            }

                            .color {
                                display: flex;
                                gap: 15px;
                                align-items: center;
                            }

                            .color-item {
                                display: flex;
                                align-items: center;
                                cursor: pointer;
                            }

                            .color-box {
                                width: 40px;
                                height: 40px;
                                margin-right: 10px;
                                border-radius: 50%;
                                border: 2px solid #ccc;
                                background-color: #fff;
                                transition: border 0.3s, transform 0.3s;
                            }

                            .color-name,
                            .color-price {
                                font-size: 14px;
                                color: #666;
                            }

                            .color-price {
                                opacity: 0.6;
                            }

                            .color-item input[type="radio"] {
                                display: none;
                            }

                            .color-item input[type="radio"]:checked+.color-box {
                                border: 2px solid #4CAF50;
                                transform: scale(1.1);

                            }

                            .color-box:hover {
                                border: 2px solid #4CAF50;
                            }


                            .size-item {
                                display: inline-block;
                                margin-right: 10px;
                                text-align: center;
                            }

                            .size-box {
                                display: block;
                                width: 150px;
                                padding: 10px;
                                background-color: #f4f4f4;
                                border: 2px solid #ccc;
                                border-radius: 10px;
                                font-weight: bold;
                                margin-bottom: 20px;
                                color: #333;
                                cursor: pointer;
                                transition: background-color 0.3s, border 0.3s;
                                text-align: center;
                            }

                            .size-price {
                                font-size: 14px;
                                color: #666;
                                opacity: 0.5;
                                margin-top: 5px;
                            }

                            .size-item input[type="radio"] {
                                display: none;
                            }

                            .size-item input[type="radio"]:checked+.size-box {
                                background-color: gray;
                                color: white;
                                border: 2px solid gray;
                            }

                            .size-box:hover {
                                background-color: #e0e0e0;
                            }


                            .size-item input[type="radio"]:focus+.size-box {
                                border: 2px solid gray;
                            }
                        </style>

                        {!! $product->short_description !!}
                        <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark">
                            <i class="fas fa-shopping-cart"></i> &nbsp;
                            {{ __('Add To Cart') }}
                        </a>

                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    daa-bs-target="#description" type="button" role="tab"
                                    aria-controls="description" aria-selected="true">{{ __('Description') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab"
                                    data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping"
                                    aria-selected="false">{{ __('Shipping & Return') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">{{ __('Reviews') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                {!! $product->description !!}
                                {{-- <p>
                                    Lortem, ipsum dolor sit amet consectetur adipisicing elit. Sit, incidunt blanditiis
                                    suscipit quidem magnam doloribus earum hic exercitationem. Distinctio dicta veritatis
                                    alias delectus quaerat, quam sint ab nulla aperiam commodi. Lorem, ipsum dolor sit amet
                                    consectetur adipisicing elit. Sit, incidunt blanditiis suscipit quidem magnam doloribus
                                    earum hic exercitationem. Distinctio dicta veritatis alias delectus quaerat, quam sint
                                    ab nulla aperiam commodi. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit,
                                    incidunt blanditiis suscipit quidem magnam doloribus earum hic exercitationem.
                                    Distinctio dicta veritatis alias delectus quaerat, quam sint ab nulla aperiam commodi.
                                </p> --}}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                {!! $product->shipping_returns !!}
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="col-md-8">
                                    <div class="row">
                                        <form action="" name="productRatingForm" id="productRatingForm"
                                            method="post">
                                            <h3 class="h4 pb-3">{{ __('Write a Review') }}</h3>

                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name">{{ __('Name') }}</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Name">
                                                <p></p>
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" id="email"
                                                    placeholder="Email">
                                                <p></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="rating">{{ __('Rating') }}</label>
                                                <br>
                                                <div class="rating" style="width: 10rem">
                                                    <input id="rating-5" type="radio" name="rating"
                                                        value="5" /><label for="rating-5"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-4" type="radio" name="rating"
                                                        value="4" /><label for="rating-4"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-3" type="radio" name="rating"
                                                        value="3" /><label for="rating-3"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-2" type="radio" name="rating"
                                                        value="2" /><label for="rating-2"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-1" type="radio" name="rating"
                                                        value="1" /><label for="rating-1"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                </div>
                                                <p class="product-rating-error"></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">{{ __('Comment') }}</label>
                                                <textarea name="comment" id="comment" class="form-control" cols="30" rows="10" placeholder=""></textarea>
                                                <p></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-dark">{{ __('Submit') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="overall-rating mb-3">
                                        <div class="d-flex">
                                            <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                                            <div class="star-rating mt-2" title="">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>

                                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-2 ps-2">
                                                (
                                                {{ $product->product_ratings_count > 1 ? $product->product_ratings_count . 'Reviews' : $product->product_ratings_count . 'Review' }}
                                                )
                                            </div>
                                        </div>

                                    </div>
                                    @if ($product->product_ratings->isNotEmpty())
                                        @foreach ($product->product_ratings as $rating)
                                            @php
                                                $ratingPer = ($rating->rating * 100) / 5;
                                            @endphp
                                            <div class="rating-group mb-4">
                                                <span> <strong>{{ $rating->username }} </strong></span>
                                                <div class="star-rating mt-2" title="">
                                                    <div class="back-stars">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>

                                                        <div class="front-stars" style="width: {{ $ratingPer }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="my-3">
                                                    <p>{{ $rating->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>{{ __('Related Products') }}</h2>
                </div>
                <div class="col-md-12">
                    <div id="related-products" class="carousel">

                        @foreach ($relatedProducts as $relProduct)
                            @php
                                $productImage = $relProduct->product_images->first();
                            @endphp
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="" class="product-img">
                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                        @else
                                            <img class="card-img-top"
                                                src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                        @endif
                                    </a>
                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>
                                    <div class="product-action">
                                        @if ($relProduct->track_qty == 'Yes')
                                            @if ($relProduct->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $relProduct->id }});">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    Add To Cart
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Out Of Stock
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $relProduct->id }});">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif

                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $relProduct->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>{{ number_format($relProduct->price, 3, '.', '.') }}
                                                VND</strong></span>
                                        @if ($relProduct->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{ number_format($relProduct->compare_price, 3, '.', '.') }}
                                                    VND</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>

        </section>
    @endif
@endsection
@section('customJs')
    <script type="text/javascript">
        $("#productRatingForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('front.saveRating', $product->id) }}",
                type: "POST",
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {

                    if (response.status == true) {
                        window.location.href = "{{ route('front.product', $product->slug) }}";
                    } else {
                        var errors = response.errors;

                        if (errors.name) {
                            $("#name").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('');
                        }
                        if (errors.email) {
                            $("#email").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('');

                        }
                        if (errors.comment) {
                            $("#comment").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.comment)

                        } else {
                            $("#comment").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('')

                        }

                        if (errors.rating) {
                            $(".product-rating-error").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.rating)
                        } else {
                            $(".product-rating-error").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('')

                        }


                    }
                }

            });


        });
    </script>
@endsection
