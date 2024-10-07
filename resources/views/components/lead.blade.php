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
            <div class="lead-info-principal">
                <span class="lead-plan-7">PLANO {{strtoupper($lead->tipo->nome)}}</span>
                <span class="lead-price-8 text-success"><span>R$</span> {{$lead->preco}}</span>    
            </div>
            <div class="lead-info-secundaria">
                <div class="d-flex flex-column">
                    <span style="font-size: 24px" class="font-weight-bold">
                        <i class="far fa-user"></i>
                         {{$lead->idade}}
                    </span>
                </div>
                <div class="grupo-infos-btn">
                    <div>
                        <span class="lead-location-10">DDD {{$lead->ddd}}</span>
                        <span class="lead-cnpj-12 ml-2"><i class="far fa-address-card" aria-hidden="true"></i> {{ $lead->cnpj ? 'CNPJ' : 'CPF' }} </span>
                        {{-- <span class="lead-cnpj-12 ml-2"><i class="far fa-building" aria-hidden="true"></i> {{ strtoupper($lead->tipo->nome) }}</span> --}}
                    </div>
                    <!-- Seção do Botão -->
                    <button class="cta-button-14" onclick="atenderLead('{{$lead->id}}')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2_392)">
                            <path d="M10.5417 15.675C8.65333 15.675 7.91084 14.8317 7.81 13.75H5.79333C5.90333 15.7575 7.40667 16.885 9.16667 17.2608V19.25H11.9167V17.2792C13.7042 16.94 15.125 15.9042 15.125 14.025C15.125 11.4217 12.8975 10.5325 10.8167 9.99167C8.73584 9.45083 8.06667 8.89167 8.06667 8.02083C8.06667 7.02167 8.9925 6.325 10.5417 6.325C12.1733 6.325 12.7783 7.10417 12.8333 8.25H14.8592C14.795 6.67333 13.8325 5.225 11.9167 4.7575V2.75H9.16667V4.73C7.38833 5.115 5.95833 6.27 5.95833 8.03917C5.95833 10.1567 7.70917 11.2108 10.2667 11.825C12.5583 12.375 13.0167 13.1817 13.0167 14.0342C13.0167 14.6667 12.5675 15.675 10.5417 15.675Z" fill="#28A745"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_2_392">
                            <rect width="24" height="24" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                                    
                        COMPRAR AGORA</button>
                </div>


            </div>

        </div>
    </div>
</div>
