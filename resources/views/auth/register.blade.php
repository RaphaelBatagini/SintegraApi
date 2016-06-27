@extends ('default')

@section ('conteudo')

<form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div class="form-group">
        <h2>Cadastro</h2>
    </div>

    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <div class="form-group">
        <label for="password_confirmation">Repita a senha</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <div>
        <button type="submit">Registrar</button>
    </div>
</form>

@stop
