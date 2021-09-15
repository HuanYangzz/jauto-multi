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
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Fee Listing ( {{sizeof($list)}} )</a>
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
            <form>
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" value="{{$group->code}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" value="{{$group->description}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status" class="form-control">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="INACTIVE">INACTIVE</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-fee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Brand / Series</th>
                        <th>Model</th>  
                        <th>Cost (RM)</th>
                        <th>Individual (RM)</th>
                        <th>Company Private (RM)</th>
                        <th>Company Commercial (RM)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td>{{$item->vehicle_code}}</td>
                            <td>{{$item->brand}} - {{$item->model}}</td>
                            <td>{{$item->variant}}</td>
                            <td><input type="tel" class="input-edit form-control" value="{{number_format($item->cost,2)}}" onchange="edit_price(this,0,{{$item->id}})" oninput="maxLengthCheck(this)" maxlength="11" /></td>
                            <td><input type="tel" class="input-edit form-control" value="{{number_format($item->price_1,2)}}" onchange="edit_price(this,1,{{$item->id}})" oninput="maxLengthCheck(this)" maxlength="11" /></td>
                            <td><input type="tel" class="input-edit form-control" value="{{number_format($item->price_2,2)}}" onchange="edit_price(this,2,{{$item->id}})" oninput="maxLengthCheck(this)" maxlength="11" /></td>
                            <td><input type="tel" class="input-edit form-control" value="{{number_format($item->price_3,2)}}" onchange="edit_price(this,3,{{$item->id}})" oninput="maxLengthCheck(this)" maxlength="11" /></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">

                <div class="col-12">
                    <div class="modal-footer">
                        <div class="modal-footer-left">
                            <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/inventory/fee_list')}}'"><i class="fa fa-list"></i></button>
                        </div>
                        <div class="modal-footer-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('modal')
<!-- Danger Alert Modal -->
<div id="modal-ban" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="form-status" method="POST" action="{{url('/inventory/fee_group/update_status')}}">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title" id="modal-title">De-activate Fee</h4>
                </div>
                <div class="modal-body">
                        {{Form::token()}}
                        <input type="hidden" name="id" value="{{$group->id}}">
                        <input type="hidden" name="status" id="hidden-status" value="{{$group->status}}">
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
    $("#datatable-fee").DataTable({
        "stateSave": true,
        "lengthChange": false,
        'scrollX':true,
        'order':[[0,"desc"]],
        'pageLength':50
    });

    $("#status").val('{{$group->status}}');
    $("#status").chosen();

    $("#status").on("change",function(){
        var status = $("#status").val();
        $("#hidden-status").val(status);
        if(status=="INACTIVE")
        {
            $("#modal-ban").modal('show');
        }
        else
        {
            $("#form-status").submit();
        }
    })
})

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

function edit_price(input,type,id)
{
  var num_val = input.value;
  var value = $(input).val().replace(',','');
    value = parseFloat(value);
    $(input).val(Number(value.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));

  $.post("{{url('/inventory/update_fee')}}",{type:type,value:value,id:id}).done(function(data){
        new PNotify({nonblock: {nonblock: !0},
          text: 'Data Saved',
          type: 'success',
          styling: 'bootstrap3',
          hide:true,
          delay: 3000
      });
      var next = $(input).parent().next().find('.input-edit');
      if(next.length>0)
      {
        $($(next)[0]).focus().select();
      }
      else
      {
        var next = $(input).parent().parent().next().find('.input-edit');
        if(next.length>0)
        {
            $($(next)[0]).focus().select();
        }
      }
    });
}

function reset_status()
{
    $('#hidden-status').val('ACTIVE');
    $('#status').val('ACTIVE');
    $('#status').trigger('chosen:updated')
}
</script>
@endpush