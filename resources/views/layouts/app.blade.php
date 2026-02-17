<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        
        <link rel="dns-prefetch" href="//fonts.bunny.net">

        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=Nunito">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">

        @vite('resources/css/app.css')

        @yield('style')

        <style>
            #toast-container .toast{
              width: 270px;
            }

            #toast-container .toast.toast-info{
                background-color: #17a2b8 !important;
            }
        </style>
    </head>

    <body>
        <div>
            <div class="header">
                <h2 class="header_head">
                    <a class="navbar-brand" style="font-size: inherit; font-weight: inherit;" href="{{ route('index') }}">
                        {{ config('app.name') }}
                    </a>
                </h2>

                <p class="header_paragraph">Easily manage your zkteco adms services</p>
            </div>

            <main class="py-4" style="min-height: calc(100vh - 185px); display: flex; align-items: center; justify-content: center; padding-top: 15px !important; padding-bottom: 15px !important;">
                @yield('content')
            </main>

            <div class="footer">
                <h2 class="header_head">Easily manage your zkteco adms services</h2>

                <p class="header_paragraph">&copy;{{ now()->year }} all rights reserved | {{config('app.name')}}</p>
            </div>
        </div>

        @vite('resources/js/app.js')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        @yield('script')

        <script>
            @if(Session::has('message'))
                var type = "{{ Session::get('alert-type') }}";

                switch(type){
                    case 'info':
                        toastr.info("{{ Session::get('message') }}");
                    break;

                    case 'warning':
                        toastr.warning("{{ Session::get('message') }}");
                    break;

                    case 'success':
                        toastr.success("{{ Session::get('message') }}");
                    break;

                    case 'error':
                        toastr.error("{{ Session::get('message') }}");
                    break;
                }
            @endif
        </script>
    </body>
</html>
