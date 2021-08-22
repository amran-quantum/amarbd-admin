<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/img/edujustlogo.png">
    <title>Amar Bangladesh Admin</title>
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />


    <link rel="stylesheet" href="{{ asset('/assets/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/main.css') }}">

</head>

<body>
    <div>
        <div id="app">
            <master></master>
        </div>
        <script>
            (function() {
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                };
                @if(Auth::check())
                 window.authUser = {!! Auth::user() !!}
                @else
                 window.authUser = false
                @endif
            })();
        </script>

    </div>
    <script src="{{mix('/js/app.js')}}"></script>
</body>

</html>
