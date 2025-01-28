<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">Owner Dashboard</h1>
                    <a href="{{ route('owner.dashboard') }}" class="hover:underline">
                        Home
                    </a>
                    <a href="{{ route('owner.users') }}" class="hover:underline">
                        Manage Users
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white cursor-not-allowed" title="Coming Soon">
                        Platform Settings
                    </a>
                </div>
                <form action="{{ route('owner.logout') }}" method="POST" class="flex">
                    @csrf
                    <button class="py-2 px-4 bg-red-500 hover:bg-red-600 rounded text-sm font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="flex-grow max-w-7xl mx-auto p-6 space-y-8">

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-200 rounded p-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-200 rounded p-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Welcome Section -->
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Welcome, Super Admin!</h2>
            <p class="mt-2 text-gray-600">Here are your quick actions to manage the platform.</p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('owner.users') }}"
                class="block bg-blue-500 text-white text-center py-4 px-6 rounded shadow hover:bg-blue-600 transition">
                <div class="text-lg font-semibold">Manage Users</div>
                <p class="text-sm mt-1">View and manage platform users.</p>
            </a>
            <a href="#"
                class="block bg-gray-400 text-white text-center py-4 px-6 rounded shadow cursor-not-allowed">
                <div class="text-lg font-semibold">Platform Settings</div>
                <p class="text-sm mt-1">(Coming Soon)</p>
            </a>
            <a href="#"
                class="block bg-green-500 text-white text-center py-4 px-6 rounded shadow hover:bg-green-600 transition">
                <div class="text-lg font-semibold">View Reports</div>
                <p class="text-sm mt-1">Analyze platform performance.</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 text-center py-4">
        <p>&copy; {{ now()->year }} Owner Dashboard. All Rights Reserved.</p>
    </footer>

</body>

</html>
