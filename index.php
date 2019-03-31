<?php 
	@session_start();
	if(isset($_SESSION['nick'])){
		header("Location: principal.php");
		die();
	} else {
	
 ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apuntomatic</title>
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/apuntomatic.css" rel="stylesheet">
	<link href="css/bootstrap-select.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
	<script type="text/javascript">
		window.cookieconsent_options = {"message":"Es obligatorio mostrar que, obviamente, utilizamos cookies.","dismiss":"Ok, makey","learnMore":"¿Qué es eso?","link":"http://apuntomatic.com/new/modals/galletas.html","theme":"dark-bottom"};
	</script>

	<script type="text/javascript" src="//s3.amazonaws.com/cc.silktide.com/cookieconsent.latest.min.js"></script>
	<!-- End Cookie Consent plugin -->
  </head>

  <body>
	<?php 
	include_once("includes/analytics.php");
	include 'header.php';
	include 'sinsesion.php';
	include 'footer.php';
	?>
  </body>
  <!-- jS
    ================================================== -->
    <!-- Se carga al final para aliviar la descarga -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/jquery.mb.YTPlayer.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script>
		(function ($) {
		$(document).ready(function () {
		$(".player").mb_YTPlayer();
		});
		}(jQuery));
	</script>	
</html><?php } ?>
