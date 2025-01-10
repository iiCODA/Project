.
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner's Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            /* Light background */
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #002855;
            /* Dark blue */
            margin-bottom: 30px;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #0078d7;
            /* Blue button */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #005bb5;
        }

        .button-group {
            position: absolute;
            top: 20px;
            right: 160px;
            /* Adjusted to create 30px space from the logout button */
            display: flex;
            gap: 10px;
        }


        .button-group button {
            background-color: #0078d7;
            /* Blue button */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-group button:hover {
            background-color: #005bb5;
            margin: auto 30px auto auto;
        }

        .table-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #ffffff;
            /* White table background */
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        .table-container header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-container header h2 {
            margin: 0;
            font-size: 24px;
            color: #002855;
        }

        .add-button {
            background-color: #0078d7;
            /* Blue button */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-button:hover {
            background-color: #005bb5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #002855;
            /* Dark blue header */
            color: #fff;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
            /* Light gray for alternating rows */
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        tbody tr:hover {
            background-color: #f1f5fb;
            /* Slight highlight on hover */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .delete-btn {
            background-color: #d7263d;
            /* Red */
            color: #fff;
        }

        .action-buttons button:hover {
            transform: scale(1.1);
            /* Slight zoom effect */
        }

        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .name-column {
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="button-group">
        <button>Show Users</button>
        <button>Show Admins</button>
        <button>Show All</button>
    </div>
    <div>
        <button class="logout-button">Logout</button>
    </div>
    <h1>Dashboard</h1>

    <div class="table-container">
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

        <header>
            <h2>Admins</h2>
            <button class="add-button">Add Admin</button>
        </header>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Phone number</th>
                    <th>User Type</th>
                    <th>Action</th>
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
        <h2>Promote/Unpromote Users</h2>
        <form action="{{ route('promote') }}" method="POST">
            @csrf
            <label for="phone">Enter User's Phone Number to Promote:</label>
            <input type="text" name="phone" id="phone" placeholder="Phone Number" required>
            <button class="add-button">Add Admin</button>
        </form>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>

    </div>
</body>

</html>
