<div
    class="lead-card-1"
    data-tipo="{{$lead->tipo_id}}"
    data-ddd="{{$lead->ddd}}">

    <!-- Seção do Logo (100% de altura) -->
    <div class="lead-logo-2">
        <img class="logo-image-3" src="{{url('storage/'.$lead->plano->logo)}}" />
    </div>

    <!-- Seção de Detalhes e CTA -->
    <div class="lead-details-4">

        <!-- Horário no canto superior direito -->
        <div class="lead-time-5">
            <i class="fas fa-clock"></i> <small>{{$lead->horario_partida}}</small>
        </div>

        <div class="lead-info-6">
            <span class="lead-plan-7">PLANO {{strtoupper($lead->tipo->nome)}}</span>
            <span class="lead-price-8">R$ {{$lead->preco}}</span>
            <span class="lead-age-9">Perfil da lead: {{$lead->idade}}</span>
            <span class="lead-location-10">Localização: DDD {{$lead->ddd}}</span>
            <span class="lead-preference-11">Preferência de plano: {{$lead->plano->nome}}</span>
            <span class="lead-cnpj-12">Possui CNPJ: {{ $lead->cnpj ? 'Sim' : 'Não' }}</span>
        </div>

        <!-- Seção do Botão -->
        <div class="lead-button-13">
            <button class="cta-button-14" onclick="atenderLead('{{$lead->id}}')">COMPRAR LEAD</button>
        </div>
    </div>
</div>
