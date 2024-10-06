{{-- 
    
                                <th>TIPO</th>
                                <th>PLANO</th>
                                <th>PREÇO</th>
                                <th>HORARIO PARTIDA</th>
                                <th>DIAS DISPONIVEIS</th>
                                <th>QTD VIDAS</th>
                                <th>DDD</th>
                                <th>TELEFONE</th>
                                <th>E-MAIL</th>
                                <th>CLIENTE</th>
                            </thead>

    --}}
<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
@foreach ($leads as $lead)
<tr>
    <td>{{$lead->id}}</td>
    <td>
        <input type="checkbox" onclick="toggleStatusLead({{$lead->id}}, this)" @checked($lead->ativo)/>
    </td>
    <td>{{$lead->tipo->nome}}</td>
    <td>{{$lead->plano->nome}}</td>
    <td>{{$lead->preco}}</td>
    <td>{{$lead->horario_partida}}</td>
    <td>{{$lead->dias_disponivel}}</td>
    <td>{{$lead->idade}}</td>
    <td>{{$lead->ddd}}</td>
    <td>{{$lead->telefone}}</td>
    <td>{{$lead->email}}</td>
    <td>{{$lead->nome_lead}}</td>
    <td>
        <a href="{{url("admin/leads/reset/".$lead->id)}}" class="btn btn-sm btn-warning">
            <i class="fas fa-clock"></i>
        </a>
        <a href="{{url("admin/leads/editar/".$lead->id)}}" class="btn btn-sm btn-secondary">
            <i class="fas fa-edit"></i>
        </a>
    </td>
</tr>
@endforeach