<?php @session_start();
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {
		include_once("includes/conexionDB.php");
		conexionDB();// comprobamos que la sesión está iniciada	
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
		function tramitarsubida () {
		if(isset($_FILES['archivo'])){
		
 		$uploaddir = "documentos/";
 		// 
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
		        $tipo = "rar";
		        break;
		}
		$n = $_SESSION["nick"];
		$query = "SELECT `ID` FROM `ap_users` WHERE `user_nick`='$n'";
		$query2 = mysqli_query($_SESSION['con'], $query);
		$fila = mysqli_fetch_array($query2);
		$user_id = $fila["ID"];
		$titulo = urlencode($_POST['titulo']);
		$asignatura = $_POST['asignaturas'];
		$descripcion = urlencode($_POST['descripcion']);
		$size = $_FILES["archivo"]["size"] / 1024;
		if(isset($_POST['anon'])){
		$anonimo = true;}
		else {
		$anonimo = false;}
		if(isset($_POST['boton']) && $error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['archivo']['tmp_name'], $uploadfile); 
		   $check = $subido && !empty($titulo) && !empty($asignatura);
		   $qry = "INSERT INTO ap_documentos ( usuario_id, asignatura_id, creado_ts, file, size, nombre, descripcion, tipo, anonimo ) VALUES
			('$user_id','$asignatura', CURDATE(), '$uploadfile','$size','$titulo','$descripcion', '$tipo', '$anonimo')";
			mysqli_query($_SESSION['con'], $qry);
			$update_user = "UPDATE ap_users SET user_files=(user_files + 1) WHERE id='$user_id'";
			mysqli_query($_SESSION['con'], $update_user);
			echo "<div class=\"alert alert-success\" role=\"alert\">Archivo subido correctamente</div>";  }
		else {echo "<div class=\"alert alert-success\" role=\"alert\">Ha ocurrido un error al subir el archivo</div>";}
		}}
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
  <?php include_once("includes/analytics.php");	?>
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
		<?php include 'sidebar.php'; ?>
		<div id="page-wrapper" >
		
        <div id="page-inner">
		<?php tramitarsubida(); ?>
		<h2 style="color:#5b5b5f">Subir documentos</h2>
				<form class="form center-block" id="form1" action="subir.php" enctype="multipart/form-data" method="post" name="form">
					<div class="col-md-6 col-sm-6 col-xs-9">
						<div class="form-group">
							<label>Título</label>
							<input type="text" class="form-control input-lg" name="titulo"  placeholder="Titulo">
						</div>
						<div class="form-group">
							<label>Descripción</label>
							<textarea class="form-control" rows="3" id="descripcion" name="descripcion"></textarea>
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
					</div>
					<div class="col-md-6 col-sm-6 col-xs-9">
						<div class="form-group">
							<label>Elige asignatura</label>
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
					</div>
					<div class="col-md-12 col-sm-6 col-xs-9">
					<input type="submit" name="boton" id="subir_doc" value="Subir Documento" class="btn btn-primary btn-lg btn-block"/>
					</div>
				</form>
             <!-- /. PAGE INNER  -->
			</div>
         <!-- /. PAGE WRAPPER  -->
        </div>
		</div>
<?php include 'footer.php'; ?>
</body>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.metisMenu.js"></script>
     <script src="js/morris/raphael-2.1.0.min.js"></script>
    <script src="js/morris/morris.js"></script>
    <script src="js/custom.js"></script>
<script type="text/javascript" src="includes/seleccionar.js"></script>
</html>