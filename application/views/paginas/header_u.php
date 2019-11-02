<?
	error_reporting(E_ERROR | E_PARSE);
?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="expires" content="0">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <meta name="description" content="AA Agents Control">
    <meta name="author" content="Xavier Alfeiran mindware.com.mx">
    <link rel="icon" type="image/png" href="<? echo base_url(); ?>assets/images/favicon.png"/>
    <title><? echo $titulo; ?></title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script src="<? echo base_url(); ?>assets/js/jquery.hotkey.js"></script>

    <!-- BOOTSTRAP Calendar -->
    <link href='<? echo base_url(); ?>assets/css/fullcalendar.css' rel='stylesheet' />
    <link href='<? echo base_url(); ?>assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />

    <script src='<? echo base_url(); ?>assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
    <script src='<? echo base_url(); ?>assets/js/fullcalendar.js' type="text/javascript"></script>

    <!-- Modal dialogs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    <link href="<? echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/sticky-footer-navbar.css" rel="stylesheet">
	<link href="<? echo base_url(); ?>assets/css/loading.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/navbar-fixed-top.css" rel="stylesheet">
	
  <script src="<? echo base_url(); ?>assets/js/moment.js"></script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-80040509-1', 'auto');
    ga('send', 'pageview');

  </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      .navbar-brand>img {
         max-height: 100%;
         height: 100%;
         width: auto;
         margin: 0 auto;


         /* probably not needed anymore, but doesn't hurt */
         -o-object-fit: contain;
         object-fit: contain; 

      }

      .lhr777 {
            position: relative;
            background: #fff url("<? echo base_url(); ?>assets/images/777lhr_60.jpg") no-repeat center center fixed;    
            width: 100%;
            height: 100%;
            background-size: cover;
            overflow: hidden;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
      }

      .top-buffer { margin-top:20px; }
  </style>
  </head>

  <body>
    <script src="https://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
        <!-- polyfiller file to detect and load polyfills -->
        <script src="https://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
        <script>
          webshims.setOptions('waitReady', false);
          webshims.setOptions('forms-ext', {types: 'date'});
          webshims.polyfill('forms forms-ext');
    </script>  
  <div class="container">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<? echo base_url(); ?>admin"><img src="<? echo base_url() . 'assets/images/aa-2.png'; ?>" style="vertical-align:central" />
        </a>
      </div>
      <div id="navbar1" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Actions<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><?=anchor(base_url().'Webcunop', 'Station OP')?></li> 
              <li><?=anchor(base_url().'Agentes', 'Station Agents')?></li>
              <li><?=anchor(base_url().'Posiciones', 'Daily Positions')?></li> 
              <li><?=anchor(base_url().'Timeswitch', 'My Shift Requests')?></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Catalogs <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              
            </ul>
          </li>
          <li class="nav navbar-nav navbar-right">
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <select id="inputCompany" required class="form-control">
                    <?
                        foreach($oficinas as $oficina)
                        {
                    
                    ?>
                      <option value="<? echo $oficina['idoficina']; ?>"><? echo $oficina['sede']; ?></option>
                      <?
                        }
                    ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Switch Station</button>
            </form>
          </li>
          <li><?=anchor(base_url().'login/logout_ci', 'Logout')?></li>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
  </nav>
</div>
  