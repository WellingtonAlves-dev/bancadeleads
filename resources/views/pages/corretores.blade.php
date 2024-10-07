@extends("templates.list")
@section("title")
<div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Gerenciamento de Leads</a></li>
          <li class="breadcrumb-item active" aria-current="page">Meus Corretores</li>
        </ol>
    </nav>
</div>
@endsection
@section("menu")
    <a class="btn btn-primary" href="{{url("corretores/novo")}}">Novo Corretor</a>
@endsection
@section("tabela")
<div class="row">
    @foreach ($corretores as $corretor)
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body bg-white">
                    <h5 class="card-title">Corretor: {{$corretor->name}}</h5>
                    <p class="card-text"><strong>ID:</strong> {{$corretor->id}}</p>
                    <p class="card-text"><strong>E-mail:</strong> {{$corretor->email}}</p>
                    <p class="card-text">
                        <strong>Ativo:</strong> 
                        <span class="badge text-white {{ $corretor->ativo ? 'bg-success' : 'bg-danger' }}">
                            {{$corretor->ativo ? 'SIM' : 'NÃO'}}
                        </span>
                    </p>
                </div>
                <div class="card-footer text-end">
                    <a href="{{url("corretores/editar/".$corretor->id)}}" class="btn btn-light btn-sm">
                        <i class="fas fa-pen"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection