<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        /* FULL SCREEN WRAPPER */
        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        .logo {
            width: 180px; /* BESARIN DI SINI */
            height: auto;
            margin-bottom: 10px;
        }

        /* LEFT PANEL */
        .left-panel {
            flex: 0 0 30%;
            background: linear-gradient(160deg, #FAE49E, #09973B);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        .headline {
            font-weight: 900;
            font-size: 32px; /* BESARIN INI */
            line-height: 1.4;
            margin: 20px 0;
        }

        /* RIGHT PANEL */
        .right-panel {
            flex: 0 0 70%;
            background: linear-gradient(160deg, #09973B, #FAE49E);
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .right-panel img {
            width: 75%;
            height: 80%;
            object-fit: cover;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background: #f97316;
            border: none;
            border-radius: 10px;
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .left-panel, .right-panel {
                flex: none;
                width: 100%;
                height: auto;
                padding: 30px;
            }

            .right-panel {
                order: -1;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="left-panel">
        <img src="{{ asset('LUOGOOOO.png') }}" alt="logo" class="logo">

        <div class="headline">
            Selamat Datang<br>Call Center Jagonet
        </div>


        {{-- ERROR --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <a href="#" class="forgot-link">Lupa password?</a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
        <img src="{{ asset('loginadmin.png') }}" alt="logo">
    </div>

</div>

</body>
</html>