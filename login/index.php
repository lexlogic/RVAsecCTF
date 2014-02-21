<?php
require_once '../Init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $user = new User();
        $remember = (Input::get('remember') === 'on') ? true : false;
        $login = $user->login($_POST['username'], $_POST['password'], $remember);

        if($login) {
            Redirect::to('../dashboard/');
        } else {
            Redirect::to('../login/');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="RVAsec CTF" />

    <title>RVAsec CTF | Login</title>

    <link rel="stylesheet" href="../assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="../assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="../assets/css/neon.css">
    <link rel="stylesheet" href="../assets/css/custom.css">

    <script src="../assets/js/jquery-1.10.2.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body class="page-body login-page login-form-fall" data-url="">

<div class="login-container">

    <div class="login-header login-caret">

        <div class="login-content">

            <a href="#" class="logo">
                <img src="../assets/images/logo-horizontal.png" />
            </a>
        </div>

    </div>

    <div class="login-form">

        <div class="login-content">

            <form method="post" role="form" id="form_login">

                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-user"></i>
                        </div>

                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" />
                    </div>

                </div>

                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>

                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox checkbox-replace">
                        <label>
                            <input type="checkbox" id="remember" name="remember">
                            Remember Me</label>
                    </div><br>
                    <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                    <button type="submit" class="btn btn-primary btn-block btn-login"><i class="entypo-login"></i>Login</button>
                </div>
                <a href="../register/" class="link">Need an Account? Click Here!</a>
            </form>
        </div>
    </div>
</div>
<!-- Bottom Scripts -->
<script src="../assets/js/gsap/main-gsap.js"></script>
<script src="../assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/joinable.js"></script>
<script src="../assets/js/resizeable.js"></script>
<script src="../assets/js/neon-api.js"></script>
<script src="../assets/js/jquery.validate.min.js"></script>
<script src="../assets/js/neon-login.js"></script>
<script src="../assets/js/neon-custom.js"></script>
<script src="../assets/js/neon-demo.js"></script>

</body>
</html>