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
            <div class="table-responsive">
                <table id="table-fee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr class="clickable-row" onclick="window.location.href='{{ url('/inventory/fee/'.$item->item_code)}}'">
                            <td>{{$item->code}}</td>
                            <td>{{$item->description}}</td>
                            <td>{{$item->status}}</td>
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
    $("#table-fee").DataTable({
        "stateSave": true,
        "lengthChange": false,
        'scrollX':true,
        'order':[[0,"desc"]],
        'pageLength':50,
        'dom': 'Bfrtip',
        'buttons': [
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
        $("#table-fee").DataTable().draw();
    });

    $("#table-fee").dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var td_class = data[2];
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