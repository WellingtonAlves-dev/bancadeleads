<style>
    .card-lead {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        padding: 10px;
    }

    .card-lead:hover {
        transform: scale(1.02);
        box-shadow: 0px 4px 15px rgba(0, 127, 255, 0.3);
    }

    .highlight-price {
        font-size: 1.8rem;
        font-weight: bold;
        color: #007fff;
        text-align: center;
        padding: 6px;
        border: 2px solid #007fff;
        border-radius: 6px;
        display: inline-block;
        width: 100%;
        background: rgba(0, 127, 255, 0.1);
        margin-bottom: 8px;
    }

    .btn-primary:hover {
        background-color: #005bb5 !important;
        border-color: #005bb5 !important;
    }

    .btn-warning:hover {
        background-color: #e6a700 !important;
        border-color: #e6a700 !important;
    }

    .card-header {
        padding: 8px;
    }

    .card-body {
        padding: 10px;
    }

    .table {
        margin-bottom: 8px;
    }

    .table th, .table td {
        padding: 6px;
        font-size: 13px;
    }

    .btn {
        padding: 6px;
        font-size: 14px;
    }
</style>

<div 
    data-tipo="{{$lead->tipo_id}}" 
    data-ddd="{{$lead->ddd}}" 
    class="card card-lead mt-4 shadow">
    
    <!-- Cabeçalho do Card -->
    <div class="card-header text-center text-white" style="background-color: {{ $lead->cnpj ? "#007fff" : "#fafafa" }}; border: none;  color: {{ $lead->cnpj ? "white" : "black" }} !important;">
        <h5 class="fw-bold m-0">Lead {{$lead->tipo->nome}}
             @if($lead->cnpj)
                <i class="fa fa-star" aria-hidden="true" style="color: yellow; font-size: 16px;"></i>
             @endif
        </h5>
        <h6 class="fw-bold m-0">DDD ({{$lead->ddd}})</h6>
    </div>

    <!-- Horário de Partida -->
    @if($show_time ?? true)
    <div class="bg-light w-100 text-center text-dark py-1">
        <small><i class="fas fa-clock"></i> {{$lead->horario_partida}}</small>
    </div>
    @endif

    <!-- Corpo do Card -->
    <div class="card-body text-center">
        <div class="highlight-price">R$ {{$lead->preco}}</div>

        <!-- Tabela de Informações -->
        <table class="table text-left w-100 table-bordered">
            <tr style="background-color: #fafafa; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-star"></i> Plano</th>
                <td>{{$lead->plano->nome}}</td>
            </tr>
            <tr style="background-color: #ebebeb; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-phone"></i> Região</th>
                <td>DDD ({{$lead->ddd}})</td>
            </tr>
            <tr style="background-color: #fafafa; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-user"></i> Quantidade</th>
                <td>{{$lead->idade}}</td>
            </tr>
            <tr style="background-color: #ebebeb; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-briefcase"></i> Pessoa Jurídica</th>
                <td>{{$lead->cnpj ? "Sim" : "Não"}}</td>
            </tr>
            <tr style="background-color: #fafafa; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-info-circle"></i> Observação</th>
                <td>{{$lead->extra ?? 'Nenhuma'}}</td>
            </tr>
            <tr style="background-color: #ebebeb; border-top: 1px solid rgba(0,0,0,0.5) !important; color: black;">
                <th><i class="fas fa-city"></i> DDD</th>
                <td>{{$lead->ddd ?? '00'}}</td>
            </tr>
            @if(Auth::user()->role == "admin")
            <tr class="text-danger fw-bold">
                <th>Disponível até</th>
                <td>{{$lead->disponivel_ate}}</td>
            </tr>
            @endif
        </table>

        <!-- Botões de Ação -->
        @if(Auth::user()->role == "user")
        <button class="btn btn-primary w-100 fw-bold mt-2" onclick="atenderLead('{{$lead->id}}')">
            <i class="fas fa-shopping-cart"></i> Atender Lead
        </button>
        @elseif($lead->id_user)
        <div class="alert alert-success mt-2 p-2">
            Lead vendida para o usuário # 
            <a target="_blank" href="{{url("admin/users/editar/".$lead->id_user)}}" class="fw-bold">
                {{$lead->id_user}}
            </a>
        </div>
        @else
        <a href="{{url("admin/leads/editar/".$lead->id)}}" class="btn btn-secondary w-100 mt-2">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{url("admin/leads/reset/".$lead->id)}}" class="btn btn-warning w-100 mt-2">
            <i class="fas fa-sync"></i> Resetar Tempo
        </a>
        @endif
    </div>
</div>
