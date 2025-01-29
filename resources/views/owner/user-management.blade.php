<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openTab(tabName) {
            document.querySelectorAll('.tab-content').forEach((content) => content.classList.add('hidden'));
            document.getElementById(tabName).classList.remove('hidden');
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">User Management</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
        @endif

        <!-- Tabs Navigation -->
        <div class="flex space-x-4 mb-6">
            <button onclick="openTab('admins')"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Admins</button>
            <button onclick="openTab('users')"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Users</button>
            <button onclick="openTab('blocked')"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Blocked</button>
        </div>

        <!-- Admins Table -->
        <div id="admins" class="tab-content">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Admins</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Photo</th>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">First Name</th>
                            <th class="px-4 py-2">Last Name</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    @if ($admin->profile_photo)
                                        <img src="{{ asset('storage/' . $admin->profile_photo) }}" alt="admin Photo"
                                            class="w-16 h-16 rounded">
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $admin->id }}</td>
                                <td class="border px-4 py-2">{{ $admin->first_name }}</td>
                                <td class="border px-4 py-2">{{ $admin->last_name }}</td>
                                <td class="border px-4 py-2">{{ $admin->phone }}</td>
                                <td class="border px-4 py-2">{{ $admin->location }}</td>
                                <td class="border px-4 py-2 flex space-x-2">
                                    <form action="{{ route('owner.unpromote') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Demote</button>
                                    </form>
                                    <form action="{{ route('owner.block') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
                                        <button
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Block</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Table (Same structure as Admins) -->
        <div id="users" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Photo</th>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">First Name</th>
                            <th class="px-4 py-2">Last Name</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="user Photo"
                                            class="w-16 h-16 rounded">
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $user->id }}</td>
                                <td class="border px-4 py-2">{{ $user->first_name }}</td>
                                <td class="border px-4 py-2">{{ $user->last_name }}</td>
                                <td class="border px-4 py-2">{{ $user->phone }}</td>
                                <td class="border px-4 py-2">{{ $user->location }}</td>
                                <td class="border px-4 py-2 flex space-x-2">
                                    <form action="{{ route('owner.promote') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button
                                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Promote</button>
                                    </form>
                                    <form action="{{ route('owner.block') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Block</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Blocked Users Tab -->
        <div id="blocked" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Blocked Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Photo</th>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">First Name</th>
                            <th class="px-4 py-2">Last Name</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blockedAdmins as $admin)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    @if ($admin->profile_photo)
                                        <img src="{{ asset('storage/' . $admin->profile_photo) }}" alt="admin Photo"
                                            class="w-16 h-16 rounded">
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $admin->id }}</td>
                                <td class="border px-4 py-2">{{ $admin->first_name }}</td>
                                <td class="border px-4 py-2">{{ $admin->last_name }}</td>
                                <td class="border px-4 py-2">{{ $admin->phone }}</td>
                                <td class="border px-4 py-2">{{ $admin->location }}</td>
                                <td class="border px-4 py-2 font-bold text-red-500">Admin</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('owner.restore') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
                                        <button
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($blockedUsers as $user)
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="user Photo"
                                            class="w-16 h-16 rounded">
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $user->first_name }}</td>
                                <td class="border px-4 py-2">{{ $user->last_name }}</td>
                                <td class="border px-4 py-2">{{ $user->phone }}</td>
                                <td class="border px-4 py-2">{{ $user->location }}</td>
                                <td class="border px-4 py-2 font-bold text-blue-500">User</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('owner.restore') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Restore</button>
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
