<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Registrar</title>
</head>
<body>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Nome</label>
            <input id="name" type="text" name="name" required>
        </div>

        <div>
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" required>
        </div>

        <div>
            <label for="password">Senha</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmar Senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">Registrar</button>
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
