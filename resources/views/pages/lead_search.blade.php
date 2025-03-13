<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
<input type="hidden" id="totalLeadsCarregada" value="{{count($leads)}}"/>

@php
$totalLeadsDisponivel = 0;
@endphp
@foreach($leads as $lead)
@if($lead->disponivel)
    <div class="col-12 justify-content-center">
        @include("components.lead")
    </div>
    @php
        $totalLeadsDisponivel += 1;
    @endphp
@endif
@endforeach

@if($totalLeadsDisponivel == 0)
    <h4 style="text-align: center; margin-top: 25px;">Infelizmente no momento as leads para este DDD
        já foram todas vendidas, fique atento que em breve entrarão mais leads no horário
        de funcionamento da plataforma
    </h4>
@endif
