@extends("templates.list")
@section("title")
<!-- <div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url("/")}}">Área do Cliente</a></li>
          <li class="breadcrumb-item active" aria-current="page">Meu Extrato Financeiro</li>
        </ol>
    </nav>
</div> -->
@endsection
@section("menu")
    <h5>Saldo atual: R$ {{$saldoAtual}}</h5>
@endsection
@section("tabela")
<div class="row">
    @foreach ($extratos as $extrato)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body bg-white">
                    <h5 class="card-title">Transação #{{$extrato->id}}</h5>
                    <p class="card-text"><strong>Ação:</strong> {{$extrato->tipo}}</p>
                    <p class="card-text"><strong>Valor da Compra:</strong> R$ {{ number_format($extrato->valor, 2, ",", ".")}}</p>
                    <p class="card-text"><strong>Saldo Anterior:</strong> R$ {{ number_format($extrato->saldo_anterior, 2, ",", ".") ?? "Sem saldo registrado" }}</p>
                    <p class="card-text"><strong>Saldo Atual:</strong> R$ {{ number_format($extrato->saldo_atual, 2, ",", ".") ?? "Sem saldo registrado" }}</p>
                    <p class="card-text"><strong>Informação:</strong> {{$extrato->observação}}</p>
                    <p class="card-text"><small class="text-muted">Data e Horário: {{$extrato->created_at->format("d/m/Y H:i:s")}}</small></p>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-end">
    {{$extratos->onEachSide(0)->links()}}
</div>
@endsection
