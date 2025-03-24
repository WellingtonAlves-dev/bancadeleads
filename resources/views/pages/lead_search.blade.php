<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
<input type="hidden" id="totalLeadsCarregada" value="{{count($leads)}}"/>

@php
$totalLeadsDisponivel = 0;
@endphp
@foreach($leads as $lead)
@if($lead->disponivel)
    <div class="col-lg-4">
        @include("components.lead")
    </div>
    @php
        $totalLeadsDisponivel += 1;
    @endphp
@endif
@endforeach

@if($totalLeadsDisponivel == 0)
    <h4 style="text-align: center; margin-top: 25px; background-color: #007fff; color: white; padding: 12px;">
        :( Que pena! Você chegou tarde e já acabaram todas as leads para este DDD!
        Mas não se preocupe, é só aguardar que logo serão adicionados mais leads.
    </h4>
@endif
