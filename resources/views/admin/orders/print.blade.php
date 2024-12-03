<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order: {{ $order->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .content-header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            color: #333;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-col {
            width: 45%;
            margin-bottom: 20px;
        }

        .invoice-col h1 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .invoice-col address {
            font-size: 14px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-info {
            color: #17a2b8;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .total th,
        .total td {
            font-size: 16px;
            color: #333;
        }

        .total th {
            text-align: right;
        }

        .total td {
            text-align: left;
        }
    </style>
</head>

<body>
    <section class="content-header">
        <h1>Order: #{{ $order->id }}</h1>
    </section>

    <section class="content">
        <div class="invoice-info">
            <div class="invoice-col">
                <h1 class="h5">Shipping Address</h1>
                <address>
                    <strong>Name: </strong>{{ $order->first_name . ' ' . $order->last_name }}<br>
                    <strong>Address:</strong> {{ $order->address }}<br>
                    <strong>City:</strong> {{ $order->city }}<br>
                    <strong>Phone: </strong> {{ $order->mobile }}<br>
                    <strong>Email: </strong> {{ $order->email }}
                </address>
                <strong>Shipped Date: </strong>
                @if (!empty($order->shipped_date))
                    {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                @else
                    n/a
                @endif
            </div>

            <div class="invoice-col">
                <b>Order ID:</b> {{ $order->id }}<br>
                <b>Total:</b> {{ number_format($order->grand_total, 3, '.', '.') }} VND<br>
                <b>Status:</b>
                @if ($order->status == 'pending')
                    <span class="text-danger">Pending</span>
                @elseif ($order->status == 'shipped')
                    <span class="text-info">Shipped</span>
                @elseif ($order->status == 'delivered')
                    <span class="text-success">Delivered</span>
                @else
                    <span class="text-warning">Cancelled</span>
                @endif
            </div>
        </div>

        <div class="card-body table-responsive p-3">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th width="100">Price</th>
                        <th width="100">Qty</th>
                        <th width="100">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ number_format($item->price, 3, '.', '.') }} VND</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->total, 3, '.', '.') }} VND</td>

                        </tr>
                    @endforeach
                    <tr class="total">
                        <th colspan="3" class="text-right">Subtotal:</th>
                        <td>{{ number_format($order->subtotal, 3, '.', '.') }} VND</td>
                    </tr>
                    <tr class="total">
                        <th colspan="3" class="text-right">
                            Discount:{{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}
                        </th>
                        <td>{{ number_format($order->discount, 3) }} VND</td>
                    </tr>
                    <tr class="total">
                        <th colspan="3" class="text-right">Shipping:</th>
                        <td>{{ number_format($order->shipping, 3) }} VND</td>
                    </tr>
                    <tr class="total">
                        <th colspan="3" class="text-right">Grand Total:</th>
                        <td>{{ number_format($order->grand_total, 3, '.', '.') }} VND</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>
