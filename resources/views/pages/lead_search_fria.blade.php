<input type="hidden" id="totalLeads" value="{{$totalLeads}}"/>
<input type="hidden" id="totalLeadsCarregada" value="{{count($leads)}}"/>
@php
    $show_time = false;
@endphp
@foreach($leads as $lead)
    <div class="col-lg-4">
        @include("components.lead")
    </div>
@endforeach