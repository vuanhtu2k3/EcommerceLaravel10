<?php

namespace App\Exports;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderItemExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $products = Product::join('order_items', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.title as product_name',
                DB::raw('SUM(order_items.qty) as total_sold'),
                DB::raw('SUM(order_items.qty * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.title')
            ->orderBy('total_sold', 'desc')
            ->limit(3)
            ->get();


        $products->map(function ($product) {

            $product->total_revenue = number_format($product->total_revenue, 3, '.', '.') . ' VND';
            return $product;
        });

        return $products;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên sản phẩm',
            'Số lượng bán',
            'Doanh thu thu được'
        ];
    }
}