<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Shop</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-2xl">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Edit Shop</h1>
            <p class="text-gray-600 mt-2">Update your shop details below.</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded-xl p-4 mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Edit Shop Form -->
        <form action="{{ route('shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Shop Name -->
            <div class="mb-6">
                <label for="shop-name" class="block text-gray-700 font-medium mb-2">Shop Name:</label>
                <input type="text" id="shop-name" name="name" value="{{ $shop->name }}"
                    class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300"
                    placeholder="Enter shop name" required />
            </div>

            <!-- Shop Location -->
            <div class="mb-6">
                <label for="shop-location" class="block text-gray-700 font-medium mb-2">Location:</label>
                <input type="text" id="shop-location" name="location" value="{{ $shop->location }}"
                    class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300"
                    placeholder="Enter shop location" required />
            </div>

            <!-- Shop Photo -->
            <div class="mb-6">
                <label for="shop-photo" class="block text-gray-700 font-medium mb-2">Shop Photo:</label>
                @if ($shop->shop_photo)
                    <img src="{{ asset('storage/' . $shop->shop_photo) }}" alt="Shop Photo"
                        class="w-40 h-40 mb-4 rounded-xl shadow-lg object-cover" />
                @endif
                <input type="file" id="shop-photo" name="shop_photo"
                    class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300" />
            </div>

            <!-- Form Buttons -->
            <div class="flex items-center space-x-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl transition duration-300 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Update Shop
                </button>
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-xl transition duration-300 transform hover:scale-105">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</body>

</html>
