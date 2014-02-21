<?php
require_once '../Init.php';

$user = new User();
if($user->isLoggedIn()) {
    Redirect::to('../dashboard/');
}
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $salt = Hash::salt(32);
        $user->createUser(array(
            'username' => Input::get('username'),
            'full_name' => Input::get('fullname'),
            'password' => Hash::make(Input::get('password'), $salt),
            'salt' => $salt,
            'group' => 1,
            'email' => Input::get('email')
        ));
        Redirect::to('../login/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Kryptos | Register</title>

    <link rel="stylesheet" href="../assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css"  id="style-resource-1">
    <link rel="stylesheet" href="../assets/css/font-icons/entypo/css/entypo.css"  id="style-resource-2">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic"  id="style-resource-3">
    <link rel="stylesheet" href="../assets/css/neon.css"  id="style-resource-4">
    <link rel="stylesheet" href="../assets/css/custom.css"  id="style-resource-5">

    <script src="../assets/js/jquery-1.10.2.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body class="page-body login-page login-form-fall">
<div class="login-container">
    <div class="login-header login-caret">
        <div class="login-content">
            <a href="#" class="logo">
                <img src="../assets/images/logo.png" alt="" />
            </a>
        </div>

    </div>

    <div class="login-form">

        <div class="login-content">
            <form method="post" id="form_login" action="" class="validate">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-user"></i>
                        </div>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" autocomplete="off" data-validate="required" data-message-required="Full Name is Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-user"></i>
                        </div>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" data-validate="required" data-message-required="Username is Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-mail"></i>
                        </div>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off" data-validate="email"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" data-validate="required" data-message-required="Password is Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>
                        <input type="password" class="form-control" name="password_again" id="password_again" placeholder="Confirm Password" autocomplete="off" data-validate="required" data-message-required="Password Confirmation is Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                    <button type="submit" name="login" class="btn btn-primary btn-block btn-login"><i class="entypo-login"></i>Register</button>
                </div>
            </form>
        </div>

    </div>

</div>


<script src="../assets/js/gsap/main-gsap.js" id="script-resource-1"></script>
<script src="../assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="../assets/js/bootstrap.min.js" id="script-resource-3"></script>
<script src="../assets/js/joinable.js" id="script-resource-4"></script>
<script src="../assets/js/resizeable.js" id="script-resource-5"></script>
<script src="../assets/js/neon-api.js" id="script-resource-6"></script>
<script src="../assets/js/jquery.validate.min.js" id="script-resource-7"></script>
<script src="../assets/js/neon-login.js" id="script-resource-8"></script>
<script src="../assets/js/neon-custom.js" id="script-resource-9"></script>
<script src="../assets/js/neon-demo.js" id="script-resource-10"></script>