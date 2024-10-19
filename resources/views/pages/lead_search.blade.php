<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
<input type="hidden" id="totalLeadsCarregada" value="{{count($leads)}}"/>
@if(count($leads) == 0)
    <h1 style="text-align: center">Infelizmente no momento as leads para este DDD
        já foram todas vendidas, fique atento que em breve entrão mais leads no horário
        de funcionamento da plataforma
    </h1>
@endif
@foreach($leads as $lead)
@if($lead->disponivel)
    <div class="col-12 justify-content-center">
        @include("components.lead")
    </div>
@endif
@endforeach