<!DOCTYPE html>
<html lang="en">

    <head>
        <link rel="manifest" href="{{asset('/manifest.json')}}">
        <link rel="apple-touch-icon" href="{{asset('/img/mini_logo.png')}}">
        <meta name="robots" content="noindex" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
        <title>{{ env('SITE_TITLE') }}</title>

        <link href="{{asset("assets/extra-libs/c3/c3.min.css")}}" rel="stylesheet">
        <link href="{{asset("assets/libs/chartist/dist/chartist.min.css")}}" rel="stylesheet">
        <link href="{{asset("assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css")}}" rel="stylesheet" />
        <link href="{{asset("assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ asset("chosen/chosen.min.css")}}" rel="stylesheet">
        <link href="{{ asset("pnotify/pnotify.css") }}" rel="stylesheet">
        <link href="{{asset("css/style.min.css")}}" rel="stylesheet">
        <link href="{{ asset("css/buttons.bootstrap.min.css")}}" rel="stylesheet">
        <link href="{{ asset("css/cropper.min.css")}}" rel="stylesheet">
        <link href="{{asset("css/all.min.css")}}" rel="stylesheet">
        <link href="{{asset("css/cs-select.css")}}" rel="stylesheet">
        <link href="{{asset("css/cs-skin-boxes.css")}}" rel="stylesheet">
        <link href="{{asset("css/custom.css")}}" rel="stylesheet">

        @if(Cookie::get('theme')=="DARK")
        <link href="{{asset("css/dark-theme.css")}}" rel="stylesheet">
        @endif
        <style>

                .no-rotate{
                    display: none;
                }
                
                .no-scroll{
                    /*overflow-y: hidden;*/
                    width: 100%;
                    height: 100%;
                    overscroll-behavior: none;
                }
            

        @media screen and (min-width: 320px) and (max-width: 767px) and (min-aspect-ratio: 13/9) {
                .no-rotate{
                    top: 0px;
                    display: block;
                    position: fixed;
                    height: 100%;
                    width: 100%;
                    background-color: black;
                    z-index: 101;
                    pointer-events: none;
                }
                .no-rotate img{
                    position: fixed;
                    width: 150px;
                    margin: auto;
                    left: 0px;
                    right: 0px;
                    top: 0px;
                    bottom: 0px;
                }

                .body{
                    pointer-events: none;
                }

                .no-scroll{
                    overflow: hidden;
                }
            }
        </style>        

        @stack('stylesheets')

    </head>

    <body class="nav-md">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
            @include('includes/topbar_header')    
            @include('includes/sidebar')

            <div class="page-wrapper">
            @yield('main_container')
            </div>

            @include('includes/footer')
        </div>

        <div class="no-rotate">
                <img src="/img/no-rotate.gif">
            </div>
        <div class="loading-modal"></div>

        @stack('modal')
        <script>
            var baseUrl = "{{url('/')}}";
        </script>
        
        <script src="{{asset("assets/libs/jquery/dist/jquery.min.js")}}"></script>
    <script src="{{asset("assets/libs/popper.js/dist/umd/popper.min.js")}}"></script>
    <script src="{{asset("assets/libs/bootstrap/dist/js/bootstrap.min.js")}}"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="{{asset("js/app-style-switcher.js")}}"></script>
    <script src="{{asset("js/feather.min.js")}}"></script>
    <script src="{{asset("assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js")}}"></script>
    <script src="{{asset("js/sidebarmenu.js")}}"></script>
    <script src="{{ asset("chosen/chosen.jquery.js") }}"></script>
    <script src="{{ asset("js/jquery.validate.min.js") }}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset("js/custom.min.js")}}"></script>
    <!--This page JavaScript -->
    <script src="{{asset("assets/extra-libs/c3/d3.min.js")}}"></script>
    <script src="{{asset("assets/extra-libs/c3/c3.min.js")}}"></script>
    <script src="{{asset("assets/libs/chartist/dist/chartist.min.js")}}"></script>
    <script src="{{asset("assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js")}}"></script>
    <script src="{{asset("assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js")}}"></script>
    <script src="{{asset("assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js")}}"></script>
    <script src="{{asset("assets/extra-libs/datatables.net/js/jquery.dataTables.min.js")}}"></script>
    <script src="{{ asset("js/jquery-ui.min.js") }}"></script>
    <script src="{{asset("js/js.cookie.js")}}"></script>
    <script src="{{asset("js/jquery.form.min.js")}}"></script>
    <script src="{{ asset("pnotify/pnotify.js") }}"></script>
    <script src="{{ asset("js/classie.js") }}"></script>
    <script src="{{ asset("js/selectFx.js") }}"></script>
    <script src="{{ asset("js/dataTables.buttons.min.js") }}"></script>
    <script src="{{ asset("js/buttons.bootstrap.min.js") }}"></script>
    <script src="{{ asset("js/buttons.flash.min.js") }}"></script>
    <script src="{{ asset("js/buttons.html5.min.js") }}"></script>
    <script src="{{ asset("js/buttons.print.min.js") }}"></script>
    <script src="{{ asset("js/cropper.min.js") }}"></script>
    <script src="{{ asset("js/jquery-cropper.min.js") }}"></script>
    <script src="{{ asset("js/all.min.js") }}"></script>
    <script src="{{ asset("js/Chart.min.js") }}"></script>
    <script src="{{ asset("js/chartjs-plugin-annotation.min.js") }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var ajax_loading_timer = null;

        $(function(){
            $(".preloader").fadeOut();
                $body.removeClass("loading");
        })

        $body = $("body");
        function showLoading()
        {
            ajax_loading_timer = setTimeout(function(){
                $body.addClass("loading");
                $(".preloader").fadeIn();
            },300);
        }

        window.addEventListener('beforeunload', function (e) {
            showLoading();
            });
            

        function hideLoading()
        {
            if(ajax_loading_timer)
            {
                window.clearTimeout(ajax_loading_timer);
                $(".preloader").fadeOut();
                $body.removeClass("loading");
            }
        }

        $(document).ajaxStart(function(){
            showLoading();
        })

        $(document).ajaxStop(function(){
            hideLoading();
        })

        function noti_go(id,link)
        {
            $.post('{{asset("/general/read_noti")}}/'+id,function(){
                window.location.href = link;
            })
        }

        function get_notification()
        {
            $(document).off('ajaxStart');

            $.get("{{url("/general/get_notifications")}}",function(data){
                if(data)
                {
                    
                    $("#message-list").empty();
                    
                    

                    $("#message-badge").removeClass();
                    if(data.count==0)
                    {
                        $("#message-badge").html("");
                        $("#message-badge").addClass('badge badge-primary notify-no rounded-circle');
                    }
                    else
                    {
                        $("#message-badge").html(data.count);
                        $("#message-badge").addClass('badge badge-primary notify-no rounded-circle');
                    }

                    var list = [];
                    if(data.unread)
                    {
                        for(var i=0;i<data.unread.length;i++)
                        {
                            list.push(data.unread[i]);
                        }
                    }
                    if(data.read)
                    {
                        for(var i=0;i<data.read.length;i++)
                        {
                            list.push(data.read[i]);
                        }
                    }

                    if(list.length==0)
                    {
                        var html = "<a href='javascript:void(0)' class='message-item d-flex align-items-center border-bottom px-3 py-2'><div class='btn btn-danger rounded-circle btn-circle'><i data-feather='airplay' class='text-white'></i></div><div class='w-75 d-inline-block v-middle pl-2'><h6 class='message-title mb-0 mt-1'>System</h6><span class='font-12 text-nowrap d-block text-muted'>Well Done, All notifications are cleared!</span><span class='font-12 text-nowrap d-block text-muted'>Now</span></div></a>";

                        $("#message-list").append(html);
                    }

                    for(var i=0;i<list.length;i++)
                    {
                        var m = list[i];
                        var link = "#";
                        var cssClass = "noti-"+m.status;
                        var html = "<a href='javascript:void(0)' onclick='noti_go("+m.id+",\""+m.action_link+"\")' class='message-item d-flex align-items-center border-bottom px-3 py-2'><div class='btn btn-danger rounded-circle btn-circle'><i data-feather='airplay' class='text-white'></i></div><div class='w-75 d-inline-block v-middle pl-2'><h6 class='message-title mb-0 mt-1'>"+m.title+"</h6><span class='font-12 text-nowrap d-block text-muted'>"+m.message+"</span><span class='font-12 text-nowrap d-block text-muted'>"+m.diff+"</span></div></a>";

                        $("#message-list").append(html);
                    }
                    feather.replace();
                }
            }).fail(function(){
                window.location.reload();
            });
            $(document).ajaxStart(function(){
                showLoading();
            })
        }
        
        function detectmob() {
            if(window.innerWidth <= 576) {
                return true;
            } else {
                return false;
            }
            }

        $(function(){
            
            var input = document.getElementsByTagName('input');
            for(var i=0;i<input.length;i++)
            {
                input[i].addEventListener('click',function(){
                    this.select();
                })
            }

            get_notification();
            var notiTimer = setInterval(get_notification, 30000);

            if(navigator.userAgent.indexOf('Mac') > 0){
                $('input').addClass('mac-os');
                $('select').addClass('mac-os');
            }
        });
        $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
            new PNotify({nonblock: {nonblock: !0},
                text: "Network Error",
                type: "error",
                styling: "bootstrap3",
                animation: "fade",
                hide: true,
                delay: 1000
                });
            });

            $(function(){
                if (navigator.serviceWorker) {
                    navigator.serviceWorker.register('{{asset("/js/sw.js")}}').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope:',  registration.scope);
                    }).catch(function(error) {
                    console.log('ServiceWorker registration failed:', error);
                    });
                }
            })
            
        jQuery.validator.methods.matches = function( value, element, params ) {
            var re = new RegExp(params);
            return this.optional( element ) || re.test( value );
        }

        function toggle_menu(menu)
        {
            var list = [];
            var selected = $(".sidebar-item.selected");
            if(selected.length>0)
            {
                selected = $(selected[0]).data('id');
                $("."+selected).show();
            }
            else
            {
                selected = "";
            }

            if(menu!="")
            {
                $("."+menu).toggle();
            }

            $(".sidebar-item").each(function(index,elem){
                var data = $(elem).data('id');
                if(data!=menu && data!=selected && !list.includes(data))
                {
                    list.push(data);
                }
            })

            for(var i=0;i<list.length;i++)
            {
                $("."+list[i]).hide();
            }
        }

        $(function(){
           toggle_menu("");
        })
    </script>
    @stack('scripts')

    </body>
</html>