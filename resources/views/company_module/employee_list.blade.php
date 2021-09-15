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
                    @if($type=="EMPLOYEE")
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Employee Mangement ( {{sizeof($list)}} )</a>
                    </li>
                    @else
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Salesman Mangement ( {{sizeof($list)}} )</a>
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
            <div class="table-responsive">
                <table id="table-employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Full Name</th>
                        <th>Contact No</th>
                        <th>Identity</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr class="clickable-row @if($item->status=='INACTIVE') grey-highlight @endif" onclick="pop('{{$item->code}}')">
                            <td>{{$item->code}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->full_name}}</td>
                            <td>{{$item->contact_str}}</td>
                            <td>{{$item->identity}}</td>
                            <td>{{$item->email}}</td>
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
    $("#table-employee").DataTable({
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
                    @if($type=="EMPLOYEE")
                    window.location.href = '{{url("/company/employee/0")}}';
                    @else
                    window.location.href = "{{url('/company/salesman/0')}}";
                    @endif
                }
            },{
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
        $("#table-employee").DataTable().draw();
    });

    $("#table-employee").dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var td_class = data[6];
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
    @if($type=="EMPLOYEE")
    window.location.href = "{{url('/company/employee/')}}/"+code;
    @else
    window.location.href = "{{url('/company/salesman/')}}/"+code;
    @endif
}
</script>
@endpush