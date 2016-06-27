@extends ('master')

@section ('conteudo')
<table class='table table-hover'>
	<thead>
		<tr>
			<th>CNPJ</th>
			<th>JSON</th>
			<th>Ações</th>
		</tr>
	</thead>
	<tbody>
	@if (count($sintegras) > 0)
		@foreach ($sintegras as $sintegra)
			<tr>
				<td>{{$sintegra->cnpj}}</td>
				<td style="overflow-wrap: break-word; max-width: 300px">{{$sintegra->resultado_json}}</td>
				<td><a href="sintegra/destroy/{{$sintegra->id}}" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3" style="text-align: center">Nenhum registro encontrado</td>
		</tr>
	@endif
	</tbody>
</table>

@stop
