<?php
	session_start();
	include("includes/conexionDB.php");
	conexionDB();	

	if(!isset($_SESSION['nick'])){
		header("location:index.php");
	}
	else {
		function opcionCarreras()
		{
			$consulta=mysqli_query($_SESSION['con'], "SELECT id, opcion FROM ap_carreras");
			// Voy imprimiendo el primer select compuesto por los carreras
			echo "<select name='carreras' id='carreras' onChange='cargaContenido(this.id)'>";
			echo "<option value='0' >Selecciona Titulaci&oacute;n</option>";
			while($registro=mysqli_fetch_row($consulta))
			{
				echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
			}
			echo "</select>";
		}
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
		if(isset($_POST['anon']))
			$anonimo = true;
		else
			$anonimo = false;
		if(isset($_POST['boton']) && $error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['archivo']['tmp_name'], $uploadfile); 
		   $check = $subido && !empty($titulo) && !empty($descripcion) && !empty($asignatura);
		   
		}
	   	if($check) { 
			$qry = "INSERT INTO ap_documentos ( usuario_id, asignatura_id, creado_ts, file, size, nombre, descripcion, tipo, anonimo ) VALUES
			('$user_id','$asignatura', CURDATE(), '$uploadfile','$size','$titulo','$descripcion', '$tipo', '$anonimo')";
			mysqli_query($_SESSION['con'], $qry);
			$update_user = "UPDATE ap_users SET user_files=(user_files + 1) WHERE id='$user_id'";
			mysqli_query($_SESSION['con'], $update_user);
			echo "<script type=\"text/javascript\">alert(\"Archivo subido correctamente.\");</script>";  }
		}
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Apuntomatic - Subir apuntes</title>
	<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	<script type="text/javascript" src="includes/seleccionar.js"></script>	
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	<link rel="stylesheet" type="text/css" href="css/login.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js?ver=1.4.2"></script>
	<script src="includes/login.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<!-- Columna central -->
<section id="cuerpo">
		<h1>Subir documentos</h1>
				<center><form id ="form1" action="subir2.php" enctype="multipart/form-data" method="post" name="form">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="400">
								<table border="0">
									<tr>
										<td><label for="titulo">T&iacute;tulo:</label></td>
										<td><input tabindex="1" name="titulo" id="titulo" type="text" class="text" /></td>
									</tr>
									<tr>
										<td><label for="descripcion">Descripci&oacute;n:</label></td>
										<td><textarea tabindex="2" type="text" id="descripcion" name="descripcion"class="estilotextarea" cols="20" rows ="5" /></textarea></td>
									</tr>
									<tr>
										<td><label for="archivo">Selecciona el archivo:</label></td>
										<td><input tabindex="3" type="file" id="archivo" name="archivo" class="text" /></td>
									</tr>										
								</table>	
								<p><input type="checkbox" name="anon" id="anon" value="1"> Subir de forma an&oacute;nima<br></p>								
							</td>
							<td width="400">
									<table width="400" border="0">
										  <tr>
											<th align="center" scope="col" width="200"><?php opcionCarreras(); ?></th>
										  </tr>
										  <tr>
											<td align="center" width="200"><select disabled="disabled" name="cursos" id="cursos">
													<option value="0">Selecciona Curso</option>
												</select></td>
										  </tr>
										  <tr>
											<td align="center" width="200"><select disabled="disabled" name="asignaturas" id="asignaturas">
													<option value="0">Selecciona Asignatura</option>
												</select></td>
										  </tr>
										</table>								
							</td>
						</tr>
					</table>
					<input name="boton" type="submit" value="Subir Documento"/>
				</form></center>
</section>
<?php include 'footer.php'; ?>
</body>
</html>