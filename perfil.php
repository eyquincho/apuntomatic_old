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
function mostrar_archivos() {
	$docs_tabla = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_documentos` WHERE `usuario_id`=". $_GET['uid'] . " AND `anonimo`='0' ORDER BY `id`");
	while ($seleccionada = mysqli_fetch_object($docs_tabla)) {
		$petnomuser = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
		$consnomuser = mysqli_fetch_object($petnomuser);
		$sql_asignatura = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_asignaturas` WHERE `ID` = ". $seleccionada->asignatura_id . "");
		$pet_asignatura = mysqli_fetch_object($sql_asignatura);
		echo '<tr>';
		echo '<td><center>' . urldecode($seleccionada->nombre) . '</center></td>';
		echo '<td><center>' . $pet_asignatura->opcion . '</center></td>';
		echo '<td><center><a href="'. $seleccionada->file .'" onclick="window.open(\'descargar.php?id='. $seleccionada->id .'\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>';
		echo '<td><center>' . $seleccionada->descargas . '</center></td>';
		echo '<td>#</td>';
		echo '<td>#</td>';
		echo '<td>#</td>';
		echo '</tr>';  
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
    <link href="css/custom.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link href="css/apuntomatic.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
	#sidebar-perfil {
		background-color:#408f9b!important;
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
		$freg_sesion = substr($row["user_registered"],0,10);
		$sub_sesion = $row["user_files"];
		$desc_sesion = $row["user_downloads"];
		$edit_sesion = $row["user_edits"];
		// Cogemos los datos que llegan por GET
		$perfil_get = mysqli_query($_SESSION['con'], "SELECT * FROM ap_users WHERE ID='".$_GET['uid']."'") or die(mysql_error());
		$row_perfil = mysqli_fetch_array($perfil_get);
		$id_perfil = $row_perfil["ID"];
		$nick_perfil = $row_perfil["user_nick"];
		$nom_perfil = $row_perfil["user_name"];
		$freg_perfil = substr($row_perfil["user_registered"],0,10);
		$sub_perfil = $row_perfil["user_files"];
		$desc_perfil = $row_perfil["user_downloads"];
		$edit_perfil = $row_perfil["user_edits"];
		//gravatar
		$gravatarMd5 = md5($row["user_email"]);
		?>
           <!-- /. NAV TOP  -->
        <?php include 'sidebar.php'; ?>
		<div id="page-wrapper" >
            <div id="page-inner">
			<div class="row">
				<div class="col-md-12">
					<h2 style="color:#5b5b5f">Hola, <?php echo $nick_perfil; ?></h2>
					<h5></h5>
				</div>
            </div>
			<div class="row">
  		<div class="col-sm-3"><!--left col-->
              
          <ul class="list-group">
            <li class="list-group-item text-muted">Resumen <i class="fa fa-dashboard fa-1x"></i></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Registro</strong></span> <?php echo $freg_perfil; ?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Documentos</strong></span> <?php echo $sub_perfil; ?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Ediciones</strong></span> <?php echo $edit_perfil; ?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Descargas</strong></span> <?php echo $desc_perfil; ?></li>
          </ul>                
          <div class="panel panel-default">
            <div class="panel-heading">Redes</div>
            <div class="panel-body">
            	<i class="fa fa-facebook fa-2x"></i> <i class="fa fa-github fa-2x"></i> <i class="fa fa-twitter fa-2x"></i> <i class="fa fa-pinterest fa-2x"></i> <i class="fa fa-google-plus fa-2x"></i>
            </div>
          </div>
          
        </div><!--/col-3-->
    	<div class="col-sm-9">
          
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#actividad" data-toggle="tab">Atividad</a></li>
            <li><a href="#settings" data-toggle="tab">Settings</a></li>
          </ul>
              
          <div class="tab-content">
            <div class="tab-pane active" id="actividad">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Asignatura</th>
                      <th>Descargar</th>
                      <th>#descargas</th>
                      <th>Label </th>
                      <th>Label </th>
                      <th>Label </th>
                    </tr>
                  </thead>
                  <tbody id="items">
                    <?php mostrar_archivos(); ?>
                  </tbody>
                </table>
                <hr>
                <div class="row">
                  <div class="col-md-4 col-md-offset-4 text-center">
                  	<ul class="pagination" id="myPager"></ul>
                  </div>
                </div>
              </div><!--/table-resp-->
              
              <hr>
              
              <h4>Recent Activity</h4>
              
              <div class="table-responsive">
                <table class="table table-hover">
                  
                  <tbody>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> Today, 1:00 - Jeff Manzi liked your post.</td>
                    </tr>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> Today, 12:23 - Mark Friendo liked and shared your post.</td>
                    </tr>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> Today, 12:20 - You posted a new blog entry title "Why social media is".</td>
                    </tr>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> Yesterday - Karen P. liked your post.</td>
                    </tr>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> 2 Days Ago - Philip W. liked your post.</td>
                    </tr>
                    <tr>
                      <td><i class="pull-right fa fa-edit"></i> 2 Days Ago - Jeff Manzi liked your post.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
             </div><!--/tab-pane-->
             <div class="tab-pane" id="messages">
               
               <h2></h2>
               
               <ul class="list-group">
                  <li class="list-group-item text-muted">Inbox</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Here is your a link to the latest summary report from the..</a> 2.13.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Hi Joe, There has been a request on your account since that was..</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Nullam sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Thllam sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Wesm sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">For therepien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Also we, havesapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Swedish chef is assaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  
                </ul> 
               
             </div><!--/tab-pane-->
             <div class="tab-pane" id="settings">
            		
               	
                  <hr>
                  <form class="form" action="##" method="post" id="registrationForm">
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>First name</h4></label>
                              <input class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any." type="text">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Last name</h4></label>
                              <input class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any." type="text">
                          </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Phone</h4></label>
                              <input class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any." type="text">
                          </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Mobile</h4></label>
                              <input class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any." type="text">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Email</h4></label>
                              <input class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email." type="email">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Location</h4></label>
                              <input class="form-control" id="location" placeholder="somewhere" title="enter a location" type="email">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>Password</h4></label>
                              <input class="form-control" name="password" id="password" placeholder="password" title="enter your password." type="password">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="password2"><h4>Verify</h4></label>
                              <input class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2." type="password">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                               	<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                      </div>
              	</form>
              </div>
               
              </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
			</div>
		</div>
	<?php include "footer.php"; ?>
	</body>
	<!-- jS
    ================================================== -->
    <!-- Se carga al final para aliviar la descarga -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.metisMenu.js"></script>
     <script src="js/morris/raphael-2.1.0.min.js"></script>
    <script src="js/morris/morris.js"></script>
    <script src="js/custom.js"></script>
</html>
<?php } ?>   