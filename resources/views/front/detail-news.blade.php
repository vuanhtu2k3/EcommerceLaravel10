@extends('front.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-4">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h1 class="text-uppercase text-center blog-title">{{ $new->title }}</h1>
                    <p class="text-center">Ngày đăng:
                        {{ \Carbon\Carbon::parse($new->published_at)->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card blog-card">
                <div class="card-body">
                    <style>
                        /* Định dạng tổng thể của thẻ tiêu đề */
                        .blog-title {
                            font-size: 2rem;
                            font-weight: bold;
                            color: #333;
                            margin-bottom: 20px;
                        }

                        /* Định dạng cho container của blog */
                        .blog-card {
                            padding: 30px;
                            background-color: #f9f9f9;
                            border: none;
                            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                        }

                        /* Căn giữa hình ảnh và tạo hiệu ứng */
                        .image-container {
                            display: flex;
                            justify-content: center;
                            padding: 15px;
                        }

                        .image-container img {
                            max-width: 50%;
                            border-radius: 8px;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                            transition: transform 0.3s ease;
                        }

                        .image-container img:hover {
                            transform: scale(1.05);
                        }

                        /* Định dạng cho các tiêu đề nhỏ */
                        h6 {
                            font-size: 1.25rem;
                            color: #555;
                            margin-top: 20px;
                            font-weight: 600;
                        }

                        /* Định dạng nút quay lại */
                        .btn-back {
                            display: inline-block;
                            margin-top: 30px;
                            color: #fff;
                            background-color: #6c757d;
                            padding: 10px 20px;
                            border-radius: 5px;
                            text-align: center;
                            transition: background-color 0.3s ease;
                        }

                        .btn-back:hover {
                            background-color: #5a6268;
                            text-decoration: none;
                        }
                    </style>

                    <div class="image-container">
                        @if (!empty($new->image))
                            <img src="{{ asset('uploads/news/thumb/' . $new->image) }}" alt="Blog Image">
                        @endif
                    </div>
                    <h6>Context:</h6>
                    <p>{!! $new->description !!}</p>
                    </p>
                    <a href="{{ route('front.home') }}" class="btn-back">Quay lại</a>
                </div>
            </div>
        </div>
    </section>
@endsection
