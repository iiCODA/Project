<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded p-4 mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="product-name" class="block font-medium">Product Name:</label>
                <input type="text" id="product-name" name="name" value="{{ $product->name }}"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="product-description" class="block font-medium">Description:</label>
                <textarea id="product-description" name="description" class="w-full p-2 border rounded">{{ $product->description }}</textarea>
            </div>

            <div class="mb-4">
                <label for="product-quantity" class="block font-medium">Quantity:</label>
                <input type="number" id="product-quantity" name="quantity" value="{{ $product->quantity }}"
                    min="1" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="product-price" class="block font-medium">Price:</label>
                <input type="number" id="product-price" name="price" value="{{ $product->price }}" min="0"
                    step="0.01" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="product-photo" class="block font-medium">Photo:</label>
                @if ($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="Product Photo"
                        class="w-32 h-32 mb-4 rounded">
                @endif
                <input type="file" id="product-photo" name="photo" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Update
                Product</button>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded ml-4">Cancel</a>
        </form>
    </div>
</body>

</html>
