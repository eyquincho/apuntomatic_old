<?php
function valid_email($str)
{
return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
} ?> 
<?php
    if(isset($_POST['rec_con'])) { // comprobamos que se han enviado los datos del formulario
		$correo = mysqli_real_escape_string($_SESSION['con'], $_POST['correo']);
		$correo = trim($correo);
		$sql = mysqli_query($_SESSION['con'], "SELECT user_nick, user_pass, user_email FROM ap_users WHERE user_email='".$correo."'");
		if(mysqli_num_rows($sql)) {
			$row = mysqli_fetch_assoc($sql);
			$num_caracteres = "6"; // asignamos el número de caracteres que va a tener la nueva contraseña
			$nueva_clave = substr(md5(rand()),0,$num_caracteres); // generamos una nueva contraseña de forma aleatoria
			$usuario_nombre = $row['user_nick'];
			$usuario_clave = $nueva_clave; // la nueva contraseña que se enviará por correo al usuario
			$usuario_clave2 = md5($usuario_clave); // encriptamos la nueva contraseña para guardarla en la BD
			$usuario_email = $row['user_email'];
			// actualizamos los datos (contraseña) del usuario que solicitó su contraseña
			mysqli_query($_SESSION['con'], "UPDATE ap_users SET user_pass='".$usuario_clave2."' WHERE user_email='".$correo."'");
			// Enviamos por email la nueva contraseña
			$remite_nombre = "Apuntomatic"; // Tu nombre o el de tu página
			$remite_email = "contacto@apuntomatic.com"; // tu correo
			$asunto = "[Apuntomatic] Recuperación de contraseña"; // Asunto (se puede cambiar)
			$mensaje = "Has solicitado cambiar tu clave de acceso a Apuntomatic, por si dudabas, tu nombre de usuario es ".$usuario_nombre.". Y tu nueva clave: ".$usuario_clave.".";
			$cabeceras = "From: ".$remite_nombre." <".$remite_email.">rn";
			$enviar_email = mail($usuario_email,$asunto,$mensaje,$cabeceras);
			if($enviar_email) {
				header("location:../index.php?rec_er=3");
			}else {
				header("location:../index.php?rec_er=2");
			}
		}else {
			header("location:../index.php?rec_er=1");
		}
    }else {}
?>
<div class="modal fade" id="recuperar" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="text-center">
                          <h3><i class="fa fa-lock fa-4x"></i></h3>
                          <h2 class="text-center">¿Olvidaste tu contraseña?</h2>
                          <p>Reiníciala desde aquí.</p>
                            <div class="panel-body">
                              
                              <form class="form" action="index.php" method="post">
                                <fieldset>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
										<input id="emailInput" placeholder="email" name="correo" class="form-control" required="" type="email">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <input class="btn btn-lg btn-primary btn-block" name="rec_con" value="Enviame una contraseña" type="submit">
                                  </div>
                                </fieldset>
                              </form>
                              
                            </div>
                        </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
