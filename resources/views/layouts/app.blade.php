<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="icon" type="image/png" href="{{ asset('/') }}assets/img/logos/diskominfo.jpg">

    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/') }}assets/js/config.js"></script>
    <script src="{{ asset('/') }}vendors/overlayscrollbars/OverlayScrollbars.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('/') }}assets/css/theme.min.css" rel="stylesheet" id="style-default">
    <link href="{{ asset('/') }}assets/css/user.min.css" rel="stylesheet" id="user-style-default">

    <link href="{{ asset('/') }}vendors/waitMe/waitMe.min.css" rel="stylesheet">

    <script>
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
    </script>

    @yield('css')
</head>

<body class="waitMe_body">
    <div class="waitMe_container progress" style="height: 5px;background-color: rgba(0, 0, 0, 0)">
        <div style="background:#e63757"></div>
    </div>

    <main class="main" id="top">
        <div class="container" data-layout="container">

            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>

            @if ($menu != 'Login')
                @include('layouts.header')
            @endif

            <div class="content">

                @yield('content')

                @if ($menu != 'Login')
                    @include('layouts.footer')
                @endif
                
            </div>
        </div>
    </main>


    {{-- toast --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-primary">
                <i class="fas fa-check me-2 text-white"></i>
                <strong class="me-auto text-white toast-title">
                    Sukses
                </strong>
                <small class="text-white me-2">{{ now()->diffForHumans() }}</small>
                <button class="text-white border-0 bg-primary" data-bs-dismiss="toast" aria-label="Close">
                    <i class="far fa-window-close"></i>
                </button>
            </div>
            <div class="toast-body">
                Sukses
            </div>
        </div>
    </div>


    @yield('modal')

    <script src="{{ asset('/') }}vendors/popper/popper.min.js"></script>
    <script src="{{ asset('/') }}vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{ asset('/') }}vendors/anchorjs/anchor.min.js"></script>
    <script src="{{ asset('/') }}vendors/is/is.min.js"></script>
    <script src="{{ asset('/') }}vendors/echarts/echarts.min.js"></script>
    <script src="{{ asset('/') }}vendors/fontawesome/all.min.js"></script>
    <script src="{{ asset('/') }}vendors/lodash/lodash.min.js"></script>
    <script src="{{ asset('/') }}vendors/list.js/list.min.js"></script>
    <script src="{{ asset('/') }}assets/js/theme.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('/') }}vendors/jquery/jquery-3.6.0.js"></script>
    <script src="{{ asset('/') }}vendors/waitMe/waitMe.js"></script>

    <script>
        let defaultMapType = sessionStorage.getItem('defaultMapType');

        if(defaultMapType == null){
            sessionStorage.setItem('defaultMapType', 'gmap');
        }

        if(defaultMapType == 'leaflet'){
            $('#mapTypeControlToggle').prop('checked', false);
        } else {
            $('#mapTypeControlToggle').prop('checked', true);
        }

        function myLoader(element, message) {
            if (localStorage.getItem('theme') === 'dark') {
                bgLoading = 'rgba(0,0,0,0.5)';
                colorLoading = '#fff';
            } else {
                bgLoading = 'rgba(255,255,255,0.6)';
                colorLoading = '#000';
            }
            $(element).waitMe({
                effect: 'timer',
                text: message,
                color: colorLoading,
                bg: bgLoading,
                maxSize: '',
                waitTime: -1,
                textPos: 'vertical',
                fontSize: '',
                source: '',
                onClose: function() {}
            });
        }

        // logout
        $(function() {
            $("#mapTypeControlToggle").click(function(){
                if(defaultMapType != 'leaflet'){
                    sessionStorage.setItem('defaultMapType', 'leaflet');
                } else {
                    sessionStorage.setItem('defaultMapType', 'gmap');
                }
                location.reload();
            });

            $("#form-logout").on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin?',
                    text: "Apakah anda yakin ingin keluar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/logout') }}",
                            dataType: 'json',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            beforeSend: function() {
                                myLoader('body', 'Sedang memuat...');
                            },
                            success: function(data) {
                                document.location.href = '{{ url('/') }}';
                                // $('body').waitMe("hide");
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            });
        });
    </script>

    @yield('js')
</body>

</html>
