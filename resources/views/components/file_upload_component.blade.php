@php
$reload = !array_key_exists('reload',$param) || $param['reload'];
$allow_file = array_key_exists('allow_file',$param) && $param['allow_file'];
$big_thumb = array_key_exists('big_thumb',$param) && $param['big_thumb'];
@endphp

<div class="form-group {{$param['class']}}">
    <label for="{{$param['attr_name']}}" @if(strpos($param['class'],"split-2")!==false) class="col-sm-12" @endif>{{$param['label']}}
    </label>
    <input type="hidden" class="hidden-upload-target-id" id="hidden-upload-{{$param['attr_name']}}-target-id" name="hidden-upload-target-id" value="{{$param['target-id']}}">
    @if($param["verify"])
    <div class="input-group">
        <input type="file" class="form-control" id="{{$param['attr_name']}}" name="{{$param['attr_name']}}" @if(array_key_exists('disabled',$param) && $param['disabled']) disabled @endif   @if(!$allow_file) accept="image/*" @endif>
        <input type="hidden" id="hidden_{{$param['attr_name']}}" name="hidden_{{$param['attr_name']}}" value="{{$param['id']}}">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="verify('{{$param['attr_name']}}','{{$param['label']}}')">Verify</button>
            @if($big_thumb)
        <button type="button" class="btn btn-default" data-method="rotate" data-option="-90" title="Rotate Left" onclick="rotate(-90)">
            <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Left">
                <span class="fa fa-undo"></span>
            </span>
        </button>
        <button type="button" class="btn btn-default" onclick="save_image_{{$param['attr_name']}}()">
                <span class="docs-tooltip" data-toggle="tooltip" title="Save Image">
                    <span class="fa fa-save"></span>
                </span>
            </button>
        <button type="button" class="btn btn-default" @if($param['file_url']!="") onclick="window.open('{{$param['file_url']}}')" @else disabled @endif><i class="fa fa-download fa-lg"></i></button>
        @endif
        </span>
    </div>
    @else
    <div id="{{$param['attr_name']}}_container" @if(strpos($param['class'],"split-2")!==false) class="col-sm-5 col-sm-offset-1" @endif>
        <div class="input-group">
            <input type="file" class="form-control" id="{{$param['attr_name']}}" name="{{$param['attr_name']}}" @if(array_key_exists('disabled',$param) && $param['disabled']) disabled @endif  @if(!$allow_file) accept="image/*" @endif>
            <input type="hidden" id="hidden_{{$param['attr_name']}}" name="hidden_{{$param['attr_name']}}" value="{{$param['id']}}">
            @if(array_key_exists('multiple',$param) && $param['multiple'])
            <span class="input-group-btn add-group">
                <button class="btn btn-default pull-right" type="button" onclick="addFile_{{$param['attr_name']}}()"><i class="fa fa-plus fa-lg"></i></button>
            </span>
            @endif
        </div>
    </div>
    @endif
    
    @if($big_thumb)
        <img id="{{$param['attr_name']}}_image" style="width: 100%;max-height:300px;object-fit:cover" src="{{$param["id"]&&$param["id"]>0?$param['file_url']:""}}" />
    @else
    <div id="link_{{$param['attr_name']}}" class="thumb_preview openDialog clickable-row"  data-img='{{$param["file_url"]}}' data-id='{{$param["id"]}}' data-type='image'</div>
        <img id="{{$param['attr_name']}}_image" class="thumb_thumb" src="{{$param["id"]&&$param["id"]>0?$param['file_url']:""}}" ></img>
    </div> 
    @endif
    
</div>


@push("modal")
@if($param["first"])
<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Preview</h4>
        </div>
        <div class="modal-body text-center no-padding">
            <img id="my_image" class="zoom" style="max-width: 100%;" />
            <object id="my_pdf" data="" type="application/pdf" width="100%" height="400px">
                <embed id="my_ipdf" src="" type="application/pdf" />
            </object>
        </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times fa-lg"></i></button>
            <button id="btn_remove_pdf" type="button" class="btn btn-danger pull-left"><i class="fa fa-ban fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
            <button type="button" class="btn btn-default" id="btn_download"><i class="fa fa-download fa-lg"></i></button>
            <button type="button" class="btn btn-default" data-method="rotate" data-option="-90" title="Rotate Left" onclick="rotate_preview(-90)">
                <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Left">
                    <span class="fa fa-undo"></span>
                </span>
            </button>
            <button type="button" class="btn btn-default" onclick="save_image_preview_{{$param['attr_name']}}()">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Save Image">
                        <span class="fa fa-save"></span>
                    </span>
                </button>
            </div>
        </div>
        </div>
    </div>
</div>
@endif
@endpush()

@push("scripts")
<script>
@if($param["first"])

