<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Shop</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded p-4 mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="shop-name" class="block font-medium">Shop Name:</label>
                <input type="text" id="shop-name" name="name" value="{{ $shop->name }}"
                    class="w-full p-2 border rounded" required />
            </div>

            <div class="mb-4">
                <label for="shop-location" class="block font-medium">Location:</label>
                <input type="text" id="shop-location" name="location" value="{{ $shop->location }}"
                    class="w-full p-2 border rounded" required />
            </div>

            <div class="mb-4">
                <label for="shop-photo" class="block font-medium">Shop Photo:</label>
                @if ($shop->shop_photo)
                    <img src="{{ asset('storage/' . $shop->shop_photo) }}" alt="Shop Photo"
                        class="w-32 h-32 mb-4 rounded" />
                @endif
                <input type="file" id="shop-photo" name="shop_photo" class="w-full p-2 border rounded" />
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Update Shop
            </button>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded ml-4">Cancel</a>
        </form>
    </div>
</body>

</html>
