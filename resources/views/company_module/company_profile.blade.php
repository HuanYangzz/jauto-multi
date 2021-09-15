@extends('layouts.layout')

@push('stylesheets')

@endpush

@section('main_container')

<div class="page-breadcrumb">
  <div class="row">
      <div class="col-7 align-self-center">
          <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning {{Auth::user()->name}}!</h3>
          <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}"></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Company</a>
                    </li>
                  </ol>
              </nav>
          </div>
      </div>
  </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form id="form-company" method="POST" action="{{url('/company/update')}}">
                {{Form::token()}}
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Registration No</label>
                            <input type="text" class="form-control" id="reg_no" name="reg_no" value="{{$item->reg_no}}">
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Service Tax License No</label>
                            <input type="text" class="form-control" id="license_no" name="license_no" value="{{$item->license_no}}">
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        @include('components.file_upload_component',array('param'=>[
                            'item'=>$item,
                            'name'=>"company_logo",
                            'label'=>'Upload supporting document or images',
                            'type'=>"company_logo",
                            'verify'=>false,
                            'first'=>true,
                            'file_url'=>$item->logo_url,
                            'id'=>$item->logo_id,
                            'attr_name'=>"company_logo",
                            'disabled'=>false,
                            "multiple"=>false,
                            'class'=>"",
                            'target-id'=>$item?$item->id:0,
                            'allow_file'=>true,
                            'reload'=>false
                        ]))
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <h4>Contact Detail</h4>
                        <label><br/></label>
                        <div class="form-group">
                            <label>PIC Name</label>
                            <input type="text" class="form-control" id="person_in_charge" name="person_in_charge" value="{{$item->person_in_charge}}">
                        </div>
                        <div class="form-group">
                            <label>Phone (1)</label>
                            <input type="tel" class="form-control" id="phone_1" name="phone_1" value="{{$item->phone_1}}">
                        </div>
                        <div class="form-group">
                            <label>Phone (2)</label>
                            <input type="tel" class="form-control" id="phone_2" name="phone_2" value="{{$item->phone_2}}">
                        </div>
                        <div class="form-group">
                            <label>Fax No</label>
                            <input type="tel" class="form-control" id="fax_no" name="fax_no" value="{{$item->fax_no}}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$item->email}}">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <div class="input-group">
                                <input type="url" class="form-control" id="website" name="website" value="{{$item->website}}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="show_website('website')"><i class="fa fa-link"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <div class="input-group">
                                <input type="url" class="form-control" id="facebook" name="facebook" value="{{$item->facebook}}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="show_website('facebook')"><i class="fa fa-link"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <h4>Legal Address</h4>
                        <label><br/></label>
                        <div class="form-group">
                            <label>Address 1</label>
                            <input type="text" class="form-control" id="legal_address_1" name="legal_address_1" value="{{$item->legal_address_1}}">
                        </div>
                        <div class="form-group">
                            <label>Address 2</label>
                            <input type="text" class="form-control" id="legal_address_2" name="legal_address_2" value="{{$item->legal_address_2}}">
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="number" class="form-control" id="legal_postcode" name="legal_postcode" value="{{$item->legal_postcode}}" onchange="check_zip(this,'legal')" minlength="5" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" id="legal_city" name="legal_city" value="{{$item->legal_city}}">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" id="legal_state" name="legal_state" value="{{$item->legal_state}}">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" id="legal_country" name="legal_country" value="{{$item->legal_country}}">
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <h4>Mailing Address</h4>
                        <label><small><input id='same_address' type="checkbox"> same as legal address</small></label>
                        <div class="form-group">
                            <label>Address 1</label>
                            <input type="text" class="form-control" id="mail_address_1" name="mail_address_1" value="{{$item->mail_address_1}}">
                        </div>
                        <div class="form-group">
                            <label>Address 2</label>
                            <input type="text" class="form-control" id="mail_address_2" name="mail_address_2" value="{{$item->mail_address_2}}">
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="number" class="form-control" id="mail_postcode" name="mail_postcode" value="{{$item->mail_postcode}}" onchange="check_zip(this,'mail')" minlength="5" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" id="mail_city" name="mail_city" value="{{$item->mail_city}}">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" id="mail_state" name="mail_state" value="{{$item->mail_state}}">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" id="mail_country" name="mail_country" value="{{$item->mail_country}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="modal-footer">
                            <div class="modal-footer-left">
                            </div>
                            <div class="modal-footer-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('modal')

@endpush

@endsection

@push('scripts')
	<script>
    @if($message)
        new PNotify({nonblock: {nonblock: !0},
            text: '{{$message}}',
            type: 'success',
            
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
        });
    @endif

    function check_zip(input,type)
    {
        $.get('/general/check_zip/'+input.value,function(data){
        if(data)
        {
            $("#"+type+"_city").val(data.city.toUpperCase());
            $("#"+type+"_state").val(data.state.toUpperCase());
            $("#"+type+"_country").val(data.country.toUpperCase());
        }
        });
    }

    $("#same_address").on('click',function(){
        if($("#same_address").is(":checked"))
        {
            $("#mail_address_1").val($("#legal_address_1").val());
            $("#mail_address_1").attr('readonly','readonly');
            $("#mail_address_2").val($("#legal_address_2").val());
            $("#mail_address_2").attr('readonly','readonly');
            $("#mail_postcode").val($("#legal_postcode").val());
            $("#mail_postcode").attr('readonly','readonly');
            $("#mail_city").val($("#legal_city").val());
            $("#mail_city").attr('readonly','readonly');
            $("#mail_state").val($("#legal_state").val());
            $("#mail_state").attr('readonly','readonly');
            $("#mail_country").val($("#legal_country").val());
            $("#mail_country").attr('readonly','readonly');
        }
        else
        {
            $("#mail_address_1").removeAttr('readonly');
            $("#mail_address_2").removeAttr('readonly');
            $("#mail_postcode").removeAttr('readonly');
            $("#mail_city").removeAttr('readonly');
            $("#mail_state").removeAttr('readonly');
            $("#mail_country").removeAttr('readonly');
        }
    })
    
    function verify()
    {
        $("#form-company").validate({
            rules:{
                phone_1:{matches:"0[0-9]+",minlength:9, maxlength:11},
                phone_2:{matches:"0[0-9]+",minlength:9, maxlength:11},
                fax:{matches:"0[0-9]+",minlength:9, maxlength:11}
            },
            messages:{
                phone_1:"Please enter a valid phone number, sample 0125451300",
                phone_2:"Please enter a valid phone number, sample 0125451300",
                fax:"Please enter a valid fax number, sample 0125451300"
            },
            errorPlacement: function (error, element) {
                if (element.parent().is('.input-group'))
                   error.appendTo(element.parents(".form-group:first"));
                else
                   error.insertAfter(element);
             }
        });

        return $("#form-company").valid();
    }

    $("input").on('input',function(){
        verify();
    })

    function show_website(type)
    {
        var url = $("#"+type).val();
        if(verify() && url != "")
        {
            window.open(url);
        }
    }
	</script>
@endpush