function addFile_{{$param['attr_name']}}()
{
    var $vcdiv = $('div[id^="{{$param['attr_name']}}_container"]:last').parent();

    var no = parseInt($vcdiv.prop("id").match(/\d+/g),10)+1;
    if(isNaN(no))
    {
        no=1;
        if($("#hidden_{{$param['attr_name']}}").val()==0)
        {
            return false;
        }
    }
    else
    {
        if($("#hidden_{{$param['attr_name']}}_"+(no-1)).val()==0)
        {
            return false;
        }
    }
    var $vcclone = $vcdiv.clone().prop('id','{{$param['attr_name']}}_container_'+no);
    
    var $vhidden = $vcclone.find('input[id^="hidden_{{$param['attr_name']}}"]:first');
    $vhidden.prop('id','hidden_{{$param['attr_name']}}_'+no);
    $vhidden.val("");
    
    var $file = $vcclone.find('input[id^="{{$param['attr_name']}}"]:first');
    $file.prop('id','{{$param['attr_name']}}_'+no);
    $file.val("");

    var $img = $vcclone.find('img:first');
    $img.prop('id','{{$param['attr_name']}}_image_'+no);
    $img.val("");

    $vcdiv.after($vcclone);

    var $removelink = $vcclone.find('.btn-remove');
    if($removelink.length>0)
    {
        $removelink.attr('onclick','remove_file_{{$param['attr_name']}}('+no+')');
    }
    else
    {
        var btn = '<button class="btn btn-danger btn-remove" type="button" onclick="remove_file_{{$param['attr_name']}}('+no+')"><i class="fa fa-ban fa-lg"></i></button>';
        $vcclone.find('.input-group>.input-group-btn').append(btn);
    }

    var $preview = $vcclone.find('.thumb_preview');
    if($preview.length>0)
    {
        $preview.remove();
    }

    $file.on('change',no,upload_{{$param['attr_name']}}_attachment);
}

function remove_file_{{$param['attr_name']}}(no)
{
    var hidden = $("#hidden_{{$param['attr_name']}}_"+no).val();
    if(hidden!="")
    {
        remove_pdf('ticket',hidden);
    }
    else
    {
        $("#{{$param['attr_name']}}_container_"+no).remove();
    }
}

$("#myModal").on('shown.bs.modal',function(res){
    $('#my_image').cropper({"zoomable":true,"zoomOnTouch":true,"zoomOnWheel":true,"guides":false,"modal":false,"background":false,"autoCrop":false,'dragMode':"move"});
})

function openDialog()
{
    var imgsrc = $(this).data('img');
    var id = $(this).data('id');

    if(id==""||id==0)
    {
        return;
    }

    $('#btn_remove_pdf').attr('onclick',"remove_pdf('ticket',"+id+")");
    $('#btn_download').attr('onclick',"window.open('"+imgsrc+"')");
    if($(this).data('type')=="image")
    {
       var parent =  $('#my_image').parent();
       parent.html("");
        parent.append('<img id="my_image" class="zoom" style="max-width: 100%;" />');

       $('#my_image').attr('src',imgsrc);
       $('#my_image').show();
       $('#my_pdf').hide();
    }
    else
    {
       $('#my_image').hide();
       $('#my_pdf').show();
       $('#my_pdf').attr('data',imgsrc);
       $('#my_ipdf').attr('src',imgsrc);
    }

    if($(".modal:not(#myModal)").hasClass("in"))
    {
        $(".modal:not(#myModal)").modal('hide').on('hidden.bs.modal',function(){$("#myModal").modal('show');$(".modal:not(#myModal)").modal('hide').off('hidden.bs.modal')});
    }
    else
    {
        $("#myModal").modal('show');
    }
}

$(document).on("click", ".openDialog", openDialog);

 function remove_pdf(type,id)
    {
      $.get("{{url('/remove_pdf/')}}/"+id)
            .done(function(data){
              
              window.location.reload();
            });
    }
@endif

$(function(){
    $("#{{$param['attr_name']}}").on('change',upload_{{$param['attr_name']}}_attachment);
})


function rotate_preview(degree)
{
    $('#my_image').cropper('clear');
    $('#my_image').data('cropper').rotate(degree);
}

function save_image_preview_{{$param['attr_name']}}(){
    $('#my_image').cropper('clear');
    var cropcanvas = $('#my_image').cropper('getCroppedCanvas');
        var dataURL = cropcanvas.toDataURL("image/png");

    var formData = new FormData();
        formData.append('{{$param['attr_name']}}',dataURL);
        var request = new XMLHttpRequest();

        //HY -ADD HIDDEN FIELD FOR PARAM ID - HISTORY
        var secret_id = $("#hidden-upload-{{$param['attr_name']}}-target-id").val();
        request.open("POST", "{{url('/upload_attachment/'.$param['type'].'/'.$param['attr_name'].'/')}}/"+secret_id,true);
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        request.onload = function () {
        if (request.status === 200) {
            new PNotify({nonblock: {nonblock: !0},
            text: 'Data Saved',
            type: 'success',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
            });
        }
    };

    request.send(formData);
}


@if($big_thumb)
var cropper = null;
$(function(){
    var $image = $('#{{$param['attr_name']}}_image');
    $image.cropper({"zoomable":true,"zoomOnTouch":true,"zoomOnWheel":true,"guides":false,"modal":false,"background":false,"autoCrop":false,'dragMode':"move"});
})
function rotate(degree)
{
    $('#{{$param['attr_name']}}_image').cropper('clear');
    $('#{{$param['attr_name']}}_image').data('cropper').rotate(degree);
}

