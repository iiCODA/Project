<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // JavaScript for tab switching
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            // Highlight the active tab in the sidebar
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('bg-gray-700', 'font-bold'));
            document.querySelector(`[data-tab="${tabId}"]`).classList.add('bg-gray-700', 'font-bold');
        }

        // Default to the "My Shop" tab on page load
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('my-shop-tab');
        });
    </script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar with Vertical Tabs -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col p-5">
            <h2 class="text-2xl font-bold mb-8 text-center">Admin Dashboard</h2>
            <nav class="space-y-4">
                <button data-tab="my-shop-tab"
                    class="tab-link block py-2 px-4 rounded hover:bg-gray-700 bg-gray-700 font-bold"
                    onclick="switchTab('my-shop-tab')">
                    My Shop
                </button>
                <button data-tab="my-products-tab" class="tab-link block py-2 px-4 rounded hover:bg-gray-700"
                    onclick="switchTab('my-products-tab')">
                    My Products
                </button>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf
                <button class="w-full py-2 px-4 mt-8 bg-red-600 hover:bg-red-700 rounded text-white font-semibold">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6">Welcome to Your Dashboard</h1>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 text-green-800 border border-green-200 rounded p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 border border-red-200 rounded p-4 mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- My Shop Tab -->
            <section id="my-shop-tab" class="tab-content hidden">
                @if (Auth::user()->shop)
                    <!-- Shop Details Section -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <!-- Header Section -->
                        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-8 text-white">
                            <div class="flex items-center">
                                <!-- Shop Photo -->
                                @if (Auth::user()->shop->shop_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->shop->shop_photo) }}" alt="Shop Photo"
                                        class="w-28 h-28 rounded-full border-4 border-white shadow-lg">
                                @else
                                    <div
                                        class="w-28 h-28 rounded-full bg-gray-300 border-4 border-white flex items-center justify-center text-gray-700 shadow-lg">
                                        <span class="text-xl font-bold">No Photo</span>
                                    </div>
                                @endif

                                <!-- Shop Info -->
                                <div class="ml-6">
                                    <h2 class="text-4xl font-bold">{{ Auth::user()->shop->name }}</h2>
                                    <p class="text-lg mt-2"><i class="fas fa-map-marker-alt"></i>
                                        {{ Auth::user()->shop->location }}</p>
                                    <p class="text-sm mt-1 opacity-75">Shop ID: {{ Auth::user()->shop->id }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Section -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Edit Shop Button -->
                                <a href="{{ route('shop.edit', Auth::user()->shop->id) }}"
                                    class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 transform hover:scale-105">
                                    <i class="fas fa-edit mr-2"></i> Edit Shop
                                </a>

                                <!-- Delete Shop Button -->
                                <form action="{{ route('shop.destroy') }}" method="POST" class="text-center"
                                    onsubmit="return confirm('Are you sure you want to delete your shop?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 transform hover:scale-105">
                                        <i class="fas fa-trash-alt mr-2"></i> Delete Shop
                                    </button>
                                </form>

                                <!-- Show Orders Button -->
                                <a href="{{ route('orders.index') }}"
                                    class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 transform hover:scale-105">
                                    <i class="fas fa-list mr-2"></i> Show Orders
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Create Shop Section -->
                    <div class="bg-white shadow-lg rounded-lg p-8">
                        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create Your Shop</h2>
                        <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="shop-name" class="block text-gray-700 font-medium mb-2">Shop Name:</label>
                                <input type="text" id="shop-name" name="name"
                                    class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    placeholder="Enter shop name" required>
                            </div>
                            <div class="mb-6">
                                <label for="shop-location"
                                    class="block text-gray-700 font-medium mb-2">Location:</label>
                                <input type="text" id="shop-location" name="location"
                                    class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    placeholder="Enter shop location" required>
                            </div>
                            <div class="mb-6">
                                <label for="shop-photo" class="block text-gray-700 font-medium mb-2">Shop Photo:</label>
                                <input type="file" id="shop-photo" name="shop_photo"
                                    class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                            <button
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg shadow transition duration-200 transform hover:scale-105">
                                Create Shop
                            </button>
                        </form>
                    </div>
                @endif
            </section>


            <!-- My Products Tab -->
            <section id="my-products-tab" class="tab-content hidden">
                @if (Auth::user()->shop)
                    <section class="bg-white shadow rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold mb-4">Add Product</h2>
                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="product-name" class="block font-medium">Product Name:</label>
                                <input type="text" id="product-name" name="name" class="w-full p-2 border rounded"
                                    placeholder="Enter product name" required>
                            </div>
                            <div class="mb-4">
                                <label for="product-description" class="block font-medium">Description:</label>
                                <textarea id="product-description" name="description" class="w-full p-2 border rounded"
                                    placeholder="Enter product description"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="product-quantity" class="block font-medium">Quantity:</label>
                                <input type="number" id="product-quantity" name="quantity"
                                    class="w-full p-2 border rounded" min="1" required>
                            </div>
                            <div class="mb-4">
                                <label for="product-price" class="block font-medium">Price:</label>
                                <input type="number" id="product-price" name="price"
                                    class="w-full p-2 border rounded" min="0" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="product-photo" class="block font-medium">Photo:</label>
                                <input type="file" id="product-photo" name="photo"
                                    class="w-full p-2 border rounded">
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Add
                                Product</button>
                        </form>
                    </section>

                    <section class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-4">Products</h2>
                        <table class="table-auto w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="px-4 py-2">Product Name</th>
                                    <th class="px-4 py-2">Price</th>
                                    <th class="px-4 py-2">Quantity</th>
                                    <th class="px-4 py-2">Description</th>
                                    <th class="px-4 py-2">Photo</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()->shop->products as $product)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $product->name }}</td>
                                        <td class="border px-4 py-2">${{ number_format($product->price, 2) }}</td>
                                        <td class="border px-4 py-2">{{ $product->quantity }}</td>
                                        <td class="border px-4 py-2">{{ $product->description }}</td>
                                        <td class="border px-4 py-2">
                                            @if ($product->photo)
                                                <img src="{{ asset('storage/' . $product->photo) }}"
                                                    alt="Product Photo" class="w-16 h-16 rounded">
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('product.edit', $product->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-2 rounded">Edit</a>
                                            <form action="{{ route('product.destroy', $product->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?')"
                                                class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white py-1 px-2 rounded">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                @else
                    <p class="text-gray-600">You need to <a href="javascript:void(0);"
                            class="text-blue-500 hover:underline" onclick="switchTab('my-shop-tab')">create a shop</a>
                        before managing products.</p>
                @endif
            </section>
        </main>
    </div>
</body>

</html>
