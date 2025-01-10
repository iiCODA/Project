<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: none;
            border: 1px solid #007bff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
        }

        .alert {
            background: rgba(255, 0, 0, 0.2);
            border-radius: 8px;
            color: #ffcccc;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center">Login</h3>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control"
                    placeholder="Enter your phone number" required>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>

</html>
