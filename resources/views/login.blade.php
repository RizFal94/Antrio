<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer Service</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="{{ route('signup') }}">Daftar</a></p>
    </div>

</body>
</html>
