<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Customer Service</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="container">
        <h2>Daftar</h2>
        <form action="{{ route('signup') }}" method="POST">
            @csrf
            <label>Nama:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label>Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
    </div>

</body>
</html>
