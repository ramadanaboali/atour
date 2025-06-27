<h2>Atour Order</h2>
<p>Dear {{ $order->user?->name }},</p>

<p>Thank you for your order! Here are your order Code:</p>

<ul>
    <li><strong>Order ID:</strong> {{ $order->id }}</li>
    <li><strong>Code :</strong> {{ $code }}</li>

    <li><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</li>
</ul>

<p>Thanks, Atour!</p>
