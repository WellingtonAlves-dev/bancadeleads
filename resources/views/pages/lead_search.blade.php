<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
<input type="hidden" id="totalLeadsCarregada" value="{{count($leads)}}"/>

@foreach($leads as $lead)
@if($lead->disponivel)
    <div class="col-12 justify-content-center">
        @include("components.lead")
    </div>
@endif
@endforeach