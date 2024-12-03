<?php

namespace App\Http\Controllers\admin;

use App\Exports\MonthSaleReport;
use App\Exports\OrderItemExport;
use App\Exports\WeekSaleReport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();
        $totalRevenue = Order::where('status', 'delivered')->sum('grand_total') * 1000;




        // Tính tỷ lệ đơn hàng thành công
        $orderStatusSuccess = Order::where('status', 'delivered')->count();
        $orderStatusFailed = Order::where('status', '!=', 'delivered')->count();

        //Du lieu bieu do tron
        $orderStatuses = [
            'delivered' => $orderStatusSuccess,
            'cancelled' => $orderStatusFailed,
        ];


        // Doanh thu theo tháng
        $monthlyRevenues = [];
        for ($i = 0; $i < 6; $i++) {
            $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth();
            $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('grand_total');

            // Lưu vào mảng doanh thu theo tháng
            $monthlyRevenues[] = [
                'month' => $startOfMonth->format('F Y'), // Hiển thị tên tháng và năm
                'revenue' => $monthlyRevenue
            ];
        }

        // Doanh thu theo tuần (đã có)
        $weeklyRevenues = [];
        for ($i = 0; $i < 4; $i++) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeklyRevenues[] = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('grand_total');
        }

        // Xóa hình ảnh tạm thời (đã có)
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name);
            $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

            if (File::exists($path)) {
                File::delete($path);
            }
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            $tempImage->delete(); // Xóa bản ghi trong cơ sở dữ liệu
        }

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'monthlyRevenues' => $monthlyRevenues, // Gửi doanh thu theo tháng tới view
            'weeklyRevenues' => $weeklyRevenues,   // Doanh thu theo tuần
            'orderStatuses' => $orderStatuses,
        ]);
    }

    public function index1()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();
        $totalRevenue = Order::where('status', 'delivered')->sum('grand_total');
        //Tìm sản phẩm có nhiều lượt bán nhất
        $bestSellingProducts = Product::join('order_items', 'order_items.product_id', '=', 'products.id')
            ->select('products.title as product_name', DB::raw('SUM(order_items.qty) as total_sold'))
            ->groupBy('products.id', 'products.title')  // Thêm 'products.title' vào groupBy
            ->orderBy('total_sold', 'desc')
            ->limit(3)
            ->get();
        return view('admin.dashboard1', [
            'bestSellingProducts' => $bestSellingProducts,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
        ]);
    }
    public function export_revenue()
    {
        return Excel::download(new OrderItemExport, 'revenue_product.xlsx');
    }
    public function export_weeksaleReport()
    {
        return Excel::download(new WeekSaleReport, 'revenue_week_sale_support.xlsx');
    }
    public function export_monthsaleReport()
    {
        return Excel::download(new MonthSaleReport, 'revenue_month_sale_support.xlsx');
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}