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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Presales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/presales/event')}}">Event Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Event</a>
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
            <form id="form-event" method="POST" action="{{url('/presales/event/update')}}">
                {{Form::token()}}
                <input type="hidden" name="id" value="{{$item->id}}">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Event Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{$item->code}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                        <div class="form-group">
                            <label>Event Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Event Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{$item->location}}" required>
                        </div>
                        <div class="form-group">
                            <label>Event Display Name</label>
                            <input type="text" class="form-control" id="display_name" name="display_name" value="{{$item->display_name}}" required>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Start At</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="start_at" name="start_at" value="{{$item->start_at}}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="clear_date('start_at')"><i class="fa fa-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>End At</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="end_at" name="end_at" value="{{$item->end_at}}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="clear_date('end_at')"><i class="fa fa-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control long-text-area" name="remarks">{{$item->remarks}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="modal-footer">
                            <div class="modal-footer-left">
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/presales/events')}}'"><i class="fa fa-list"></i></button>
                                <button type="button" class="btn btn-danger" onclick="deactivate()" @if($item->status!="ACTIVE") disabled @endif><i class="fa fa-ban"></i></button>
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

<!-- Danger Alert Modal -->
<div id="modal-ban" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{url('/presales/event/deactivate')}}">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title" id="modal-title">De-activate event</h4>
                </div>
                <div class="modal-body">
                        {{Form::token()}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                    <div class="form-group">
                        <label>Confirm de-activate this event?</label>
                        <textarea class="form-control mid-text-area" name="description" placeholder="Please provide reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i></button>
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
        @if($message)
            new PNotify({nonblock: {nonblock: !0},
                text: '{{$message}}',
                type: 'success',
                
                styling: 'bootstrap3',
                hide:true,
                delay: 3000
            });
        @endif

        @if($item->status=="INACTIVE")
        $("input").attr('disabled','disabled');
        $(".btn-dark").attr('disabled','disabled');
        $(".btn-primary").attr('disabled','disabled');
        $(".btn-ban").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
        $("textarea").attr('disabled','disabled');
        @endif
    })
    
    function deactivate()
    {
        $("#modal-ban").modal('show');
    }

    function clear_date(type)
    {
        $("#"+type).val("");
    }
	</script>
@endpush