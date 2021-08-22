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

<div class="_login">
    <form method="POST" action="/app/login">
    @csrf
    @if (session('message'))
        <div class="alert alert-danger" role="alert">
            {{ session('message') }}
        </div>
    @endif
        <div class="_1input_group">
            <p class="_1label">Email</p>
            <input class="_1int" name="email" type="text"  placeholder="Enter Email" required/>
        </div>

        <div class="_1input_group">
            <p class="_1label">Password</p>
            <input class="_1int" type="password" name="password" placeholder="Enter Password" required/>
        </div>

        <div class="_1input_button _text_center">
            <button class="_btn _btn_info"  type="submit">Sign In</button>
        </div>
    </form>

</div>

<script>
            (function () {
               window.Laravel = {
                   csrfToken: '{{ csrf_token() }}'
               };
               @if(Auth::check())
                 window.authUser={!! Auth::user() !!}
                 @else
                   window.authUser=false

                 @endif
            })();
            </script>

    </body>
</html>
