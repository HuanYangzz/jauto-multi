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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Presales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Event Mangement ( {{sizeof($list)}} )</a>
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
                <table id="table-event" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Display Name</th>
                        <th>Event Location</th>
                        <th>Status</th>
                        <th>Start At</th>
                        <th>End At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr class="clickable-row @if($item->status=='INACTIVE') grey-highlight @endif" onclick="pop('{{$item->code}}')">
                            <td>{{$item->name}}</td>
                            <td>{{$item->display_name}}</td>
                            <td>{{$item->location}}</td>
                            <td>{{$item->status}}</td>
                            <td>{{$item->start_at?$item->start_at:""}}</td>
                            <td>{{$item->end_at?$item->end_at:""}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $("#table-event").DataTable({
        "stateSave": true,
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
                    window.location.href = '{{url("/presales/event/0")}}';
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
    options += "<option value='ACTIVE'>ACTIVE</option>";
    options += "<option value='INACTIVE'>INACTIVE</option>";
    
    $(".btn-class-filter").html('<select id="class-filter" data-placeholder="select class">'+options+'</select>');
    $("#class-filter").chosen();
    $("#class-filter").chosen().change(function(){
        $("#table-event").DataTable().draw();
    });

    $("#table-event").dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var td_class = data[3];
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

function pop(code)
{
    window.location.href = "{{url('/presales/event/')}}/"+code;
}
</script>
@endpush