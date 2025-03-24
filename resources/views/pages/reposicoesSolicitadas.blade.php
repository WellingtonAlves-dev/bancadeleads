@extends("templates.list")
@section("title")
<!-- <div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url("/")}}">Área do Cliente</a></li>
          <li class="breadcrumb-item active" aria-current="page">Reposições Solicitadas</li>
        </ol>
    </nav>
</div> -->
@endsection
@section("menu")

@endsection
@section("tabela")
<div class="row">
    @foreach($reposicoes as $reposicao)
        <div class="col-md-4">
            <div class="card mb-4" style="background-color: white !important;">
                <div class="card-body" style="background-color: white !important;">
                    <h5 class="card-title">Número da Contestação: {{$reposicao->id}}</h5>
                    <p class="card-text">Número da Lead: {{$reposicao->lead_id}}</p>

                    <!-- Situação Atual -->
                    <p class="card-text">
                        Situação:
                        @if($reposicao->status === "rejeitada")
                            <span class="badge bg-danger">Rejeitada</span>
                            <a href="#" class="btn btn-sm btn-outline-danger mt-2" onclick="alert('{{$reposicao->motivo_reprovacao}}')">Ver Motivo</a>
                        @else
                            <span class="badge bg-success text-white">{{ ucfirst($reposicao->status) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-end">
    {{$reposicoes->onEachSide(0)->withQueryString()->links()}}
</div>

@endsection
@section("script")
<script>

</script>
@endsection