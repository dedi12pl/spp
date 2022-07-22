<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}assets/img/logos/diskominfo.jpg">

    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/') }}assets/js/config.js"></script>
    <script src="{{ asset('/') }}vendors/overlayscrollbars/OverlayScrollbars.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('/') }}assets/css/theme.min.css" rel="stylesheet" id="style-default">
    {{-- <link href="{{ asset('/') }}assets/css/user.min.css" rel="stylesheet" id="user-style-default"> --}}

    <link href="{{ asset('/') }}vendors/waitMe/waitMe.min.css" rel="stylesheet">

    <script>
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
    </script>
</head>

<body class="waitMe_body">
    <div class="waitMe_container progress" style="height: 5px;background-color: rgba(0, 0, 0, 0)">
        <div style="background:#e63757"></div>
    </div>
    

    <div class="container">
        <div class="row flex-center min-vh-100 py-6">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4 col-xxl-4">
                <a class="d-flex flex-center mb-4" href="{{ url('/') }}">
                    <img class="me-2" src="{{ asset('/') }}img/logos/lutim.png"" alt="" height=" 50" />
                    <span class="font-sans-serif fw-bolder fs-5 d-inline-block">tower map</span>
                </a>
    
                <div class="" id="loading">
                    <div class="card-body p-4">
                        <div class="row flex-between-center mb-2">
                            <div class="col-auto">
                                <h5>Login</h5>
                            </div>
                        </div>
    
                        @if (session()->has('loginError'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Oppss!</strong> {{ session('loginError') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
    
                        <form id="loginForm" action="{{ url('/') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input class="form-control @error('email') is-invalid @enderror" type="text"
                                    placeholder="email" name="email" id="email" value="{{ old('email') }}"
                                    autofocus />
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    placeholder="Password" name="password" id="password" />
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
    
                            <div class="row flex-between-center">
                                <div class="col-auto">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input show-password" type="checkbox" id="basic-checkbox" />
                                        <label class="form-check-label mb-0" for="basic-checkbox">Tampilkan password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit"
                                    id="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <script src="{{ asset('/') }}vendors/jquery/jquery-3.6.0.js"></script>
    <script src="{{ asset('/') }}vendors/waitMe/waitMe.js"></script>

     <script>
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
        
        $(function() {
            $('#submit').click(function() {
                myLoader('body', 'Sedang memuat...');
            });

            $('.show-password').click(function() {
                let input = $('#password');
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
            });
        });
    </script>
    </body>

</html>
