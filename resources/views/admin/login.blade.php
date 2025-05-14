<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: rgb(28, 15, 69);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container h2 {
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
        }

        .form-label{
            color: white;
            font-weight: bold;
            text-align: left;
            display: block;

        }
        .btn-login {
            background-color: #E95670;
            color: white;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color:#B34270;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
        h1{
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }
        h2{
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>RelovedUKM</h1>
        <h2>Admin Login</h2>
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="matricnum" class="form-label">ID:</label>
                <input type="text" class="form-control" name="matricnum" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-login w-100">Login</button>
        </form>

        @if ($errors->any())
            <div class="mt-3">
                @foreach ($errors->all() as $error)
                    <p class="error-message">{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bootstrap JS (optional for interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
