@extends('admin.layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <style>
        .btn-group button {
            margin-right: 10px;
        }

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

        .h5.mb-0.font-weight-bold.text-gray-800 {
            color: #2e59d9;
        }

        #barChart,
        #lineChart {
            width: 100% !important;
            height: 400px !important;
        }

        #pieChart {
            width: 40% !important;
            height: 300px !important;
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }
    </style>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">
            <!-- Total Orders Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Đơn Hàng</div>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng Sản Phẩm</div>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng Khách Hàng</div>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tổng Doanh Thu</div>
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

        <!-- Content Row -->
        <div class="row">
            <!-- Statistics Menu -->
            <div class="col-12 mb-4">
                <div class="btn-group" role="group" aria-label="Revenue Statistics">
                    <button type="button" class="btn btn-primary" id="weeklyStatsBtn">Thống kê theo tuần</button>
                    <a href="{{ route('admin.export_weeksaleReport') }}"> <button class="btn btn-danger ">Xuất Doanh thu
                            Tuần
                            Report</button></a>
                    <button type="button" class="btn btn-secondary" id="monthlyStatsBtn">Thống kê theo tháng</button>
                    <a href="{{ route('admin.export_monthsaleReport') }}"><button class="btn btn-success">Xuất Doanh thu
                            theo Tháng</button></a>
                    <button type="button" class="btn btn-info" id="orderStatsBtn">Tỷ lệ đơn hàng</button>
                    <a href="{{ route('admin.dashboard1') }}"><button type="button" class="btn btn-warning"
                            id="topProductStatsBtn">Sản phẩm bán chạy
                            nhất</button></a>
                </div>
            </div>


            <!-- Revenue Chart (Bar Chart) -->
            <div class="col-12" id="barChartContainer">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary" id="barChartTitle">Biểu đồ doanh thu theo tuần (Bar
                            Chart)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart (Line Chart) -->
            <div class="col-12" id="lineChartContainer">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary" id="lineChartTitle">Biểu đồ doanh thu theo tuần (Line
                            Chart)
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Status Chart (Pie Chart) -->
            <div class="col-12" id="pieChartContainer" style="display: none;">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary" id="pieChartTitle">Tỷ lệ đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Weekly revenue data
        const weeklyLabels = ["Tuần 1", "Tuần 2", "Tuần 3", "Tuần 4"];
        const weeklyData = @json(array_values($weeklyRevenues));

        // Monthly revenue data
        const monthlyLabels = @json(array_column($monthlyRevenues, 'month'));
        const monthlyData = @json(array_column($monthlyRevenues, 'revenue'));

        // Order status data
        const orderStatuses = @json($orderStatuses);
        const pieLabels = Object.keys(orderStatuses);
        const pieData = Object.values(orderStatuses);

        // Bar Chart configuration
        const barChartConfig = {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: weeklyData.map(value => value * 1000),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Thời gian'
                        },
                        ticks: {
                            autoSkip: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VND';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString('vi-VN') +
                                    ' VND';
                            }
                        }
                    }
                }
            }
        };

        // Line Chart configuration
        const lineChartConfig = {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: weeklyData.map(value => value * 1000),
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Thời gian'
                        },
                        ticks: {
                            autoSkip: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VND';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString('vi-VN') +
                                    ' VND';
                            }
                        }
                    }
                }
            }
        };

        // Pie Chart configuration
        const pieChartConfig = {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' (' + ((tooltipItem.raw /
                                    pieData.reduce((a, b) => a + b, 0)) * 100).toFixed(2) + '%)';
                            }
                        }
                    }
                }
            }
        };



        // Initialize charts
        const barChart = new Chart(document.getElementById('barChart'), barChartConfig);
        const lineChart = new Chart(document.getElementById('lineChart'), lineChartConfig);
        const pieChart = new Chart(document.getElementById('pieChart'), pieChartConfig);

        document.getElementById('weeklyStatsBtn').addEventListener('click', function() {

            const updatedWeeklyData = weeklyData.map(value => value * 1000);


            document.getElementById('barChartContainer').style.display = 'block';
            document.getElementById('lineChartContainer').style.display = 'block';
            document.getElementById('pieChartContainer').style.display = 'none';


            barChart.data.labels = weeklyLabels;
            barChart.data.datasets[0].data = updatedWeeklyData;
            lineChart.data.labels = weeklyLabels;
            lineChart.data.datasets[0].data = updatedWeeklyData;


            barChart.update();
            lineChart.update();


            document.getElementById('barChartTitle').innerText = 'Doanh thu theo tuần (Bar Chart)';
            document.getElementById('lineChartTitle').innerText = 'Doanh thu theo tuần (Line Chart)';
        });




        document.getElementById('monthlyStatsBtn').addEventListener('click', function() {

            const updatedMonthlyData = monthlyData.map(value => value * 1000);


            document.getElementById('barChartContainer').style.display = 'block';
            document.getElementById('lineChartContainer').style.display = 'block';
            document.getElementById('pieChartContainer').style.display = 'none';


            barChart.data.labels = monthlyLabels;
            barChart.data.datasets[0].data = updatedMonthlyData;
            lineChart.data.labels = monthlyLabels;
            lineChart.data.datasets[0].data = updatedMonthlyData;


            barChart.update();
            lineChart.update();


            document.getElementById('barChartTitle').innerText = 'Doanh thu theo tháng (Bar Chart)';
            document.getElementById('lineChartTitle').innerText = 'Doanh thu theo tháng (Line Chart)';
        });


        document.getElementById('orderStatsBtn').addEventListener('click', function() {
            document.getElementById('barChartContainer').style.display = 'none';
            document.getElementById('lineChartContainer').style.display = 'none';
            document.getElementById('pieChartContainer').style.display = 'block';
            document.getElementById('pieChartTitle').innerText = 'Tỷ lệ đơn hàng';
        });
    </script>
@endsection
