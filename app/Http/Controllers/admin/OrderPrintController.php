<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPrintController extends Controller
{
    // public function printOrder($orderId)
    // {
    //     // Load the HTML content into the PDF wrapper
    //     $pdf = Pdf::loadHTML($this->printOrderConvert($orderId));
    //     return $pdf->stream(); // Stream the PDF to the browser
    // }

    // public function printOrderConvert($orderId)
    // {
    //     // Replace this with the actual HTML content for the order
    //     return "<h1>Order ID: {$orderId}</h1>";
    // }
    public function printOrder($orderId)
    {
        // Retrieve the order based on the given orderId
        $order = Order::where('orders.id', $orderId) // Filter by orderId
            ->latest('orders.created_at') // Get the latest order
            ->select('orders.*', 'users.name', 'users.email')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->first();

        if (!$order) {

            return response()->json(['message' => 'Order not found'], 404);
        }

        $orderItems = OrderItem::where('order_id', $orderId)->get();


        $html = view('admin.orders.print', [
            'order' => $order,
            'orderItems' => $orderItems,
        ])->render();


        $pdf = Pdf::loadHTML($html);

        return $pdf->stream('order-' . $orderId . '.pdf');
    }
}
