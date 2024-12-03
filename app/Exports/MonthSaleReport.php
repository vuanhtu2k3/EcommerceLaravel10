<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthSaleReport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $monthlyRevenues = collect();

        for ($i = 0; $i < 6; $i++) {
            $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth();
            $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('grand_total');

            $monthlyRevenues->push([
                'month' => $startOfMonth->format('F Y'),
                'revenue' => number_format($monthlyRevenue, 3, '.', '.') . ' VNĐ',
            ]);
        }

        return $monthlyRevenues;
    }

    /**
     * Tiêu đề các cột trong Excel
     */
    public function headings(): array
    {
        return ['Tháng', 'Doanh thu (VNĐ)'];
    }
}