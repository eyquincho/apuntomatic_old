<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {

include_once("includes/conexionDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
?>
<?php
	if(isset($_POST['DENboton'])){
		$DENarchivo = $_POST['DENarchivo'];
		$DENacusado = $_POST['DENacusado'];
		$DENdenunciante = $_POST['DENdenunciante'];
		$DENmotivo = $_POST['DENmotivo'];
	   	if(!empty($DENmotivo)) { 
			$qry_denuncia = "INSERT INTO ap_denuncias ( IDarchivo, IDacusado, IDdenunciante, motivo, resuelto ) VALUES 
			('$DENarchivo', '$DENacusado','$DENdenunciante','$DENmotivo','0')";
			mysqli_query($_SESSION['con'], $qry_denuncia);
			echo "<div style=\"margin-top:40px;\" class=\"alert alert-success\" role=\"alert\">Denuncia enviada. Gracias!</div>";  }
			else {
				echo "<div style=\"margin-top:40px;\" class=\"alert alert-danger\" role=\"alert\">No escribiste un motivo de denuncia</div>";
			}
	}
?>
<?php
	if(isset($_FILES['archivo'])){
		$uploaddir = "documentos/";
		$file = time() . '-' . $_FILES['archivo']['name'];
		$file = preg_replace('/[^0-9a-zA-Z-_.]+/','',$file);
 		$uploadfile = $uploaddir . $file;
 		$error = $_FILES['archivo']['error'];
 		$subido = false;
 		$tipoch = str_replace("application/","",$_FILES["archivo"]["type"]);
		switch ($tipoch) {
		    case "vnd.openxmlformats-officedocument.wordprocessingml.document":
		        $tipo = "docx";
		        break;
		    case "vnd.ms-excel":
		        $tipo = "xls";
		        break;
		    case "vnd.openxmlformats-officedocument.spreadsheetml.sheet":
		        $tipo = "xlsx";
		        break;
		    case "vnd.ms-powerpoint":
		        $tipo = "ppt";
		        break;
		    case "ax-rar-compressed":
		        $tipo = "archivo";
		        break;
		}
		$n = $_SESSION["nick"];
		$query = "SELECT `ID` FROM `ap_users` WHERE `user_nick`='$n'";
		$query2 = mysqli_query($_SESSION['con'], $query);
		$fila = mysqli_fetch_array($query2);
		$user_id = $fila["ID"];
		$descripcion = urlencode($_POST['descripcion']);
		$size = $_FILES["archivo"]["size"] / 1024;
		$original = $_POST['original'];
		if(isset($_POST['anon']))
			$anonimo = true;
		else
			$anonimo = false;
		if(isset($_POST['boton']) && $error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['archivo']['tmp_name'], $uploadfile); 
		   $check = $subido && !empty($descripcion);
		}
	   	if($check) { 
			$qry_edit = "INSERT INTO ap_documentos ( usuario_id, creado_ts, file, size, descripcion, tipo, anonimo, relacion, original ) VALUES 
			('$user_id', CURDATE(), '$uploadfile','$size','$descripcion','$tipo','$anonimo','1','$original')";
			mysqli_query($_SESSION['con'], $qry_edit);
			$update_user = "UPDATE ap_users SET user_edits=(user_edits + 1) WHERE id='$user_id'";
			mysqli_query($_SESSION['con'], $update_user);
			echo "<div style=\"margin-top:40px;\" class=\"alert alert-success\" role=\"alert\">Archivo subido correctamente</div>";  }
		}
?>
<?php
	function generaCarreras()
	{
		$consulta = mysqli_query($_SESSION['con'], "SELECT `id`, `opcion` FROM `ap_carreras`");
		// Voy imprimiendo el primer select compuesto por los carreras
		while($registro=mysqli_fetch_row($consulta))
		{
			echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}
	}
?>
<?php
	function mostrar_asignatura(){
		if (isset($_POST['asignaturas'])){
		$pet_asign = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_asignaturas` WHERE `id` =". $_POST['asignaturas'] ."");
		$asign_elegida = mysqli_fetch_row($pet_asign);
		echo 'Mostrando '. $asign_elegida[0] . '.';
	}else{}
	}
	if (isset($_POST['asignaturas'])) {

			// Recoge las filas de documentos para una asignatura concreta.
			
		function mostrar_lista() {
			$docs = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `asignatura_id` =". $_POST['asignaturas'] ." AND `relacion`=0");
			while ($seleccionada = mysqli_fetch_object($docs)) {
				$div_id= $seleccionada->id;
				$edits = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `original` =". $div_id ." AND `relacion`=1");
				$num_edits = mysqli_num_rows($edits);
				$petnomuser = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
				$consnomuser = mysqli_fetch_object($petnomuser);
				if(isset($seleccionada->anonimo) && $seleccionada->anonimo == '1')
				{
				$uploader = "Anónimo";
				}  
				else {
				$uploader = $consnomuser->user_nick;
				}
				
				?>
				<tr>
				<td><center><i class="fa fa-file-<?php echo $seleccionada->tipo; ?>-o fa-2x"></i></center></td>
				<td><center><?php echo urldecode($seleccionada->nombre);?></center></td>
				<td><center><?php echo urldecode($seleccionada->descripcion);?></center></td>
				<td><center><?php echo number_format($seleccionada->size/1024,2,".",",");?> Mb</center></td>
				<td><center><a href="<?php echo $seleccionada->file?>" onclick="window.open(\'descargar.php?id=<?php echo $seleccionada->id;?>\')" target="_blank"><i class="fa fa-cloud-download fa-2x"></i></a></center></td>
				<td><center><?php echo $uploader;?></center></td>
				<td>
					<center>
						<div class="btn-group-xs" style="width:100px">
							<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#edits<?php echo $div_id;?>" class="clickable">
								<i class="fa fa-files-o fa-2x">
									<span class="rw-number-notification"><?php echo $num_edits;?></span>
								</i>
							</button>
							<!-- ******************* -->
							<!-- Modal subir edición -->
							<div class="modal fade" id="modal_edit<?php echo $div_id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalEdit" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="titulo-modal">Subir una edición</h4>
										</div>
										<div class="modal-body">
											<form name="subiredicion" id="form-edit" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
											<div class="form-group">
												<label>Cambios</label>
												<textarea class="form-control" rows="3" id="descripcion" name="descripcion" placeholder="Indica, de forma clara y sencilla, los cambios realizados"></textarea>
											</div>
											<div class="form-group">
												<label>Seleccionar archivo</label>
												<input type="file" id="archivo" name="archivo" />
											</div>
											<div class="checkbox">
												<label>
													<input type="checkbox" name="anon" id="anon" value="1" />Subir de forma anónima
												</label>
											</div>
											<input type="hidden" id="original" name="original" value="<?php echo $div_id;?>">
											<input type="hidden" id="asig_edit" name="asig_edit" value="<?php echo $_POST['asignaturas']; ?>"> 
											
										</div>
										<div class="modal-footer">
											<input class="btn btn-success" name="boton" type="submit" value="Subir" id="upedit">
											<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
										</div></form>
									</div>
								</div>
							</div>
							<!-- ******************* -->
							<button name="abrir" type="button" class="btn btn-alarm" data-target="#modal_edit<?php echo $div_id;?>" data-toggle="modal">
								<i class="fa fa-cloud-upload fa-2x"></i>
							</button>
						</div>
					</center>
				</td>
				<td><center><?php echo $seleccionada->descargas;?></center></td>
				<td><button title="Denunciar documento" data-target="#modal_denuncia<?php echo $div_id;?>" data-toggle="modal"><i class="fa fa-exclamation-circle text-danger"></i></button></td>
				<!-- ******************* -->
				<!-- Modal denunciar documento -->
				<div class="modal fade" id="modal_denuncia<?php echo $div_id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="titulo-modal">Denunciar documento </h4> <?php echo urldecode($seleccionada->nombre);?>
							</div>
							<div class="modal-body">
								<form name="enviardenuncia" id="form-denuncia" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
								<div class="form-group">
									<label>Motivos</label>
									<textarea class="form-control" rows="3" id="DENmotivo" name="DENmotivo" placeholder="Describe brevemente los motivos de denuncia"></textarea>
								</div>
								<input type="hidden" id="DENarchivo" name="DENarchivo" value="<?php echo $div_id;?>">
								<input type="hidden" id="DENdenunciante" name="DENdenunciante" value="<?php echo $_SESSION['id']; ?>">
								<input type="hidden" id="DENacusado" name="DENacusado" value="<?php echo $consnomuser->user_nick; ?>"> 
							</div>
							<div class="modal-footer">
								<input class="btn btn-danger" name="DENboton" type="submit" value="Enviar denuncia" id="DENboton">
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div></form>
						</div>
					</div>
				</div>
				<!-- ******************* -->
				<!-- Tabla extensible de ediciones de documento -->
				<tbody id="edits<?php echo $div_id;?>" class="collapse hiddenrow accordion-toggle">
						<?php
						while ($sel_ed = mysqli_fetch_object($edits)) {
							$petnomuser_ed = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $sel_ed->usuario_id . "");
							$consnomuser_ed = mysqli_fetch_object($petnomuser_ed);
							if(isset($sel_ed->anonimo) && $sel_ed->anonimo == '1')
							{
							$uploader_ed = "Anónimo";
							}  
							else {
							$uploader_ed = $consnomuser_ed->user_nick;
							}?>
								<tr class="success">
									<td><center><i class="fa fa-file-<?php echo $sel_ed->tipo; ?>-o fa-2x"></i></center></td>
									<td><center><?php echo urldecode($sel_ed->nombre);?></center></td>
									<td><center><?php echo urldecode($sel_ed->descripcion);?></center></td>
									<td><center><?php echo number_format($sel_ed->size/1024,2,".",",");?> Mb</center></td>
									<td><center><a href="<?php echo $sel_ed->file ?>" onclick="window.open(\'descargar.php?id=<?php echo $sel_ed->id;?>\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>
									<td><center><?php echo $uploader_ed;?></center></td>
									<td></td>
									<td><center><?php echo $sel_ed->descargas;?></center></td>
									<td><button title="Denunciar documento" data-target="#modal_denuncia<?php echo $div_id;?>" data-toggle="modal"><i class="fa fa-exclamation-circle text-danger"></i></button></td>
								</tr>	
									<!-- ******************* -->
									<!-- Modal denunciar ediciones -->
									<div class="modal fade" id="modal_denuncia<?php echo $sel_ed->id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title" id="titulo-modal">Denunciar documento </h4> <?php echo urldecode($sel_ed->nombre);?>
												</div>
												<div class="modal-body">
													<form name="enviardenuncia" id="form-denuncia" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
													<div class="form-group">
														<label>Motivos</label>
														<textarea class="form-control" rows="3" id="DENmotivo" name="DENmotivo" placeholder="Describe brevemente los motivos de denuncia"></textarea>
													</div>
													<input type="hidden" id="DENarchivo" name="DENarchivo" value="<?php echo $sel_ed->id;?>">
													<input type="hidden" id="DENdenunciante" name="DENdenunciante" value="<?php echo $_SESSION['id']; ?>">
													<input type="hidden" id="DENacusado" name="DENacusado" value="<?php echo $consnomuser_ed->user_nick; ?>"> 
												</div>
												<div class="modal-footer">
													<input class="btn btn-danger" name="DENboton" type="submit" value="Enviar denuncia" id="DENboton">
													<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
												</div></form>
											</div>
										</div>
									</div>
									<!-- ******************* -->								
							<?php
						}
						?>
				</tbody>
				<?php			
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8"> 
	<title>Apuntomatic</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link href="css/apuntomatic.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
	#sidebar-apuntes {
		background-color:#4a9b40!important;
	}
	</style>
  </head>
  <body>
  <?php include_once("includes/analytics.php") ?>
    <div id="wrapper" style="margin-top:20px;">
		<?php 
		include 'header.php';
		$perfil = mysqli_query($_SESSION['con'], "SELECT * FROM ap_users WHERE user_nick='".$_SESSION['nick']."'") or die(mysql_error());
		$row = mysqli_fetch_array($perfil);
		$id_sesion = $row["ID"];
		$nick_sesion = $row["user_nick"];
		$nom_sesion = $row["user_name"];
		$freg_sesion = $row["user_registered"];
		$sub_sesion = $row["user_files"];
		$desc_sesion = $row["user_downloads"];
		//gravatar
		$gravatarMd5 = md5($row["user_email"]);
		?>
           <!-- /. NAV TOP  -->
        <?php include 'sidebar.php'; ?>
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-6">
                     <h2 style="color:#5b5b5f">Apuntomatic</h2>   
                        <h5>Tu máquina expendedora de apuntes</h5>
                    </div>
					<div class="col-md-6 col-sm-12 col-xs-12">
						<br><a href="subir.php"><button class="btn btn-primary"><i class="fa fa-edit "></i>Subir un documento</button></a>
					</div>
                </div>
                 <!-- /. ROW  -->
					<div class="row" >
					<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="panel back-dash">
							<!-- Menú selección de apuntes -->
								<i class="fa fa-search fa-3x"></i><strong> BUSCAR</strong> <?php mostrar_asignatura(); ?><br>
								<p></p>
								<form id ="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" name="form" style='color:black'>
									<div class="form-group">
											<select name="carreras" id="carreras" onChange="cargaContenido(this.id)" class="form-control">
											<option value='0' >Selecciona Titulación</option>
											<?php generaCarreras(); ?>
											</select>
									</div>
									<div class="form-group">
									<select disabled="disabled" name="cursos" id="cursos" class="form-control">
										<option value="0">Selecciona Curso</option>
									</select>
									</div>
									<div class="form-group">
									<select disabled="disabled" name="asignaturas" id="asignaturas" class="form-control">
										<option value="0">Selecciona Asignatura</option>
									</select>
									</div>
								<button type="submit" class="btn btn-default btn-lg">Buscar</button>
								</form>
							</div>
					</div>
				</div>
                <div class="row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
						<?php if (isset ($_POST['asignaturas'])){ ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover"style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
											<th>Nombre</th>
                                            <th>Descripción</th>
											<th>Tamaño</th>
                                            <th>Link</th>
											<th>Uploader</th>
											<th>Ediciones</th>
											<th>Descargas</th>
											<th><i class="fa fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php mostrar_lista();	?>
                                    </tbody>
                                </table>
                            </div>
						<?php } else {?>	
						<h3 style="color: #5b5b5f">Toda gran aventura tiene un comienzo, la tuya empieza seleccionando asignatura.</h3><br><h5>No es muy épico, pero es que no quedaban dragones.</h5>
						<?php } ?>
                        </div>
                    </div>
                    
                    </div>
                </div>
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
		<?php 
		include "footer.php";
		?>
  </body>
  <!-- jS
    ================================================== -->
    <!-- Se carga al final para aliviar la descarga -->
    <script src="includes/seleccionar.js"></script>
	<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.metisMenu.js"></script>
    <script src="js/custom.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	
</html>
<?php } ?>