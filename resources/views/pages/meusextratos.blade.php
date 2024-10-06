@extends("templates.list")
@section("title")
Movimentação Financeira
@endsection
@section("menu")
    {{-- <h5>Movimentação total: R$ {{$movimentacaoTotal}}</h5> --}}
    <h5>Saldo atual: R$ {{$saldoAtual}}</h5>
@endsection
@section("tabela")
<table class="table table-bordered" style="font-size: 11px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>AÇÃO</th>
            <th>Valor da Compra</th>
            <th>
                Saldo Anterior
            </th>
            <th>
                Saldo Atual
            </th>
            <th>Informação</th>
            <th>Data e Horário</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($extratos as $extrato)
            <tr>
                <td>{{$extrato->id}}</td>
                <td>{{$extrato->tipo}}</td>
                <td>{{ number_format($extrato->valor, 2, ",", ".")}}</td>
                <td>{{ number_format($extrato->saldo_anterior, 2, ",", ".") ?? "Sem saldo registrado" }}</td>
                <td>{{ number_format($extrato->saldo_atual, 2, ",", ".") ?? "Sem saldo registrado" }}</td>
                <td>{{$extrato->observação}}</td>
                <td>{{$extrato->created_at->format("d/m/Y H:i:s")}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$extratos->onEachSide(0)->links()}}
</div>
@endsection