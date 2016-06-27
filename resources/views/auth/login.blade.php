@extends ('default')

@section ('conteudo')

<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div class="form-group">
        <h2>Login</h2>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="remember"> Lembrar
        </label>
    </div>

    <div class="form-group">
        <button type="submit">Login</button>
    </div>

    <div class="form-group">
        <a href="auth/register">Ainda não possuo login</a>
    </div>
</form>

@stop
