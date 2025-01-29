<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Orders</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-6xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Shop Orders</h1>
            <a href="{{ route('dashboard') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- No Orders Message -->
        @if (empty($orders))
            <div class="text-center p-8 bg-gray-50 rounded-lg">
                <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">No orders found for your shop.</p>
            </div>
        @else
            <!-- Orders List -->
            @foreach ($orders as $order)
                <div class="border rounded-lg p-6 mb-6 bg-white hover:shadow-xl transition duration-300">
                    <!-- Order Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Order #{{ $order['order_id'] }}</h2>
                        <p class="text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i> {{ $order['order_date'] }}
                        </p>
                    </div>

                    <!-- Order Items Table -->
                    <table class="table-auto w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-3 border border-gray-200">Product Name</th>
                                <th class="px-4 py-3 border border-gray-200">Quantity</th>
                                <th class="px-4 py-3 border border-gray-200">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order['items'] as $item)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-4 py-3 border border-gray-200">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-3 border border-gray-200">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-3 border border-gray-200">${{ number_format($item['price'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>
</body>

</html>
