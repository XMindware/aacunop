<?
	error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><? echo $titulo; ?></title>

    <!-- Bootstrap -->
    <link href="<? echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/favicon.png"/>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-80040509-1', 'auto');
      ga('send', 'pageview');

    </script>
    <style>
      .jumbotron {
            position: relative;
            background: #fff url("<? echo base_url(); ?>assets/images/backgroundadmin.jpg") no-repeat center center fixed;    
            width: 100%;
            height: 100%;
            background-size: cover;
            overflow: hidden;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .small {
          background-color: #fff;
          max-width: 550px;
          border: 2px solid;
          border-color: #fff;
          border-radius: 25px;
        }
    </style>
  </head>
  <body class='jumbotron'>
	<?php
    $attributes = array('class' => 'form-signin');
    $email = array('type' => 'text','id' => 'email','name' => 'email', 'class' => 'form-control','placeholder' => 'Email Address / Employee Number');
    $password = array('id' => 'password','name' => 'password','class' => 'form-control', 'placeholder' => 'Password');
    $submit = array('name' => 'submit', 'value' => 'Iniciar sesión', 'title' => 'Iniciar sesión');
    ?>
    
    <!-- Custom styles for this template -->
     <link href="<? echo base_url(); ?>assets/css/signin.css" rel="stylesheet">
    
    <div class="container">
      <center>
      <div class="small">
      <?=form_open(base_url().'login/new_user', $attributes)?>
        <h2 class="form-signin-heading">Please sign in</h2>
        
        <label for="inputEmail" class="sr-only">Email address</label>
        <?=form_input($email)?><p><?=form_error('email')?></p>
        <label for="inputPassword" class="sr-only">Password</label>
       
        <?=form_password($password)?><p><?=form_error('password')?></p>
        <?=form_hidden('token',$token)?>
        
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <?=form_close()?>
        <?php 
        if($this->session->flashdata('usuario_incorrecto'))
        {
        ?>
        <p><?=$this->session->flashdata('usuario_incorrecto')?></p>
        <?php
        }
        ?>
        <center>
          <h2>Version <? echo $versionweb; ?></h2>
        </center>
        <a href="ppolicy_es_gen_view.php" target=_blank>Aviso de privacidad </a> | <a href="ppolicy_en_gen_view.php"  target=_blank>Terms of service / Privacy Policy</a>
      </center>
    <div>
    </div> <!-- /container -->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>