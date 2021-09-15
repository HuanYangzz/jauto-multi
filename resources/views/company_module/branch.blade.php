@extends('layouts.layout')

@push('stylesheets')

@endpush

@section('main_container')

<div class="page-breadcrumb">
  <div class="row">
      <div class="col-7 align-self-center">
          <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Day {{Auth::user()->name}}!</h3>
          <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}"></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/company/profile')}}">Company</a>
                    </li>
                    @if($type=="BRANCH")
                    <li class="breadcrumb-item"><a href="{{url('/company/branches')}}">Branch Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Branch</a>
                    </li>
                    @else
                    <li class="breadcrumb-item"><a href="{{url('/company/brokers')}}">Broker Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Broker</a>
                    </li>
                    @endif
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
            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                <li class="nav-item">
                    <a href="#detail" data-toggle="tab" aria-expanded="true"
                        class="nav-link rounded-0 active">
                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                        <span class="d-lg-block">Branch Detail</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id='pic_link' href="#pic" data-toggle="tab" aria-expanded="false"
                        class="nav-link rounded-0">
                        <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                        <span class="d-lg-block">Branch Manager</span>
                    </a>
                </li>
            </ul>

            <div class="ln_solid"></div>
            <div class="tab-content">
                <div class="tab-pane show active" id="detail">
                    <form id="form-branch" method="POST" action="{{url('/company/branch/update')}}">
                        {{Form::token()}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <input type="hidden" name="type" value="{{$type}}">
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Branch Code</label>
                                    <input type="text" class="form-control" value="{{$item->branch_code}}" placeholder="AUTO GENERATE" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Branch Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
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
                            </div>
                            <div class="col-sm-4 col-xs-12">
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
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$item->email}}">
                                </div>
                                <div class="form-group">
                                    <label>Email CC</label>
                                    <input type="email" class="form-control" id="email_cc" name="email_cc" value="{{$item->email_cc}}">
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
                                    <label>Remarks</label>
                                    <textarea class="form-control mid-text-area" name="remarks">{{$item->remarks}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-footer">
                                    <div class="modal-footer-left">
                                        @if($type=="BRANCH")
                                            <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/branches')}}'"><i class="fa fa-list"></i></button>
                                        @else
                                            <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/brokers')}}'"><i class="fa fa-list"></i></button>
                                        @endif
                                    </div>
                                    <div class="modal-footer-right">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="pic">
                    <div class="table-responsive">
                        <table id="datatable-pic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Job Title</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Office No</th>
                                    <th>Ext</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($list)
                                @foreach($list as $pic)
                                    <tr class="clickable-row" onclick="load_pic()">
                                        <td>{{$pic->index}}</td>
                                        <td>{{$pic->job_title}}</td>
                                        <td>{{$pic->name}}</td>
                                        <td><a href="tel:{{$pic->contact_1}}">{{$pic->contact_1}}</a></td>
                                        <td><a href="tel:{{$pic->contact_2}}">{{$pic->contact_2}}</a></td>
                                        <td>{{$pic->ext}}</td>
                                        <td><a href="mailto:{{$pic->email}}">{{$pic->email}}</a></td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="modal-footer">
                                <div class="modal-footer-left">
                                    <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/branches')}}'"><i class="fa fa-list"></i></button>
                                </div>
                                <div class="modal-footer-right">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('modal')
<div id="modal-add-pic" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <form action="{{url('/company/update_pic')}}" method="POST">
        <input type="hidden" name="branch_id" value="{{$item->id}}">
        <input type="hidden" id="pic_ids" name="pic_ids" value="">
        <input type="hidden" name="type" value="{{$type}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Branch Manager</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="datatable-pic-select" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Office No</th>
                                <th>Ext</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee_list as $pic)
                                    <tr class="clickable-row {{$pic->class}}" data-id="{{$pic->id}}">
                                    <td>{{$pic->name}}</td>
                                    <td>{{$pic->email}}</td>
                                    <td>{{$pic->contact_1}}</td>
                                    <td>{{$pic->contact_2}}</td>
                                    <td>{{$pic->ext}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-footer-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endpush('modal')

@endsection

@push('scripts')
<script>
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

    function show_website(type)
    {
        var url = $("#"+type).val();
        if(verify() && url != "")
        {
            window.open(url);
        }
    }

    $("#pic_link").on('shown.bs.tab',function(){
        $("#datatable-pic").DataTable().draw();
    })

    $("#datatable-pic").DataTable({
        "lengthChange": false,
        'scrollX':true,
        'order':[[0,"desc"]],
        'dom':'Bfrtip',
        'buttons':[
            {
                className:'btn btn-default pull-left',
                text: '<i class="fa fa-plus"></i>',
                titleAttr: 'New',
                action: function(){
                    load_pic();
                }
            }
        ]
    });

    function load_pic()
    {
        $("#pic_id").val('');
        $("#pic_job_title").val('');
        $("#pic_name").val('');
        $("#pic_ext").val('');
        $("#pic_email").val('');
        $("#pic_contact").val('');
        $("#pic_office").val('');
        $("#pic_contact_prefix").val('');
        $("#pic_office_prefix").val('');

        $("#modal-add-pic").modal('show');
    }

    $("#modal-add-pic").on('shown.bs.modal',function(){
        $("#modal-add-pic").off('shown.bs.modal');
        
        $("#datatable-pic-select").DataTable({
          "lengthChange": false,
          "drawCallback": function( settings ) {
            highlight();
          }
        });
      })

    function highlight(){
        var ids = [];
        var table = $('#datatable-pic-select tbody')[0];
        for (var i=0;i < table.rows.length;i++){
            table.rows[i].onclick= function () {
                var data = $(this).data();
                if(!data.id)
                {
                    return;
                }
                if(!this.hilite){
                unhighlight();
                this.origColor=this.style.backgroundColor;
                this.style.backgroundColor='#BCD4EC';
                this.hilite = true;
                this.classList.add('selected');
                ids.push(data.id);
                }
                else{
                this.style.backgroundColor=this.origColor;
                this.hilite = false;
                this.classList.remove('selected');
                }
            }
        }
        $("#pic_ids").val(ids.join(','));
    }
    
    function unhighlight(){
        var table = $('#datatable-pic-select tbody')[0];
        for (var i=0;i < table.rows.length;i++){
            var row = table.rows[i];
            row.style.backgroundColor=row.origColor;
            row.hilite = false;
            row.classList.remove('selected');
        }
    }

    function verify()
    {
        $("#form-branch").validate({
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

        return $("#form-branch").valid();
    }

    $("input").on('input',function(){
        verify();
    })

    $(function(){
        @if($message)
        new PNotify({nonblock: {nonblock: !0},
            text: '{{$message}}',
            type: 'success',
            
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
        });
        @endif
    })
</script>
@endpush