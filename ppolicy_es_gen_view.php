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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VPJJCHEV8F"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-VPJJCHEV8F');
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
          <h2>Aviso de privacidad</h2>
        </div>
          <p class="text-muted">La protección de sus datos personales es uno de los ejes principales para Mindware.com.mx, y de acuerdo con la Ley federal de protección de dato personales en posesión de los particulares, el presente aviso de privacidad describe como será usada esta información.</p>
          <p class="text-muted">Mindware.com.mx utiliza las medidas de seguridad necesarias para garantizar la protección de la información de nuestros clientes, frente a acciones no autorizadas de modificación, divulgación o destrucción de datos. Estas medidas incluyen actualizaciones constantes, revisiones periódicas de procedimientos y respaldos.</p>
          <h3>Datos personales recabados</h3>
          <p class="text-muted">Para la aplicación WebCUNOP Mindware.com.mx requiere la siguiente información: nombre, numero de empleado, dirección de correo, fecha de nacimiento, días de descanso y puesto. Esta información es requerida por el sistema para generar el rol de asignaciones de empleados, y para comunicación interna.</p>
          <h3>Privacidad de su información</h3>
          <p class="text-muted">Los datos almacenados por Mindware.com.mx en cualquiera de los sistemas, son privados y no forman parte de ninguna base de datos publica de acuerdo al acuerdo firmado entre Mindware.com.mx y el representante legal de la empresa cliente. Cada usuario del sistema debe aceptar el presente acuerdo de privacidad, y se garantiza que ningún dato personal o generado por el propio sistema saldrá de los confines del propio sistema. Los administradores del sistema, ya sea por parte de Mindware.com.mx o del cliente se someten a acuerdos de confidencialidad y pueden estar sujetos a acciones disciplinarias pertinentes en caso de no cumplir dichas obligaciones.</p>
          <p class="text-muted">La única excepción a esta regla será cuando exista una requisición de obligaciones legales ante las autoridades que así lo soliciten.</p>
          <h3>Limitando el uso o divulgación de información personal</h3>
          <p class="text-muted">Usted esta en el derecho de limitar el uso de su información personal, presentando una solicitud vía correo electrónico dirigida a su supervisor en turno. Es importante mencionar que no puede limitarse el uso o divulgación de su información personal, cuando sea necesario dar cumplimiento a obligaciones legales o a solicitudes de autoridades competentes.</p>
          <h3>Dudas</h3>
          <p class="text-muted">Si tiene usted alguna duda sobre el presente aviso de privacidad favor de enviar un correo a x@mindware.com.mx dirigido a Xavier Alfeiran, así mismo, los cambios en el presente aviso de privacidad los podrá encontrar en la pagina de acceso de WebCUNOP bajo la opción "Aviso de privacidad".</p>
          <br />
          <p class="text-muted">El presente cobra efecto desde el dia 18 de Octubre de 2017</p>
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