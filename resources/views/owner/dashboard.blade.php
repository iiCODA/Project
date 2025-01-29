<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Gradient background */
        body {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-6">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i> Owner Dashboard
                    </h1>
                    <div class="flex space-x-4">
                        <a href="{{ route('owner.dashboard') }}"
                            class="hover:bg-blue-700 px-3 py-2 rounded-lg transition duration-300">
                            Home
                        </a>
                        <a href="{{ route('owner.users') }}"
                            class="hover:bg-blue-700 px-3 py-2 rounded-lg transition duration-300">
                            Manage Users
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white px-3 py-2 rounded-lg cursor-not-allowed"
                            title="Coming Soon">
                            Platform Settings
                        </a>
                    </div>
                </div>
                <form action="{{ route('owner.logout') }}" method="POST" class="flex">
                    @csrf
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-semibold transition duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="flex-grow max-w-7xl mx-auto p-6 space-y-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-200 rounded-lg p-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded-lg p-4 space-y-2">
                @foreach ($errors->all() as $error)
                    <p><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="text-center">
            <h2 class="text-4xl font-bold text-gray-800">Welcome, Super Admin!</h2>
            <p class="mt-2 text-gray-600">Here are your quick actions to manage the platform.</p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('owner.users') }}"
                class="block bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center py-6 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <i class="fas fa-users text-4xl mb-4"></i>
                <div class="text-xl font-semibold">Manage Users</div>
                <p class="text-sm mt-2">View and manage platform users.</p>
            </a>
            <a href="#"
                class="block bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-6 px-6 rounded-xl shadow-lg cursor-not-allowed">
                <i class="fas fa-cogs text-4xl mb-4"></i>
                <div class="text-xl font-semibold">Platform Settings</div>
                <p class="text-sm mt-2">(Coming Soon)</p>
            </a>
            <a href="#"
                class="block bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-6 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <i class="fas fa-chart-line text-4xl mb-4"></i>
                <div class="text-xl font-semibold">View Reports</div>
                <p class="text-sm mt-2">Analyze platform performance.</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 text-center py-4">
        <p>&copy; {{ now()->year }} Owner Dashboard. All Rights Reserved.</p>
    </footer>
</body>

</html>
