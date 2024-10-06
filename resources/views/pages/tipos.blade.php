@extends("templates.list")
@section("title")
    Tipos
@endsection
@section("menu")
    <a href="{{url("/admin/tipos/novo")}}" class="btn btn-primary">Novo Tipo</a>
@endsection
@section("tabela")
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Ativo</th>
            <th>Tipo</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Ativo</th>
            <th>Tipo</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($tipos as $tipo)
            <tr>
                <td>{{$tipo->id}}</td>
                <td>{{$tipo->ativo ? "Ativo" : "Inativo"}}</td>
                <td>{{$tipo->nome}}</td>
                <td>
                    <a href="{{url("admin/tipos/editar/".$tipo->id)}}" class="btn btn-light btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection