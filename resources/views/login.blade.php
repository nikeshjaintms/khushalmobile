<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298, #4e4376);
            background-size: 400% 400%;
            animation: gradientShift 10s ease infinite;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .btn {
            background: linear-gradient(45deg, #ff7f50, #ff4500);
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: linear-gradient(45deg, #ff6347, #ff8c00);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 140, 0, 0.3);
        }

        .extras a {
            color: #ffa07a;
            text-decoration: none;
            transition: 0.3s;
        }

        .extras a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="{{ route('admin.login.post')}}">
            @csrf
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif

            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Username" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
        <div class="extras mt-3">
            <a href="#">Forgot Password?</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
