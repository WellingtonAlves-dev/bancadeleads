@extends("templates.list")
@section("title")
    Pacotes
@endsection
@section("menu")
    <a href="{{url("/admin/pacotes/novo")}}" class="btn btn-primary">Novo Pacote</a>
@endsection
@section("tabela")
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>ATIVO</th>
            <th>NOME</th>
            <th>QTD LEADS</th>
            <th>QTD DESCONTO</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pacotes as $pacote)
            <tr>
                <td>
                    {{$pacote->id}}
                </td>
                <td>
                    {{$pacote->ativo ? "SIM" : "NÃO"}}
                </td>
                <td>
                    {{$pacote->name}}
                </td>
                <td>
                    {{$pacote->qtd_leads}}
                </td>
                <td>
                    {{$pacote->qtd_desconto}}
                </td>
                <td>
                    {{-- MENU --}}
                    <a href="{{url("admin/pacotes/editar/".$pacote->id)}}" class="btn btn-primary btn-sm">Editar</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection