@extends('layouts.layout')

@push('stylesheets')
<style>
.hide-hide{
    display:none;
}
    
.show-show{
    display:block;
    -webkit-animation: fadein 0.2s linear forwards;
    animation: fadein 0.2s linear forwards;
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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Sales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Customer Book ( {{sizeof($list)}} )</a>
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
                <table id="table-customer" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Full Name</th>
                        <th>Customer Type</th>
                        <th>Contact No</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Salesman</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr class="clickable-row higher-row" onclick="window.location.href='/sales/customer_book/{{$item->code}}'">
                        <td>{{$item->code}}</td>
                        <td class="long-col">{{$item->customer}} @if($item->customer_type=="COMPANY") <br/> ({{$item->full_name}}) @endif</td>
                        <td>{{$item->customer_type}}</td>
                        <td>{{$item->contact_str}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->created_by_str}}</td>
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
        $("#table-customer").dataTable({"stateSave": true,"lengthChange": false,'scrollX':true,'order':[[0,"desc"]],'dom':'Bfrtip',
        'buttons':[
            {
                className:'btn btn-default pull-left',
                text: '<i class="fa fa-plus"></i>',
                titleAttr: 'New',
                action: function(){
                    window.location.href = '{{url("/sales/customer_book_detail/NEW")}}';
                }
              }]});
        
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