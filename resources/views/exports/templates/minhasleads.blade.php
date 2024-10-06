<table>
    <thead>
        <tr>
            <th>ID LEAD</th>
            @if(!Auth::user()->vinculado)
                <th>PREÇO</th>
            @endif
            <th>PERFIL</th>
            <th>NOME</th>
            <th>TELEFONE</th>
            <th>E-MAIL</th>
            <th>TIPO</th>
            <th>PLANO</th>
            <th>EXTRA</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leads as $lead)
            <tr>
                <td>{{$lead->id}}</td>
                @if(!Auth::user()->vinculado)
                    <td>R$ {{ number_format($lead->preco, 2, ",", ".") }}</td>
                @endif
                <td>{{$lead->idade}}</td>
                <td>{{$lead->nome_lead}}</td>
                <td>({{$lead->ddd}}) {{$lead->telefone}}</td>
                <td>{{$lead->email}}</td>
                <td>{{$lead->tipo->nome}}</td>
                <td>{{$lead->plano->nome}}</td>
                <td>{{$lead->extra}}</td>
            </tr>
        @endforeach
    </tbody>
</table>