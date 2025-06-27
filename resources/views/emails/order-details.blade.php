<h2>Order Confirmation</h2>
<p>Dear {{ $order->user?->name }},</p>

<p>Thank you for your order! Here are your order details:</p>

<ul>
    <li><strong>Order ID:</strong> {{ $order->id }}</li>
    <li><strong>Total:</strong> ${{ number_format($order->customer_total, 2) }}</li>
    <li><strong>Status:</strong> {{ $order->status }}</li>
    <li><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</li>
</ul>

<p>Thanks, Atour!</p>

