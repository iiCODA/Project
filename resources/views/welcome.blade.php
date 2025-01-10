<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 150px;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>

    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">

        <!-- Flash Messages -->
        @if (session('message'))
            <div class="message success">
                {{ session('message') }}
            </div>
        @endif
        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Admin Users Table -->
        <h2>Admin Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adminUsers as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            <!-- Actions for promoting/unpromoting -->

                            <form action="{{ route('unpromote', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Unpromote</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Promote User Form -->
        <h2>Promote/Unpromote Users</h2>
        <form action="{{ route('promote') }}" method="POST">
            @csrf
            <label for="phone">Enter User's Phone Number to Promote:</label>
            <input type="text" name="phone" id="phone" placeholder="Phone Number" required>
            <button type="submit">Promote to Admin</button>
        </form>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>

    </div>

</body>

</html>