function save_image_{{$param['attr_name']}}(){
    $('#{{$param['attr_name']}}_image').cropper('clear');
    var cropcanvas = $('#{{$param['attr_name']}}_image').cropper('getCroppedCanvas');
        var dataURL = cropcanvas.toDataURL("image/png");

    var formData = new FormData();
        formData.append('{{$param['attr_name']}}',dataURL);
        var request = new XMLHttpRequest();

        //HY -ADD HIDDEN FIELD FOR PARAM ID - HISTORY
        var secret_id = $("#hidden-upload-{{$param['attr_name']}}-target-id").val();
        request.open("POST", "{{url('/upload_attachment/'.$param['type'].'/'.$param['attr_name'].'/')}}/"+secret_id,true);
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        request.onload = function () {
        if (request.status === 200) {
            new PNotify({nonblock: {nonblock: !0},
            text: 'Data Saved',
            type: 'success',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
            });
        }
    };

    request.send(formData);
}
@endif

function upload_{{$param['attr_name']}}_file(file)
{
    var formData = new FormData();
    formData.append('file',file);
    var request = new XMLHttpRequest();

    //HY -ADD HIDDEN FIELD FOR PARAM ID - HISTORY
    var secret_id = $("#hidden-upload-{{$param['attr_name']}}-target-id").val();
    request.open("POST", "{{url('/upload_file/'.$param['type'].'/'.$param['attr_name'].'/')}}/"+secret_id,true);
    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    request.onload = function () {
        if (request.status === 200) {
            new PNotify({nonblock: {nonblock: !0},
            text: 'Data Saved',
            type: 'success',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
            });
            
            if(request.response!="OK")
            {
                //window.location.href = request.response;
            }
            else
            {
                //window.location.reload();
            }
        }
    };
    request.send(formData);   
}

function upload_{{$param['attr_name']}}_attachment(event)
{
    var no = "";
    if(event.data !== undefined)
    {
        no = "_"+event.data;
    }
    
    if($("#{{$param['attr_name']}}"+no)[0].files.length==0)
    {
        return;
    }

    var file = $("#{{$param['attr_name']}}"+no)[0].files[0];

    if(!file.name.match(/.(jpg|jpeg|png|gif)$/i))
    {
        if(file.size <= 1024 * 10 * 1024)
        {
            return upload_{{$param['attr_name']}}_file(file);
        }
        else
        {
            alert("File too big, please select file less than 10mb");
            return;
        }
    }

    var reader = new FileReader();
    reader.onloadend = function() {
 
    var tempImg = new Image();
    tempImg.src = reader.result;
    tempImg.onload = function() {
 
        var MAX_WIDTH = 1024;
        var MAX_HEIGHT = 1024;
        var tempW = tempImg.width;
        var tempH = tempImg.height;

        if (tempW >= tempH) {
            if (tempW > MAX_WIDTH) {
               tempH *= MAX_WIDTH / tempW;
               tempW = MAX_WIDTH;
            }
        } else {
            if (tempH > MAX_HEIGHT) {
               tempW *= MAX_HEIGHT / tempH;
               tempH = MAX_HEIGHT;
            }
        }
 
        var canvas = document.createElement('canvas');
        canvas.width = tempW;
        canvas.height = tempH;
        var ctx = canvas.getContext("2d");
        
        ctx.drawImage(this, 0, 0, tempW, tempH);

        var dataURL = canvas.toDataURL("image/png");
 
        //HY - request
        var formData = new FormData();
        formData.append('{{$param['attr_name']}}',dataURL);
        var request = new XMLHttpRequest();

        //HY -ADD HIDDEN FIELD FOR PARAM ID - HISTORY
        var secret_id = $("#hidden-upload-{{$param['attr_name']}}-target-id").val();
        request.open("POST", "{{url('/upload_attachment/'.$param['type'].'/'.$param['attr_name'].'/')}}/"+secret_id,true);
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        request.onload = function () {
        if (request.status === 200) {
            new PNotify({nonblock: {nonblock: !0},
            text: 'Data Saved',
            type: 'success',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
            });
            
            $("#hidden_{{$param['attr_name']}}").val(request.response);

            @if($big_thumb)
            window.location.reload();
            return false;
            @endif

            @if($reload)
            if(request.response!="OK")
            {
                window.location.href = request.response;
            }
            else
            {
                window.location.reload();
            }
            @endif

            //HY
            
            var parent =  $('#{{$param['attr_name']}}_image').parent();
            $('#{{$param['attr_name']}}_image').remove();
                parent.append('<img id="{{$param['attr_name']}}_image" class="zoom" style="max-width: 100%;" />');

            $('#{{$param['attr_name']}}_image').attr('src',dataURL);
            $("#link_{{$param['attr_name']}}").data('img',dataURL);
         
            var $image = $('#{{$param['attr_name']}}_image');
            $image.cropper({
            });
        }
        };
        request.send(formData);    
      }
 
   }
   reader.readAsDataURL(file); 
}


</script>
@endpush()