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
                    <li class="breadcrumb-item"><a href="{{url('/inventory')}}">Inventory</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Vehicle Management ( {{sizeof($list)}} )</a>
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
            <form id="form-vehicle">
                <div class="table-responsive">
                    <table id="datatable-vehicle" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Brand / Series</th>
                            <th>Variant</th>
                            <th>Make</th>
                            <th>Status</th>
                            <th>R.S.P</th>
                            <th>Min Price</th>
                            <th>Cost</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td>{{$item->vehicle_code}}</td>
                                <td>{{$item->brand}} - {{$item->model}}</td>
                                <td>{{$item->variant}}</td>
                                <td class="short-col">{{$item->make}}</td>
                                <td><select id="status_{{$item->id}}" class="form-control status-input" onchange="edit_status('{{$item->id}}')"><option @if($item->status=="ACTIVE") selected @endif</option>ACTIVE</option><option @if($item->status=="INACTIVE") selected @endif>INACTIVE</option></select></td>
                                <td><input type="tel" class="input-edit form-control" data-value="{{number_format($item->price,2)}}" value="{{number_format($item->price,2)}}" onchange="edit_price(this,{{$item->id}})" oninput="maxLengthCheck(this)" /></td>
                                <td><input type="tel" class="input-edit form-control" data-value="{{number_format($item->min_price,2)}}" value="{{number_format($item->min_price,2)}}" onchange="edit_min_price(this,{{$item->id}})" oninput="maxLengthCheck(this)" /></td>
                                <td><input type="tel" class="input-edit form-control" data-value="{{number_format($item->cost,2)}}" value="{{number_format($item->cost,2)}}" onchange="edit_cost(this,{{$item->id}})" oninput="maxLengthCheck(this)" /></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

@push('modal')
<!-- Danger Alert Modal -->
<div id="modal-ban" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="form-status" method="POST" action="{{url('/inventory/vehicle/update_status')}}">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title" id="modal-title">De-activate vehicle model</h4>
                </div>
                <div class="modal-body">
                        {{Form::token()}}
                        <input type="hidden" name="id" id="hidden-id" value="">
                        <input type="hidden" name="status" id="hidden-status" value="">
                    <div class="form-group">
                        <label>Confirm de-activate this item?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-light" data-dismiss="modal" onclick="reset_status()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-footer-right">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-ban"></i></button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endpush

@endsection

@push('scripts')
<script>
$(function(){
    $("#datatable-vehicle").DataTable({
        'pageLength':50,
        "lengthChange": false,
        'scrollX':true,
        'order':[[0,"desc"]],
        "stateSave": true
    });
})

function edit_status(id)
{
    var status = $("#status_"+id).val();
    $("#hidden-id").val(id);
    $("#hidden-status").val(status);
    if(status=="INACTIVE")
    {
        $("#modal-ban").modal('show');
    }
    else
    {
        $("#form-status").submit();
    }
}

function reset_status()
{
    var id = $("#hidden-id").val();

    $('#hidden-status').val('ACTIVE');
    $('#status_'+id).val('ACTIVE');
}

function edit_price(input,id)
{
  var num_val = input.value;
  var value = $(input).val().replace(',','');
    value = parseFloat(value);
    $(input).val(Number(value.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));

  var min = $(input).parent().next().children('.input-edit');
  var min_val = parseFloat($(min).val().replace(',',''));
  if(min_val > 0 && parseFloat(value) < parseFloat(min_val))
  {
    new PNotify({nonblock: {nonblock: !0},
        text: "Cost cannot lower that Minimum Price",
        type: "warning",
        styling: "bootstrap3",
        animation: "fade",
        hide: true,
        delay: 1000
    });
    $(input).val($(input).data('value'));
    $(input).focus().select();
    return false;
  }

  $(input).data('value',$(input).val());
  $.post("{{url('/inventory/vehicle/edit_price')}}",{value:value,id:id}).done(function(data){
        new PNotify({nonblock: {nonblock: !0},
          text: "Data Saved",
          type: "success",
          styling: "bootstrap3",
          animation: "fade",
          hide: true,
          delay: 1000
        });

        $(min).focus().select();
      });
}

function edit_min_price(input,id)
{  
  var num_val = input.value;
  var value = $(input).val().replace(',','');
    value = parseFloat(value);
    $(input).val(Number(value.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));

  var price = $(input).parent().prev().children('.input-edit');
  var price_val = parseFloat($(price).val().replace(',',''));
  if(price_val > 0 && parseFloat(value) > parseFloat(price_val))
  {
    new PNotify({nonblock: {nonblock: !0},
        text: "Minimum price cannot greater that Recommended Selling Price",
        type: "warning",
        styling: "bootstrap3",
        animation: "fade",
        hide: true,
        delay: 1000
    });
    $(input).val($(input).data('value'));
    $(input).focus().select();
    return false;
  }
  
  $(input).data('value',$(input).val());
  $.post("{{url('/inventory/vehicle/edit_min_price')}}",{value:value,id:id}).done(function(data){
      new PNotify({nonblock: {nonblock: !0},
        text: "Data Saved",
        type: 'success',
        styling: 'bootstrap3',
        hide:true,
        delay: 3000
      });

        var next = $(input).parent().next().children('.input-edit');
        if(next.length>0)
        {
          $($(next)[0]).focus().select();
        }
      });
}

function edit_cost(input,id)
{
  var num_val = input.value;
  var value = $(input).val().replace(',','');
    value = parseFloat(value);
    $(input).val(Number(value.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));

  var price = $(input).parent().prev().children('.input-edit');
  var price_val = parseFloat($(price).val().replace(',',''));
  if(price_val > 0 && parseFloat(value) > parseFloat(price_val))
  {
    new PNotify({nonblock: {nonblock: !0},
        text: "Cost cannot greater that Minimum Price",
        type: "warning",
        styling: "bootstrap3",
        animation: "fade",
        hide: true,
        delay: 1000
    });
    $(input).val($(input).data('value'));
    $(input).focus().select();
    return false;
  }
  
  $(input).data('value',$(input).val());
  $.post("{{url('/inventory/vehicle/edit_cost')}}",{value:value,id:id}).done(function(data){
    new PNotify({nonblock: {nonblock: !0},
    text: "Data Saved",
    type: 'success',
    styling: 'bootstrap3',
    hide:true,
    delay: 3000
  });

    var next = $(input).parent().parent().next().find('.input-edit');
    if(next.length>0)
    {
        $($(next)[0]).focus().select();
    }
    });
}

function maxLengthCheck(object) 
{
    if (object.value.length > 11)
    {
        object.value = object.value.slice(0, 11)
    }
    var str = object.value;
    var text = "";
    var dot = false;
    for(var i=0;i<object.value.length;i++)
    {
        if(i>0 && object.value[i]=='.' && !dot)
        {
        dot = true;
        text+= object.value[i];
        }

        if(!isNaN(object.value[i]))
        {
        text+= object.value[i];
        }
    }
    var dotindex = text.indexOf('.');
    if(text.length>1 && dotindex+2 >= text.length)
    {
        sub = text.substring(0,dotindex);
        var num = Number(sub);
        
        var raw = num.toLocaleString(undefined, {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
        });
        object.value = raw + text.substring(dotindex);
        return;
    }
    else if(dotindex>0)
    {
        var num = Number(text).toFixed(2);
        
        var raw = num.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
        })
        object.value= raw;
    }
    else
    {
        text = Number(text);
        text = text.toLocaleString(undefined, {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
        })
        object.value = text;
    }
}




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