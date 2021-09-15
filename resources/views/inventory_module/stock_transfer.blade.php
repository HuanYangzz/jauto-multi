@extends('layouts.layout')

@push('stylesheets')
<style>
    option:checked {
        background-color:grey !important;
        color:white !important;
    }
</style>
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
                    <li class="breadcrumb-item"><a href="{{url('/inventory')}}">Inventory</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Stock Transfer ( {{sizeof($list)}} )</a>
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
            <div class="table-responsive">
                <table id="table-transfer" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Stock Out Date</th>
                        <th>Code</th>
                        <th>Branch From</th>
                        <th>Branch To</th>
                        <th>Stock No</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($st_list as $s)
                        <tr class="clickable-row" @if($s->status=="NEW") onclick="pop('{{$s->code}}')" @else  onclick="pop('{{$s->code}}',false)"  @endif>
                            <td>{{$s->transfer_date}} <br/> {{$s->transfer_duration}}</td>
                            <td>{{$s->code}}</td>
                            <td>{{$s->from_name}} <br/> {{$s->from_code}}</td>
                            <td>{{$s->to_name}} <br/> {{$s->to_code}}</td>
                            <td>{{$s->stock_no}}</td>
                            <td>{{$s->status}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="modal_transfer" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="transfer_form" class="form-vertical" method="POST" action="{{url('/inventory/transfer_stock')}}">
                <input type="hidden" id="review_type" name="type" value="OUT">
                {{Form::token()}}
                <div class="modal-header">
                    <h4 class="modal-title"><span>Transfer</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <select class="form-control" id="branch_from" name="branch_from" onchange="load_stock()" data-placeholder="Please select Source Branch">
                            <option value=""></option>
                                @if($branches)
                                <option value="0">UNASSIGNED</option>
                                @foreach($branches as $m)
                                    <option value="{{$m->id}}">{{$m->branch_code}} - {{$m->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1"></div>
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <select class="form-control" id="branch_to" name="branch_to" onchange="verify()" data-placeholder="Please select Branch to">
                                <option value=""></option>
                                @if($branches)
                                    @foreach($branches as $m)
                                    <option value="{{$m->id}}">{{$m->branch_code}} - {{$m->name}}</option>
                                    @endforeach
                                @endif
                                </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <select class="form-control" id="available_stock" name="available_stock" multiple="" style="height: 300px">

                            </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                            <button class="btn btn-default btn-multi-group" type="button" onclick="add_item()">></button>
                            <button class="btn btn-default btn-multi-group" type="button" onclick="remove_item()"><</button>
                            <button class="btn btn-default btn-multi-group" type="button" onclick="add_all_item()">>></button>
                            <button class="btn btn-default btn-multi-group" type="button" onclick="remove_all_item()"><<</button>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                            <select class="form-control" id="model_assign" name="model_assign[]" multiple="" style="height: 300px">
                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" title="Cancel"><i class="fa fa-times fa-lg"></i></button>
                    </div>
                    <div class="modal-footer-right">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="Save" onclick="select_all()"><i class="fa fa-save fa-lg"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    



    <div class="modal fade" id="modal_confirm" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-vertical">
                {{Form::token()}}
            <div class="modal-header">
            <h4 class="modal-title"><span id="review_title">Stock Out</h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                <table id="datatable-item" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Vehicle Model</th>
                            <th>Color / Chassis</th>
                            <th>Branch From</th>
                            <th>Branch To</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                </table>
            </div>
            </div>
            <div class="modal-footer">
                <div class="modal-footer-left">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" title="Cancel"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-footer-right">
                    <button type="button" class="btn btn-default" data-toggle="tooltip" title="Download Stock Transfer Form" disabled><i class="fa fa-upload fa-lg"></i></button>
                    <button type="button" class="btn btn-primary" data-toggle="tooltip" title="Confirm" onclick="confirm_submit()"><i class="fa fa-check fa-lg"></i></button>
                </div>
            </div>
        </form>
        </div>
        </div>
    </div>

    <div class="modal fade" id="modal_confirm_in" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-vertical" id="transfer_out_form" action="{{url('/inventory/confirm_transfer_out')}}" method="POST">
                {{Form::token()}}
                <input type="hidden" id="review_code" name="code" value="">
            <div class="modal-header">
            <h4 class="modal-title"><span id="review_title">Stock In</h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                <table id="datatable-item-in" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Vehicle Model</th>
                            <th>Color / Chassis</th>
                            <th>Branch From</th>
                            <th>Branch To</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                </table>
            </div>
            </div>
            <div class="modal-footer">
                <div class="modal-footer-left">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" title="Cancel"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-footer-right">
                    <button id="btn-download-out" type="button" class="btn btn-default" data-toggle="tooltip" title="Download Stock Transfer Form" onclick="download_out()"><i class="fa fa-download fa-lg"></i></button>
                    <button id="btn-submit-in" type="button" class="btn btn-primary" data-toggle="tooltip" title="Confirm" onclick="confirm_in()"><i class="fa fa-check fa-lg"></i></button>
                </div>
            </div>
        </form>
        </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>
$(function(){
    $("#table-transfer").DataTable({
        'pageLength':50,
        "lengthChange": false,
        'scrollX':true,
        'order':[],
        'dom': 'Bfrtip',
        'buttons': [
            {
                className:'btn btn-default pull-left',
                text: '<i class="fa fa-exchange-alt"></i>',
                titleAttr: 'Transfer',
                action: function(){
                    $("#modal_transfer").modal('show');
                }
            },
            {
                className:'btn btn-default btn-class-filter',
                text: '<i class="fa fa-filter"></i> Status',
                titleAttr: 'Select Status'
            }
        ]
    });

    var options = "<option value=''>All Status</option>";
    options += "<option value='NEW'>NEW</option>";
    options += "<option value='COMPLETED'>COMPLETED</option>";
    
    $(".btn-class-filter").html('<select id="class-filter" data-placeholder="select class">'+options+'</select>');
    $("#class-filter").chosen();
    $("#class-filter").chosen().change(function(){
        $("#table-transfer").DataTable().draw();
    });

    $("#table-transfer").dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var td_class = data[5];
            var filter = $('#class-filter').chosen().val();
            if(filter=="")
            {
                return true;   
            }
            
            if (td_class == filter)
            {
                return true;
            }
            return false;
        }
    );
})

function download_in()
  {
    var code = $("#review_code").val();

    window.open('{{url("/inventory/download_stock_transfer/IN/")}}'+"/"+code);
  }

function download_out()
{
    var code = $("#review_code").val();

    window.open('{{url("/inventory/download_stock_transfer/OUT/")}}'+"/"+code);
}

function pop(code,clickable=true)
{
    if(!clickable)
    {
        $("#btn-submit-in").attr('disabled',true);
    }
    else
    {
        $("#btn-submit-in").attr('disabled',false);
    }

    $.get('{{url("/inventory/stock_transfer/")}}/'+code,function(res){
        if(res && res.list)
        {
        $("#review_code").val(code);
        var list = res.list;
        $("#datatable-item-in tbody").empty();
        for(var i=0;i<list.length;i++)
        {
            var model = list[i].model;
            var stock_id = list[i].id;
            var text = list[i].innerText;
            var from = list[i].from_name;
            var to = list[i].to_name;
            var html = "<tr><td>"+model+"</td><td>"+text+"</td><td>"+from+"</td><td>"+to+"</td></tr>";
            $("#datatable-item-in tbody").append(html);
        }
        $("#review_type").val("IN");
        $("#modal_confirm_in").modal('show')
        }
    })
}

function confirm_in()
{
    var code = $("#review_code").val();
    $("#transfer_out_form").submit();
}


function confirm_submit()
{
    $("#transfer_form").submit();
}

function select_all()
{
    $("#model_assign option").prop('selected',true);
    $("#modal_transfer").modal('hide').on('hidden.bs.modal',function(){
    $("#modal_transfer").modal('hide').off('hidden.bs.modal');
    $("#modal_confirm").modal('show').on('shown.bs.modal',function(){
        $("#review_title").html("Stock Out");
        $("#review_type").val("OUT");
        var list = $("#model_assign option:selected").clone();
        var from = $("#branch_from option:selected")[0].innerHTML;
        var to = $("#branch_to option:selected")[0].innerHTML;
        $("#datatable-item tbody").empty();
        for(var i=0;i<list.length;i++)
        {
        var model = $(list[i]).data('header');
        var stock_id = $(list[i]).data('id');
        var text = list[i].innerText;
        var color = $(list[i]).data('color');
        var html = "<tr><td>"+model+"</td><td>"+text+"</td><td>"+from+"</td><td>"+to+"</td></tr>"
        $("#datatable-item tbody").append(html);
        }
    });
    });
}

function remove_item()
{
    var items = $("#model_assign option:selected").clone();
    $("#available_stock").prepend(items);
    $("#model_assign option:selected").remove();
    refresh();
}

function remove_all_item()
{
    var items = $("#model_assign option").clone();
    $("#available_stock").prepend(items);
    $("#model_assign").empty();
    refresh();
}

function add_all_item()
{
    var items = $("#available_stock option").clone();
    $("#model_assign").prepend(items);
    $("#available_stock option").remove();
    refresh();
}

function add_item()
{
    var items = $("#available_stock option:selected").clone();
    $("#model_assign").prepend(items);
    $("#available_stock option:selected").remove();
    refresh();
}


function compare(a,b)
{
    if(a.text > b.text)
    {
        return 1;
    }

    if(a.text < b.text)
    {
        return -1;
    }

    return 0;
}

function refresh()
{
    var box = ['available_stock','model_assign'];

    for(var b=0;b<2;b++)
    {
        var list = $("#"+box[b]+" option").clone();
        $("#"+box[b]+"").empty();
        var rows = [];
        for(var i=0;i<list.length;i++)
        {
            var header = rows.find(h=>h.text == $(list[i]).data('header'));
            if(!header)
            {
                var data ={'text':$(list[i]).data('header'),'stock':[{'id':$(list[i]).data('id'),'text':list[i].innerText,'color':$(list[i]).data('color')}]}
                rows.push(data);
            }
            else
            {
                header.stock.push({'id':$(list[i]).data('id'),'text':list[i].innerText,'color':$(list[i]).data('color')});
            }
        }
        rows.sort(compare);

        for(var i=0;i<rows.length;i++)
        {
            var head = "<optgroup label='"+rows[i].text+"'>";
            rows[i].stock.sort(compare);
            for(var j=0;j<rows[i].stock.length;j++)
            {
                head += "<option data-color='"+rows[i].stock[j].color+"' data-header='"+rows[i].text+"' data-id='"+rows[i].stock[j].id+"' value='"+rows[i].stock[j].id+"' data-text='"+rows[i].stock[j].text+"'>"+rows[i].stock[j].text+"</option>";
            }
            head += "</optgroup>";
            $("#"+box[b]+"").prepend(head);
        }
    }
}

function load_stock()
{
    $("#available_stock").empty();
    $("#model_assign").empty();
    var id = $("#branch_from").val();
    
    $.get('{{url("/inventory/getstock/")}}/'+id,function(data){
    if(data)
    {
        for(var j=0;j<data.length;j++)
        {
            $("#available_stock").append("<option data-color='"+data[j].color+"' data-header='"+data[j].header+"' data-id='"+data[j].id+"' value='"+data[j].id+"' data-text='"+data[j].text+"'>"+data[j].text+"</option>");
        }
        refresh();
        verify();
    }
    });
}

function verify()
{
    var from_id = $("#branch_from").val();
    var to_id = $("#branch_to").val();
    if(from_id == to_id)
    {
        new PNotify({nonblock: {nonblock: !0},
            text: 'Invalid branch selected',
            type: 'warning',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
        });

        return;
    }

    if(from_id!="" && to_id!="")
    {
        $(".btn-multi-group").attr('disabled',false);
    }
    else
    {
        $(".btn-multi-group").attr('disabled',true);
    }
}

$("#modal_transfer").on('shown.bs.modal',function(){
    $("#branch_from").chosen();
    $("#branch_to").chosen();
});

@if($message!='')
    $(function(){
    new PNotify({nonblock: {nonblock: !0},
        text: '{{$message}}',
        type: 'success',
        styling: 'bootstrap3',
        hide:true,
        delay: 3000
    });
})
@endif
</script>
@endpush