<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Owner Login</h1>

        <!-- Display Error Messages -->
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

        <form action="{{ route('owner.login.submit') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Phone Input -->
            <div>
                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="text" name="phone" id="phone" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200 transform hover:scale-105">
                Login
            </button>
        </form>
    </div>
</body>

</html>
