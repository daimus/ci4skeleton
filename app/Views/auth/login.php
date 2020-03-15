<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <link href="<? echo base_url('/assets/vendors/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <form class="form-signin" action="<? echo base_url('/auth/login') ?>" method="post">
        <img class="mb-4" src="<? echo base_url('/assets/images/codeigniter.svg') ?>" alt="" width="90" height="90">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <? if (!empty(\Config\Services::session()->getFlashdata('message'))) : ?>
            <div class="alert alert-danger" role="alert">
                <? echo \Config\Services::session()->getFlashdata('message') ?>
            </div>
        <? endif; ?>
        <div>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" class="form-control <? echo $validation->hasError('email') ? 'is-invalid' : '' ?>" name="email" placeholder="Email address" autofocus>
            <div class="invalid-feedback">
                <? echo $validation->getError('email') ?>
            </div>
        </div>
        <div>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control <? echo $validation->hasError('password') ? 'is-invalid' : '' ?>" name="password" placeholder="Password">
            <div class="invalid-feedback">
                <? echo $validation->getError('password') ?>
            </div>
        </div>

        <br>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me" name="remember"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2020</p>
    </form>
</body>

</html>