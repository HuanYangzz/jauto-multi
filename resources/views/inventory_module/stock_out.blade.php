<title>MARCUS</title>



<link href="{{ public_path("css/bootstrap.min.css") }}" rel="stylesheet">
<link href="{{ public_path("css/font-awesome.min.css")}}" rel="stylesheet">
<link href="{{ public_path("css/gentelella.min.css") }}" rel="stylesheet">
<link href="{{ public_path("css/custom.css")}}" rel="stylesheet">

<style>
  body{
    height: {{ 1020 * (ceil($list->count()/20))}}px;
  }

  .page-body{
      height: 1020px;
  }

        @page {
            
        }

        .table>tbody>tr>td, .form-control, label, span, td>span, label
        {
          line-height: 12px !important;
        }

        .mb-start
        {
          display: block;
          font-size: 10px;
          line-height: 10px;
        }

        .table>tbody>tr>th
        {
          line-height: 10px;
        }

        .table>tbody>tr>td
        {
          padding-top:0;
          padding-bottom:5;
        }
        
        .table>tbody>tr.total-row>td
        {
          padding-top:5px;
          padding-bottom:5px;
        }

        .table{
            margin-bottom:220px;
        }

        .form-control, label, span
        {
          padding: 0 0 0 0;
        }

        .form-control
        {
          height: 18px;
        }

        h2{
          font-size:10px;
        }

        .big-font
        {
          font-size:14px !important;
        }

        .fourth-red
        {
          margin-left:0px;
          cursor:pointer !important;
          display: inline-block;
        }
        
        .fourth-red::after
        {
        content:"D";
        color:red;
        position: absolute;
        transform: translate(-100%, 0);
        }

table{
  font-size:10px;
}

body{
    background:white;
}

.error-text{
  color:red !important;
}

.table-responsive-pricing table {
  border-top: 1px solid black;
}


  .btn {
    display: none !important;
  }

  .no-print{
    display:none;
  }

  .form-control{
    border:none !important;
  }

  body * {
    visibility: hidden;
    color:black;
  }
  #section-to-print, #section-to-print * {
    visibility: visible;
  }
  #section-to-print {
    position: absolute;
    left: 0;
    top: 0;
    bottom:0;
    right:0;
  }

  .x_panel{
    border:none !important;
  }

.header-span, .form-control, span, label{
    line-height: 10px;
    font-size: 10px;
}



.header-span-icon i
{
  line-height: 10px;
}

.ss-small{
    margin-top:0px !important;
    margin-bottom:0px !important;
  }

  .page-break {
    page-break-after: always;
}

label{
    color:black;
}

.wrap-border{
    border: 1px solid black;
}

