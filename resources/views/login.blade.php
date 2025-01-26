<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- Login Card -->
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Welcome Back</h1>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 border border-red-200 p-4 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('wlogin') }}" method="POST" class="space-y-6">
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
