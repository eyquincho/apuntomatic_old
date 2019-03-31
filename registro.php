<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apuntomatic | Registro</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link href="css/apuntomatic.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
<body>

	<div class="container">
	 <?php
    if(isset($_POST['enviar'])) { // comprobamos que se han enviado los datos desde el formulario
        // creamos una función que nos permita validar el email
        function valida_email($correo) {
            if (preg_match("/^[_.0-9a-z-]+@[0-9a-z._-]+.[a-z]{2,4}$/", $correo)) return true;
            else return false;
        }
		echo "<br>";
		echo "<br>";
        // Procedemos a comprobar que los campos del formulario no estén vacíos
        $sin_espacios = count_chars($_POST['usuario_nombre'], 1);
        if(!empty($sin_espacios[32])) { // comprobamos que el campo usuario_nombre no tenga espacios en blanco
            echo "<div class=\"alert alert-warning\" role=\"alert\">El nick no puede contener espacios en blanco. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(empty($_POST['usuario_nombre'])) { // comprobamos que el campo usuario_nombre no esté vacío
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has ingresado un nombre de usuario. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(empty($_POST['usuario_clave'])) { // comprobamos que el campo usuario_clave no esté vacío
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has puesto ninguna contraseña. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif($_POST['usuario_clave'] != $_POST['usuario_clave_conf']) { // comprobamos que las contraseñas ingresadas coincidan
            echo "<div class=\"alert alert-warning\" role=\"alert\">Las contraseñas ingresadas no coinciden. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(!valida_email($_POST['usuario_email'])) { // validamos que el email ingresado sea correcto
            echo "<div class=\"alert alert-warning\" role=\"alert\">Las contraseñas no coinciden. <a href='javascript:history.back();'>Reintentar</a></div>";
        }else {
            // "limpiamos" los campos del formulario de posibles códigos maliciosos
            $usuario_nombre = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_nombre']);
            $usuario_clave = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_clave']);
            $usuario_email = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_email']);
            // comprobamos que el usuario ingresado no haya sido registrado antes
            $sql_nick = mysqli_query($_SESSION['con'], "SELECT user_nick FROM ap_users WHERE user_nick='".$usuario_nombre."'");
			$sql_mail = mysqli_query($_SESSION['con'], "SELECT user_email FROM ap_users WHERE user_email='".$usuario_email."'");
            if(mysqli_num_rows($sql_nick) > 0) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">El nombre usuario elegido ya ha sido registrado anteriormente. <a href='javascript:history.back();'>Reintentar</a></div>";
            }else {
				if(mysqli_num_rows($sql_mail) > 0) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">El email que has utilizado ya está siendo utilizado. <br />¿Es tu cuenta? ¡Recupera tu contraseña! <a href='javascript:history.back();'>Reintentar</a></div>";
				}else {
					$usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
					// ingresamos los datos a la BD
					$reg = mysqli_query($_SESSION['con'],"INSERT INTO ap_users (user_nick, user_pass, user_email, user_registered) VALUES ('".$usuario_nombre."', '".$usuario_clave."', '".$usuario_email."', NOW())");
					if($reg) {
						echo "<div class=\"alert alert-success\" role=\"alert\">Registro realizado con éxito, ahora ya puedes iniciar sesión y empezar a compartir.</div>";
					}else {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error, por favor, ponte en contacto con el Administrador.</div>";
					}
				}
			}
        }
    }else {
?>
	<div class="row col-centered">
      <div style="margin-top:60px" class="col-md-6 col-sm-9 col-xs-9">
          <form class="form center-block" id="loginForm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<div class="form-group">
              <input type="text" class="form-control input-lg" name="usuario_nombre"  placeholder="Nick">
            </div>
			<div class="form-group">
              <input type="password" class="form-control input-lg" name="usuario_clave" placeholder="Contraseña">
            </div>
            <div class="form-group">
              <input type="password" class="form-control input-lg" name="usuario_clave_conf" placeholder="Repetir contraseña">
            </div>
			<div class="form-group">
              <input type="text" class="form-control input-lg" name="usuario_email" placeholder="E-mail">
            </div>
            <div class="form-group">
              <button type="submit" name="enviar" class="btn btn-primary btn-lg btn-block">Regístrate</button>
              <span>Registrándote aceptas nuestras <a data-toggle="modal" data-target="#condiciones">condiciones de uso</a></span>
            </div>
          </form>
      </div>
		<div style="margin-top:60px" class="col-md-6 col-sm-9 col-xs-9">
			<h2 style="color:#5b5b5f">Regístrate ahora para empezar a compartir</h2>
			Tu experiencia con Apuntomatic está a pocos clicks de distancia. Regístrate ahora para poder empezar a compartir apuntes con cientos de alumnos de tu universidad.
		</div>
	 </div>
	</div>
	<?php 
		include "footer.php";
	?>
<?php
    }
?> 
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>