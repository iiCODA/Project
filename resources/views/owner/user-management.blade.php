<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Simple JavaScript for tab switching
        function openTab(tabName) {
            document.querySelectorAll('.tab-content').forEach((content) => {
                content.classList.add('hidden');
            });
            document.getElementById(tabName).classList.remove('hidden');
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">User Management</h1>

        <!-- Success & Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="flex space-x-4 mb-6">
            <button onclick="openTab('admins')"
                class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                Admins
            </button>
            <button onclick="openTab('users')"
                class="px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transition">
                Users
            </button>
            <button onclick="openTab('deleted-users')"
                class="px-4 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600 transition">
                Deleted Users
            </button>
        </div>

        <!-- Admins Tab -->
        <div id="admins" class="tab-content">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Admins</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">{{ $admin->name }}</td>
                                <td class="border px-4 py-2">{{ $admin->email }}</td>
                                <td class="border px-4 py-2">{{ $admin->phone }}</td>
                                <td class="border px-4 py-2 flex space-x-2">
                                    <form action="{{ route('owner.unpromote') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition">
                                            Demote
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.block') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
                                        <button
                                            class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
                                            Block
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Tab -->
        <div id="users" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->phone }}</td>
                                <td class="border px-4 py-2 flex space-x-2">
                                    @if ($user->trashed())
                                        <!-- User is soft-deleted, show restore button -->
                                        <form action="{{ route('owner.restore') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button
                                                class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 transition">
                                                Restore
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('owner.promote') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button
                                                class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition">
                                                Promote
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.block') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button
                                                class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
                                                Block
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Deleted Users Tab -->
        <div id="deleted-users" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Deleted Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedUsers as $user)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->phone }}</td>
                                <td class="border px-4 py-2 flex space-x-2">
                                    <form action="{{ route('owner.restore') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button
                                            class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 transition">
                                            Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
