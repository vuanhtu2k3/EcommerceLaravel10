@extends('admin.layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <style>
        .btn-group button {
            margin-right: 10px;
        }

        .card {
            border-radius: 15px;
            /* Rounded corners for a softer look */
            transition: transform 0.3s ease-in-out;
            /* Smooth hover effect */
        }

        .card:hover {
            transform: translateY(-5px);
            /* Slight hover effect */
        }

        /* Card borders and background colors */
        .card.border-left-primary {
            border-left: 5px solid #4e73df;
            background-color: #f1f4ff;
        }

        .card.border-left-success {
            border-left: 5px solid #1cc88a;
            background-color: #e8f8f1;
        }

        .card.border-left-info {
            border-left: 5px solid #36b9cc;
            background-color: #e4f7fa;
        }

        .card.border-left-warning {
            border-left: 5px solid #f6c23e;
            background-color: #fff7e8;
        }

        /* Card title and content */
        .h5.mb-0.font-weight-bold.text-gray-800 {
            color: #2e59d9;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 20px;
        }

        /* Improved spacing and alignment */
        .row .col-auto {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Chart Container */
        #topProductsChartContainer {
            width: 100%;
            margin: 30px auto;
        }

        #topProductsChart {
            width: 100% !important;
            height: 300px !important;
            display: block;
            border-radius: 10px;
            /* Rounded chart corners */
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow effect */
        }

        #pieChart {
            width: 40% !important;
            height: 300px !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #topProductsChartTitle {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Small tweaks for responsiveness */
        @media (max-width: 768px) {
            .col-xl-3 {
                width: 100%;
                margin-bottom: 15px;
            }

            #topProductsChart {
                height: 250px;
            }

            #topProductsChartTitle {
                font-size: 1.1rem;
            }
        }
    </style>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Cards Section -->
        <div class="row">
            <!-- Total Orders Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng đơn hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng đơn hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng đơn hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($totalRevenue, 3, '.', '.') }} VND
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.export_revenue') }}"><button class="btn btn-primary">Xuất Báo Cáo </button></a>

        <!-- Top Products Chart Container -->
        <div class="col-12" id="topProductsChartContainer">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" id="topProductsChartTitle"> Top Sản Phẩm Bán Chạy Nhất
                        (Bar
                        Chart)</h6>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Script for Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const bestSellingProducts = @json($bestSellingProducts);
            const productNames = bestSellingProducts.map(product => product.product_name);
            const totalSold = bestSellingProducts.map(product => product.total_sold);

            const topProductsChartConfig = {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Số lượng bán',
                        data: totalSold,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,

                            },
                            ticks: {
                                autoSkip: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString('vi-VN');
                                }
                            }
                        }
                    }
                }
            };

            const topProductsChart = new Chart(document.getElementById('topProductsChart').getContext('2d'),
                topProductsChartConfig);
        </script>
    </div>
@endsection