</style>

    <!-- page content -->
    <body>
              <div class="x_content" id="section-to-print">
                  <div class="page-body">
                  @include('sales_module.vso_company_header',array('param'=>[
                    'company'=>$company,
                    'type'=>"STOCK TRANSFER",
                    'vso_no'=>$code,
                    'print'=>true,
                    'from'=>$from,
                    'to'=>$to
                  ]))                  
                  
                
                <form id="pricing_form" class="form-vertical form-label-left">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <table id="datatable-responsive-pricing" class="table table-bordered table-responsive-pricing table-no-border" cellspacing="0" width="100%">
                            
                        <thead>
                        <tr>
                          <th class="short-col">No</th>
                          <th>Vehicle Model</th>
                          <th>Color</th>
                          <th>Reg No</th>
                          <th>Chassis</th>
                          <th>Engine No</th>
                        </tr>
                    </thead>
                        <tbody>
                        @php $i=0; @endphp
                      @foreach($list as $row)
                      @php $i++; @endphp
                      <tr>
                          <td class="short-col">{{$i}}</td>
                          <td>{{$row->model}}</td>
                          <td>{{$row->color}}</td>
                          <td>{{$row->reg_no}}</td>
                          <td>{{$row->chassis_no}}</td>
                          <td>{{$row->engine_no}}</td>
                      </tr>
                      @if($i%20==0 && $i!=0)
                            </tbody>
                        </table>
                    </div>
                  </div>
                  <div class="row" style="position: absolute;bottom:{{1020*($i/20) }}px;right:10px;left:10px;width:100%;">
              
                        <div class="col-sm-6 col-xs-6 text-left">
                            <strong>Driver</strong>
                                <div class="text-left wrap-border" style="padding-left:10px;">
                                    <br/><br/><br/><br/>
                                    <div class="border_bottom"></div>
                                    <span>&nbsp;<br/>
                                        <p>Name &nbsp;&nbsp;&nbsp;:</p>
                                    <p>IC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
                                    <p>Phone &nbsp;&nbsp;:</p>
                                    <p>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p><br/></span>
                                        
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-6 text-left">
                                    <strong>Transfer From</strong>
                                    <div class="text-left wrap-border" style="padding-left:10px;">
                                        <div style="width:45%;display:inline-block;">
                                        <br/><br/><br/>
                                        <div class="border_bottom"></div>
                                        <span><p>( Manager )</p></span>
                                    </div>
                                    <div style="width:45%;display:inline-block;">
                                            <br/><br/><br/>
                                            <div class="border_bottom"></div>
                                            <span><p>( Guard )</p></span>
                                        </div>
                                    </div>
                                    
                                    <strong>Transfer To</strong>
                                    <div class="text-left wrap-border" style="padding-left:10px;">
                                            <div style="width:45%;display:inline-block;">
                                            <br/><br/><br/>
                                            <div class="border_bottom"></div>
                                            <span><p>( Manager )</p></span>
                                        </div>
                                        <div style="width:45%;display:inline-block;">
                                                <br/><br/><br/>
                                                <div class="border_bottom"></div>
                                                <span><p>( Guard )</p></span>
                                            </div>
                                        </div>
                            </div>
                            
                    
          </div>
        </div>
          <div class="page-body">
                        @include('sales_module.vso_company_header',array('param'=>[
                    'company'=>$company,
                    'type'=>"STOCK TRANSFER",
                    'vso_no'=>$code,
                    'print'=>true,
                    'from'=>$from,
                    'to'=>$to
                  ]))       
                  <form id="pricing_form" class="form-vertical form-label-left">
                        <div class="row">
                          <div class="col-md-12 col-sm-12 col-xs-12">

                                       

                        <table class="table table-bordered table-responsive-pricing table-no-border" cellspacing="0" width="100%">
                            
                                <thead>
                                <tr>
                                    <th class="short-col">No</th>
                                    <th>Vehicle Model</th>
                                    <th>Color</th>
                                    <th>Reg No</th>
                                    <th>Chassis</th>
                                    <th>Engine No</th>
                                </tr>
                            </thead>
                                <tbody>
                      @endif
                      
                      @endforeach
                    </tbody>
                    </table>
                  </div>
                
                    

                
                  </div>
                </form>
                


          <div class="row" style="position: absolute;bottom:15px;right:10px;left:10px;width:100%;">
              
                <div class="col-sm-6 col-xs-6 text-left">
                    <strong>Driver</strong>
                        <div class="text-left wrap-border" style="padding-left:10px;">
                            <br/><br/><br/><br/>
                            <div class="border_bottom"></div>
                            <span>&nbsp;<br/>
                                <p>Name &nbsp;&nbsp;&nbsp;:</p>
                            <p>IC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
                            <p>Phone &nbsp;&nbsp;:</p>
                            <p>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p><br/></span>
                                
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6 text-left">
                            <strong>Transfer From</strong>
                            <div class="text-left wrap-border" style="padding-left:10px;">
                                <div style="width:45%;display:inline-block;">
                                <br/><br/><br/>
                                <div class="border_bottom"></div>
                                <span><p>( Manager )</p></span>
                            </div>
                            <div style="width:45%;display:inline-block;">
                                    <br/><br/><br/>
                                    <div class="border_bottom"></div>
                                    <span><p>( Guard )</p></span>
                                </div>
                            </div>
                            
                            <strong>Transfer To</strong>
                            <div class="text-left wrap-border" style="padding-left:10px;">
                                    <div style="width:45%;display:inline-block;">
                                    <br/><br/><br/>
                                    <div class="border_bottom"></div>
                                    <span><p>( Manager )</p></span>
                                </div>
                                <div style="width:45%;display:inline-block;">
                                        <br/><br/><br/>
                                        <div class="border_bottom"></div>
                                        <span><p>( Guard )</p></span>
                                    </div>
                                </div>
                    </div>
                    
            
  </div>
              </div>
            </div>
            </body>            

