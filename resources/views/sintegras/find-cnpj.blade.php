@extends ('master')

@section ('conteudo')

<form class="form-inline" method="POST" action="/sintegra/create">
    {!! csrf_field() !!}

    <div class="form-group">
        <input type="text" class="form-control" id="cnpj" placeholder="CNPJ" name="cnpj" aria-describedby="helpBlock" maxlength="14" required>
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
    <span id="helpBlock" class="help-block">Preencha somente com números.</span>
</form>
<br/>
<table class='table table-hover'>
    <thead>
        <tr>
            <th>Resultado da busca</th>
        </tr>
        <tr>
            <td style="overflow-wrap: break-word; max-width: 300px">{{$resultado_json}}</td>
        </tr>
    </thead>
</table>

@stop
