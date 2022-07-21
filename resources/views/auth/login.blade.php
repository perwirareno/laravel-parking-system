<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>

    <link rel="icon" href="{{ asset('images/logo_l.png') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-image: url('images/bg-login.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        #title {
            text-align: center;
            padding-top: 25px;
        }

        #title h2 {
            color: #ffffff;
            text-shadow: 2px 2px 4px #000000;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }

        #title h3 {
            color: yellow;
            text-shadow: 2px 2px 4px #000000;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-md-5">
                <div class="card" style="border-radius: 10px; background-color: rgba(255, 255, 255, 0.5);box-shadow: 0 0 10px rgba(0, 0, 0, 0.2)">
                    <div class="card-body">
                        <br>
                        <center style="color:#fff;text-shadow: 2px 2px 5px #000000;">
                            <h2>LOGIN</h2>
                        </center><br>
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">E-Mail Address</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                        <input type="text" id="invalid_email" value=1 hidden>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                        <input type="text" id="invalid_password" value=1 hidden>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('template\plugins\sweetalert2\sweetalert2.all.min.js') }}"></script>
<script>
    var check_email = $("#invalid_email").val();
    var check_password = $("#invalid_password").val();

    if (check_email == 1 || check_password == 1) {
        Swal.fire(
            'Email/Password Invalid',
            'Email/Password yang anda masukkan salah!',
            'error'
        )
    }
</script>