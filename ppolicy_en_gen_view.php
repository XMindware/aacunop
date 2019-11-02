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
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/bootstrap-theme.css" rel="stylesheet" />
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
            background: #fff url("assets/images/backgroundadmin.jpg") no-repeat center center fixed;    
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
  <body>
        <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Mindware.com.mx</a>
      </div>
    </nav>
    <div class="container">
      <div class="container">
        <div class="section-heading text-center">
          <h2>Terms of Service</h2>
        </div>
          <p class="text-muted">These Terms of Service (“Terms”) govern your use of the services, software and websites (the “Service”) provided by Mindware.com.mx. Our Privacy Policy explains the way we collect and use your information. The laws of Mexico, country of citizenship for Mindware.com.mx, will govern these terms.</p>
          <h3>Mindware.com.mx obligations</h3>
          <p class="text-muted">As a software provider and Web App system administrator, we are obliged to maintain the system running, stable and with all the security patches available. Daily backups and to be ready at all times in case there is an issue with the server.</p>
          <h3>Security</h3>
          <p class="text-muted">We will use industry standard technical and organizational security measures in connection with the storage, processing and presentation of your content. This is designed to guard against unauthorized or unlawful access to, use of, or processing of such information.</p>
          <h3>About the WebCUNOP software</h3>
          <p class="text-muted">The service allows the administrator to manage the agent’s roster of the Cancun Station for American Airlines; this involves the use of Employee information, and their schedule for each flight departing from this Airport. The software generates daily reports for the administration and the agents to plan ahead their schedule.</p>
          <p class="text-muted">La única excepción a esta regla será cuando exista una requisición de obligaciones legales ante las autoridades que así lo soliciten.</p>
        <div class="section-heading text-center">
          <h2>Privacy Policy</h2>
        </div>
          <p class="text-muted">This Privacy Policy describes Mindware.com.mx practices regarding the collection, use and disclosure of the information we store and process, from and about the user of WebCUNOP application. Each and every user must agree this Privacy Policy and our Terms of Service.</p>
          <h3>Our collection and use of Information</h3>
          <p class="text-muted">WebCUNOP collect personal information, such as name, employee number, email address, date of birth and position. This information is associated with your agent account and will be entered by privileged users like Station Manager of Lead Agents, which are entitled by the Station Manager to produce the information needed.</p>
          <p class="text-muted">This personal information is private, and for no reason is shared, outside the WebCUNOP or to other clients of Mindware.com.mx. System Administrator and Users with privileges to access employee’s information are also bound by these terms, and can be subjects of sanctions.</p>
          <h3>Mobile App</h3>
          <p class="text-muted">When users access the service using a mobile device, we collect specific device information contained in the mobile device’s “device identifier”. This device identifier includes information such as de type of device used, its operating system and app version. Phone number or other private information is not used or stored in anyway. We associate the device identifier with your user account to send customized notification regarding changes on the user assignments.</p>
          <h3>Mailing or marketing</h3>
          <p class="text-muted">Mindware.com.mx does not send newsletters or marketing mails with information from WebCUNOP or other enterprise software.</p>
          <h3>International Data Transfer</h3>
          <p class="text-muted">Regarding the location of the server used for the service, this is located in North America, and Godaddy, Inc. provides the service so information may go across borders from the user country or jurisdiction to another country if at the time of access the user is located outside Mexico.</p>
          <h3>Links to other sites</h3>
          <p class="text-muted">WebCUNOP does not link or connect to other servers. This includes access to American Airlines corporate network.</p>
          <br />
          <h3>Changes to this Privacy Policy</h3>
          <p class="text-muted">If Mindware.com.mx change this Privacy Policy, we will post those changes on this site and notify the users as soon as they login. Changes will be effective when they are posted on this page.</p>
          <h3>Contact Us</h3>
          <p class="text-muted">For questions about these or any Mindware.com.mx terms or policies, email us at x@mindware.com.mx</p>
          <br />
          <address>
            <strong>www.mindware.com.mx</strong><br />
            SM 317 M52 L3<br />
            Islas Britanicas 30B<br />
            Cancun, Quintana Roo<br />
            Mexico
          </address>  
          <br />
        </div>
      </div>
      
    </div> <!-- /container -->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>