<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Instrument Sans, Arial, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#f4f4f4;
        }

        .login-container{
            width:350px;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }

        .login-container h2{
            text-align:center;
            margin-bottom:25px;
            color:#333;
        }

        .input-group{
            position:relative;
            margin-bottom:20px;
        }

        .input-group i{
            position:absolute;
            top:50%;
            left:12px;
            transform:translateY(-50%);
            color:#777;
        }

        .input-group input{
            width:100%;
            padding:12px 12px 12px 40px;
            border:1px solid #ccc;
            border-radius:8px;
            outline:none;
            font-size:14px;
            transition:border-color 0.3s;
        }

        .input-group input:focus{
            border-color:#007bff;
        }

        .error-msg{
            color:#e74c3c;
            font-size:13px;
            margin-bottom:15px;
            text-align:center;
            background:#fde8e8;
            padding:8px 12px;
            border-radius:6px;
        }

        .options{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
            font-size:14px;
        }

        .options a{
            text-decoration:none;
            color:#007bff;
        }

        .options a:hover{
            text-decoration:underline;
        }

        .login-btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            background:#007bff;
            color:#fff;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        .login-btn:hover{
            background:#0056b3;
        }

        .register{
            text-align:center;
            margin-top:18px;
            font-size:14px;
        }

        .register a{
            color:#007bff;
            text-decoration:none;
            font-weight:bold;
        }

        .register a:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="error-msg">
                <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="email" placeholder="Email or Username" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="options">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn">
                <i class="fa fa-sign-in-alt"></i> Login
            </button>

            <div class="register">
                Don't have an account?
                <a href="{{ route('register') }}">Register</a>
            </div>
        </form>
    </div>

</body>
</html>
