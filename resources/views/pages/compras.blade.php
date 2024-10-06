@extends("templates.list")
@section("title")
Relatório de venda de leads
@endsection
@section("menu")
    <form method="GET" class="row">
        <div class="col-4">
            <label>Data Inicial</label>
            <input type="date" name="data_inicial" value="{{request()->get("data_inicial") ?? ""}}" class="form-control"/>
        </div>
        <div class="col-4">
            <label>Data Final</label>
            <input type="date" name="data_final" value="{{request()->get("data_final") ?? ""}}" class="form-control"/>
        </div>
        <div class="col-4">
            <label>&nbsp</label><br/>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{url("/admin/financeiro/vendas")}}" class="btn btn-secondary">
                    <i class="fas fa-broom"></i>
                </a>
            </div>
        </div>
    </form>
@endsection
@section("tabela")
<table class="table table-bordered" id="dataTable" width="100%" style="font-size: 12px" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>E-MAIL</th>
            <th>TELEFONE</th>
            <th>PREÇO</th>
            <th>COMPRADOR</th>
            <th>DATA COMPRA</th>
        </tr>
    </thead>
    <tbody style="font-size: 11px">
        @foreach($extratos as $key => $extrato)
            <tr>
                <td>{{$extrato->id}}</td>
                <td>{{$extrato->lead->nome_lead ?? ""}}</td>
                <td>{{$extrato->lead->email ?? ""}}</td>
                <td>({{$extrato->lead->ddd ?? ""}}) {{$extrato->lead->telefone ?? ""}}</td>
                <td>R$ {{ number_format($extrato->valor ??0, 2,",", ".") }}</td>
                <td>
                    <a href="{{url("/admin/users/editar/".$extrato->user->id)}}" target="_blank">
                        {{$extrato->user->name}}
                    </a>
                </td>
                <td>
                    {{$extrato->created_at->format("d/m/Y H:i:s")}}
                </td>
            </tr>
        @endforeach
        <tr style="font-size: 16px">
            <th colspan="2">Valor Total:</th>
            <td colspan="6">R$ 
                {{number_format($valorTotal, 2,",", ".")}}
            </td>
        </tr>
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$extratos->onEachSide(0)->withQueryString()->links()}}
</div>
@endsection