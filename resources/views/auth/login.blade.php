<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" required autofocus>
        </div>

        <div>
            <label for="password">Senha</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>

        @if($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</body>
</html>
