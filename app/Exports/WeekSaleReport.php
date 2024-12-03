<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WeekSaleReport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $weeklyRevenues = collect();

        for ($i = 0; $i < 4; $i++) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $revenue = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('grand_total');


            $weeklyRevenues->push([
                'Week' => $startOfWeek->format('d/m/Y') . ' - ' . $endOfWeek->format('d/m/Y'),
                'Revenue' => number_format($revenue, 3, '.', '.') . ' VNĐ'
            ]);
        }

        return $weeklyRevenues;
    }


    public function headings(): array
    {
        return ['Tuần', 'Doanh thu '];
    }
}