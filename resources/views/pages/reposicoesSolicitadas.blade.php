@extends("templates.list")
@section("title")
<div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Gestão de Leads</a></li>
          <li class="breadcrumb-item active" aria-current="page">Contestações Solicitadas</li>
        </ol>
    </nav>
</div>
@endsection
@section("menu")

@endsection
@section("tabela")
<table class="table table-bordered" style="font-size: 11px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Número da contestação</th>
            <th>Número da Lead</th>
            <th>Situação atual</th>
            {{-- <th>PERFIL</th>
            <th>DDD</th>
            <th>TELEFONE</th>
            <th>E-MAIL</th>
            <th>NOME</th>
            <th>PLANO</th>
            <th>TIPO</th>
            <th>AQUISIÇÃO</th>
            <th>CORRETOR</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($reposicoes as $reposicao)
            <tr>
                <td>{{$reposicao->id}}</td>
                <td>{{$reposicao->lead_id}}</td>
                <td>
                    @if($reposicao->status === "rejeitada")
                        <a href="#" onclick="alert('{{$reposicao->motivo_reprovacao}}')">Rejeitada</a>
                    @else
                    <strong>{{ucfirst($reposicao->status)}}</strong>
                    @endif    
                </td>
            </tr>   
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$reposicoes->onEachSide(0)->withQueryString()->links()}}
</div>

@endsection
@section("script")
<script>

</script>
@endsection