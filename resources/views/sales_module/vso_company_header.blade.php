@php
$company = $param['company'];
$print = array_key_exists('print',$param)&&$param['print'];
$type = array_key_exists('type',$param)&&$param['type']?$param['type']:"OFFICIAL RECEIPT";
$vso_no = $param['vso_no'];
@endphp

<div class="row">
    <div class="col-sm-5 col-xs-5 text-left pull-right text-right">
        
            <span class="mb-start">
                    
                    @if($company->mail_address_1!=""){{$company->mail_address_1}} @endif
                    @if($company->mail_address_2!=""){{$company->mail_address_2}} <br/>@endif
                    @if($company->mail_postcode!=""){{$company->mail_postcode}} @endif
                    @if($company->mail_city!=""){{$company->mail_city}}  <br/>@endif
                    @if($company->mail_state!=""){{$company->mail_state}} @endif
                    @if($company->mail_country!=""){{$company->mail_country}} <br/>@endif
                </span>
                <span class="mb-start">
                    {{$company->phone_1_str}}
                </span>
                @if($company->fax_no_str && $company->fax_no_str!="")
                    <span class="mb-start">
                        {{$company->fax_no_str}}
                    </span>
                    @endif
            
                    @if($company->email && $company->email!="")
            <span class="mb-start">{{$company->email}}</span>
            @endif
            @if($company->website && $company->website!="")
            <span class="mb-start">{{$company->website}}</span>
            @endif
        </div>
    <div class="col-sm-7 col-xs-7 pull-left">
        <div>
            @if($company->logo_url!='')
                  <img id="image" src="{{$print?public_path($company->logo_url):url($company->logo_url)}}"
                     max-width="250" height="60" alt="Logo" />
            @endif
            <h2>
                <strong>{{$company->name}}</strong>
                @if($company->reg_no !="")<small>( {{$company->reg_no}} )</small>@endif
            </h2>
            <h2>
                <span>Service Tax License No: {{$company->license_no}}</span>
            </h2>
        </>
    </div>
</div>
</div>
    <div class="row">
            <div class="col-sm-4 col-xs-4">
                @if($type == "STOCK TRANSFER") <h2>TRANSFER FROM:<br/> {{$from}}</h2> @endif
            </div>
            <div class="col-sm-4 col-xs-4">
                    @if($type == "STOCK TRANSFER") <h2>TRANSFER TO:<br/> {{$to}}</h2> @endif
                </div>
    <div class="col-sm-4 col-xs-4">
            <h2 class="text-right">
                    @if($type == "STOCK TRANSFER") <br/> @endif
                     {{$type}} - <strong>{{$vso_no}}</strong></h2>
        </div>
</div>






@push('scripts')

<script>
$(function(){
    
})



</script>

@endpush