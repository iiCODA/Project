<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Orders</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-6xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Orders</h1>

        @if (empty($orders))
            <p class="text-gray-600">No orders found for your shop.</p>
        @else
            @foreach ($orders as $order)
                <div class="border rounded-lg p-4 mb-6">
                    <h2 class="text-xl font-semibold mb-2">Order #{{ $order['order_id'] }}</h2>
                    <p class="text-gray-600">Order Date: {{ $order['order_date'] }}</p>
                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 border">Product Name</th>
                                <th class="px-4 py-2 border">Quantity</th>
                                <th class="px-4 py-2 border">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order['items'] as $item)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-2 border">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-2 border">${{ number_format($item['price'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif

        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Back to
            Dashboard</a>
    </div>
</body>

</html>
