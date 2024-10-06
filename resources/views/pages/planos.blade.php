@extends("templates.list")
@section("title")
Planos
@endsection
@section("menu")
<a href="{{url("/admin/planos/novo")}}" class="btn btn-primary">Novo plano</a>
@endsection
@section("tabela")
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($planos as $plano)
            <tr>
                <td>{{$plano->id}}</td>
                <td>{{$plano->ativo ? "Ativo" : "Inativo"}}</td>
                <td>{{$plano->nome}}</td>
                <td>{{$plano->telefone ?? "Não informado"}}</td>
                <td>{{$plano->email ?? "Não informado"}}</td>
                <td>
                    <a href="{{url("admin/planos/editar/".$plano->id)}}" class="btn btn-light btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection