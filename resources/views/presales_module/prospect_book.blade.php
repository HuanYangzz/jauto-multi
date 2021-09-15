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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Presales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Prospect Contact Book ( {{sizeof($list)}} )</a>
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
    @hasanyrole('System Admin|Branch Manager|Manager|Admin')
    <div class="card-group">
        <div class="card border-right">
            <div class="card-body clickable-row" onclick="duplicate_check()">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium">{{$no}}</h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Duplicate Prospect</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body clickable-row" onclick="active_check()">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium">{{$active}}</h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Active Prospect</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card display-none">
        </div>
        <div class="card display-none">
        </div>
    </div>
    @endhasanyrole
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-prospect" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Vehicle No</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created at</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr class="clickable-row @if($item->status=='INACTIVE') grey-highlight @endif" onclick="pop('{{$item->code}}')">
                            <td>{{$item->code}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->contact_str}}</td>
                            <td>{{$item->vehicle_no}}</td>
                            <td>{{$item->status}}</td>
                            <td>{{$item->created_by_str}}</td>
                            <td>{{$item->created_at?$item->created_at:""}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('modal')
<div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-wrong h1"></i>
                    <h4 class="mt-2">Oh snap!</h4>
                    <p class="mt-3">Please select prospect to merge</p>
                    <button type="button" class="btn btn-light my-2"
                        data-dismiss="modal">Continue</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="activeModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Active Prospect</h4>
        </div>
        <div class="modal-body text-center">
            <div id="active_container"></div>
        </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times fa-lg"></i></button>
            </div>
            <div class="modal-footer-right"></div>
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
              Prospect Duplication Check
            </h4>
        </div>
        <div class="modal-center">
            <button id="btn-prev" type="button" class="btn btn-default" onclick="prev()"><i class="fa fa-chevron-left fa-lg"></i></button>
            <div>
                <button id="btn-1" type="button" data-id="1" class="btn btn-default" onclick="jump(this)">1</button>
                <button id="btn-2" type="button" data-id="2" class="btn btn-default" onclick="jump(this)">2</button>
                <button id="btn-3" type="button" data-id="3" class="btn btn-default" onclick="jump(this)">3</button>
            </div>
            <button id="btn-next" type="button" class="btn btn-default" onclick="next()"><i class="fa fa-chevron-right fa-lg"></i></button>
        </div>
        <div class="modal-body text-center">
          <div class="row">
            <div class="col-sm-12 col-xs-12 text-left">
              <h4 id="dupcheck_name"></h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-xs-12">
                    <div id="dupcheck_div" style="height:400px;overflow:scroll;">
                    </div>
              </div>
              <form id="upload_form" action="{{url('/presales/prospect/merge')}}" method="POST">
                {{Form::token()}}
                <input type="hidden" id="ids" name="ids" value="">
                <input type="hidden" id="hidden-first" name="first" value="">
              </form>
            </div>
        </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button class='btn btn-outline-primary pull-right' type='button' onclick='merge_prospect()'>Create as New Prospect</button>
            </div>          
        </div>
      </div>
    </div>
  </div>
@endpush

@endsection

@push('scripts')
<script>
$(function(){
    $("#table-prospect").DataTable({
        "stateSave": true,
        "lengthChange": false,
        "pageLength": 50,
        'scrollX':true,
        'order':[[0,"desc"]],
        'dom':'Bfrtip',
        'buttons':[
            {
                className:'btn btn-default pull-left',
                text: '<i class="fa fa-plus"></i>',
                titleAttr: 'New',
                action: function(){
                    window.location.href = '{{url("/presales/prospect")}}';
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
        $("#table-prospect").DataTable().draw();
    });

    $("#table-prospect").dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var td_class = data[4];
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
    window.location.href = "{{url('/presales/prospect_book/')}}/"+code;
}

function active_check()
    {
      if(0=={{$active}})
      {
        new PNotify({nonblock: {nonblock: !0},
          text: 'No Active',
          type: 'warning',
          styling: 'bootstrap3',
          hide:true,
          delay: 3000
      });
        return;
      }

      $.post('{{url("/presales/prospect/active_check")}}',function(res){
        if(res)
        {
          var container = $("#active_container");
          container.empty();

          for(var i=0;i<res.length;i++)
          {
            var name_str = res[i].name_str;
            var contact_str = res[i].contact_str;

            var table = "<h4 onclick='window.open(\"{{url("/presales/prospect_book/")}}/"+res[i].id+"\")' class='clickable-row text-left'>"+name_str+" ( "+contact_str+" )</h4>";
            table += "<div class='table-responsive'><table class='table table-striped table-bordered text-left v-top' cellspacing='0' width='100%'>";
              table += "<thead><tr><td width='18%'>Date</td><td width='17%'>Event</td><td width='35%'>Vehicle</td><td with='35%'>Salesman</td></tr></thead><tbody>";
            for(var j=0;j<res[i].history.length;j++)
            {
              var history = res[i].history[j];
              var vehicle_model = "";
              for(var k=0;k<history.details.length;k++)
              {
                vehicle_model += history.details[k].vehicle_model + "<br/>";
              }
              var tmp = "<tr class='clickable-row' onclick='window.open(\"{{url("/presales/prospect")}}/"+history.code+"\")'>"+'<td class="v-top">'+history.created_at+'</td><td class="v-top">'+history.event_name+'</td><td class="v-top">'+vehicle_model+'</td><td class="v-top">'+history.salesman_name+'<br/>'+history.salesman_contact+'</td></tr>';
              table+=tmp
            }
            table +="</tbody></table></div><div class='ln_solid'></div>";
            container.append(table);
          }          

          $("#activeModal").modal('show');
        }
      })
    }

    function duplicate_check()
    {

      $.post('{{url("/presales/prospect/duplicate_check")}}',function(res){
        if(res)
        {
          var container = $("#dupcheck_div");
          container.empty();
          var hide_class = "show-show";

          if(res.length==0)
          {
            new PNotify({nonblock: {nonblock: !0},
                text: 'No Duplicated',
                type: 'warning',
                styling: 'bootstrap3',
                hide:true,
                delay: 3000
            });
            return;
          }

          for(var i=0;i<res.length;i++)
          {
            var list = res[i].found_list;
            var title = (res[i].origin.full_name!=''?res[i].origin.full_name:res[i].origin.name)+" ( "+res[i].origin.contact_str+" )";
            if(i==0)
            {
              $("#dupcheck_name").html(title);
            }
            //var title = "<div class='x_title'><h2>"+(res[i].origin.full_name!=''?res[i].origin.full_name:res[i].origin.name)+" ( "+res[i].origin.contact_str+" )</h2><div class='clearfix'></div></div>";
            var table = "<div data-title='"+title+"' data-id='"+i+"' id='panel-"+i+"' class='table-responsive hide-hide "+hide_class+"'><table id='table-check-"+i+"' class='table table-striped table-bordered text-left v-top' cellspacing='0' width='100%'>";
            table += "<thead><tr><td width='10%'>Code</td><td width='27%'>Name</td><td width='25%'>Contact</td><td with='28%'>Address</td><td width='10%'>Action</td></tr></thead><tbody>";
            
            var button = "<button type='button' class='btn btn-outline-dark btn-highlight' onclick='trigger_button(this)'>Merge</button>";
            //var tmp = '<tr data-prospect_id="'+res[i].origin.id+'" data-select="0"><td class="v-top">'+res[i].origin.code+'</td><td class="v-top">'+res[i].origin.name+'</td><td class="v-top">'+res[i].origin.full_name+'<br/>'+res[i].origin.identity+'</td><td class="v-top">'+res[i].origin.contact_str+'</td><td class="v-top">'+res[i].origin.address+'</td><td class="text-center v-top">'+button+'</td></tr>';
            //table+=tmp
            for(var j in list)
            {
              var name_str = list[j].name_str;
              var full_name_str = list[j].full_name_str;
              var contact_str = list[j].contact_str;

              var button = "<button type='button' class='btn btn-outline-dark btn-highlight' onclick='trigger_button(this)'>Merge</button>";
              var tmp = '<tr data-prospect_id="'+list[j].id+'" data-select="0"><td class="v-top">'+list[j].code+'</td><td class="v-top">'+name_str+'<br/>'+full_name_str+'<br/>'+list[j].identity+'</td><td class="v-top">'+contact_str+'</td><td class="v-top">'+list[j].address+'</td><td class="text-center v-top">'+button+'</td></tr>';
              table+=tmp
            }  
            
            table +="</tbody></table></div>";
            container.append(table);
            hide_class="";
          }

          $("#myModal").modal('show');
        }
      });
    }

    $("#myModal").on("shown.bs.modal",function(){
        check_last();
      })
    
      function jump(input)
      {
        var id = $(input).data('id');
        var panel = $("#panel-"+id); 
        if(panel.length>0)
        {
          $(".show-show").removeClass('show-show');
          panel.addClass("show-show")
          var name = panel.data("title");
          $("#dupcheck_name").html(name);
        }
        check_last();
      }
    
      function next()
      {
        var id = $(".show-show").data('id');
        var panel = $("#panel-"+(id+1));
        if(panel.length>0)
        {
          $(".show-show").removeClass('show-show');
          panel.addClass("show-show")
          var name = panel.data("title");
          $("#dupcheck_name").html(name);
        }
        check_last();
      }
    
      function check_last()
      {
        clear_selection();
        
        var id = $(".show-show").data('id');
        $("#btn-1").html(id);
        $("#btn-2").html(id+1);
        $("#btn-3").html(id+2);
    
        var next = $("#panel-"+(id+1));
        if(next.length==0)
        {
          $("#btn-next").attr("disabled",true);
          $("#btn-3").attr("disabled",true);
          $("#btn-1").html(id-1);
          $("#btn-2").html(id);
          $("#btn-3").html(id+1);
        }
        else
        {
          $("#btn-next").attr("disabled",false);
          $("#btn-3").attr("disabled",false);
        }
    
        var prev = $("#panel-"+(id-1));
        if(prev.length==0)
        {
          $("#btn-prev").attr("disabled",true);
          $("#btn-1").attr("disabled",true);
          $("#btn-1").html(id+1);
          $("#btn-2").html(id+2);
          $("#btn-3").html(id+3);
        }
        else
        {
          $("#btn-prev").attr("disabled",false);
          $("#btn-1").attr("disabled",false);
        }
    
        $("#btn-1").data('id',$("#btn-1").html()-1);
        $("#btn-2").data('id',$("#btn-2").html()-1);
        $("#btn-3").data('id',$("#btn-3").html()-1);
    
      }
    
      function prev()
      {
        var id = $(".show-show").data('id');
        var panel = $("#panel-"+(id-1));
        if(panel.length>0)
        {
          $(".show-show").removeClass('show-show');
          panel.addClass("show-show")
          var name = panel.data("title");
          $("#dupcheck_name").html(name);
        }
        check_last();
      }

    function merge_prospect()
    { 
      var id = $(".show-show").data('id');
      var table = $("#table-check-"+id);
      var selected_rows = table.find(".row-selected-must");
      var ids = [];
      
      for(var i=0;i<selected_rows.length;i++)
      {
        var data = $(selected_rows[i]).data();
        ids.push(data.prospect_id);
      }
      
      if(ids.length>1)
      {
        $("#ids").val(ids);
        $("#upload_form").submit();
      }
      else
      {
          $("#myModal").modal('hide').on('hidden.bs.modal',function(){
              $("#myModal").off('hidden.bs.modal');
            $("#danger-alert-modal").modal('show');
          });
      }
    }

    $("#danger-alert-modal").on('hidden.bs.modal',function(){
        $("#myModal").modal('show');
    })
  
    function trigger_button(button)
    {
      var row = $(button).parent().parent();
      if($(row).data('select')==0)
      {
        if($("#hidden-first").val()=="")
        {
          $("#hidden-first").val($(row).data().prospect_id);
        }
  
        $(row).data('select',1);
        $(row).addClass("row-selected-must");
      }
      else
      {
        $(row).data('select',0);
        $(row).removeClass("row-selected-must");  
  
        if($("#hidden-first").val()==$(row).data().prospect_id)
        {
          $("#hidden-first").val()="";
        }
      }
    }
  
    function clear_selection()
    {
      $("#hidden-first").val("");
      $(".row-selected-must").removeClass("row-selected-must");
    }
</script>
@endpush