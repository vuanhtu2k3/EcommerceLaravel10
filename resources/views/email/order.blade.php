<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    @if ($mailData['userType'] == 'customer')
        <h1>Thanks for your order!!</h1>
        <h2> Your Order Id is : #{{ $mailData['order']->id }}</h2>
    @else
        <h1>You have received an order:</h1>
        <h2> Order Id is : #{{ $mailData['order']->id }}</h2>
    @endif


    <h1>Shipping Address</h1>
    <address>
        <strong>{{ $mailData['order']->first_name . '' . $mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }},{{ $mailData['order']->zip }}
        {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>
    <h2>Products</h2>
    <table cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #CCC;">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->price, 3) }} VND</td>
                    <td>{{ $item->qty }} </td>
                    <td>{{ number_format($item->total, 3) }} VND</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>{{ number_format($mailData['order']->subtotal, 3) }} VND</td>
            </tr>
            <tr>
                <th colspan="3" align="right">
                    Discount:{{ !empty($mailData['order']->coupon_code) ? '(' . $mailData['order']->coupon_code . ')' : '' }}
                </th>
                <td>{{ number_format($mailData['order']->discount, 3) }} VND</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>{{ number_format($mailData['order']->shipping, 3) }} VND</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>{{ number_format($mailData['order']->grand_total, 3) }} VND</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
