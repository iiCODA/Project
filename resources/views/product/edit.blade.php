<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <!-- Page Header -->
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Product</h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded-lg p-4 mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Edit Product Form -->
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Product Name -->
            <div class="mb-6">
                <label for="product-name" class="block text-gray-700 font-medium mb-2">Product Name:</label>
                <input type="text" id="product-name" name="name" value="{{ $product->name }}"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter product name" required>
            </div>

            <!-- Product Description -->
            <div class="mb-6">
                <label for="product-description" class="block text-gray-700 font-medium mb-2">Description:</label>
                <textarea id="product-description" name="description"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter product description">{{ $product->description }}</textarea>
            </div>

            <!-- Product Quantity -->
            <div class="mb-6">
                <label for="product-quantity" class="block text-gray-700 font-medium mb-2">Quantity:</label>
                <input type="number" id="product-quantity" name="quantity" value="{{ $product->quantity }}"
                    min="1"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter quantity" required>
            </div>

            <!-- Product Price -->
            <div class="mb-6">
                <label for="product-price" class="block text-gray-700 font-medium mb-2">Price:</label>
                <input type="number" id="product-price" name="price" value="{{ $product->price }}" min="0"
                    step="0.01"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter price" required>
            </div>

            <!-- Product Photo -->
            <div class="mb-6">
                <label for="product-photo" class="block text-gray-700 font-medium mb-2">Photo:</label>
                @if ($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="Product Photo"
                        class="w-32 h-32 mb-4 rounded-lg shadow">
                @endif
                <input type="file" id="product-photo" name="photo"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Form Buttons -->
            <div class="flex items-center space-x-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</body>

</html>
