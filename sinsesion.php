<section class="content-section <!--video-section hidden-xs hidden-sm-->">
	<!--<div class="col-lg-12"><center>
			<script type="text/javascript">
				imagen = new Array();
				imagen[0] = '<img src="img/publi/partido101.jpg" height="700px">';
				imagen[1] = '<img src="img/publi/brieffindor.jpg" height="700px">';
				aleatorio = Math.random() * (imagen.length);
				aleatorio = Math.floor(aleatorio);
				document.write(imagen[aleatorio]);
			</script>
	</center></div>
	
		<div class="pattern-overlay">
			<a id="bgndVideo" class="player" data-property="{videoURL:'https://www.youtube.com/watch?v=UfUu0F2EukI',containment:'.video-section', quality:'large', autoPlay:true, mute:true, opacity:1}">bg</a>
			<div class="container">
				<div class="row">
					<div class="col-lg-12"> 
						<h3>Descubre el último videoclip de <strong>Los Tónikash</strong></h3>
						<h2>
							<a href="https://www.youtube.com/watch?v=opbDwyFbsq4" target="_blank"><i class="fa fa-youtube-play"></i></a>
							<a href="https://www.facebook.com/lostonikash" target="_blank"><i class="fa fa-facebook"></i></a>
						</h2>
					</div>
				</div>
			</div>
		</div> -->
	<!-- Espacio elecciones FCSC -->

	<style>
		.muro {
			background-color: #805940;
			background-image: url("img/brick-wall.png");
			min-height: 100%;
			height: 100%;
		
		}
		.poster {
			width: 100%;
		}
		.fila {
		margin-top: 10%;
		margin-bottom: 10%;
		}
		
	</style>
	<div class="row muro">
	
	  <div class="container fila">
	  <?php if (isset($_GET['log_er'])) {
			echo "<div class=\"alert alert-warning\" role=\"alert\" style=\"margin-top:20px;\">Datos de inicio de sesi&oacute;n err&oacute;neos. <a href='javascript:history.back();'>Reintentar</a> o <a data-toggle=\"modal\" data-target=\"#recuperar\">Recupera tu contraseña</a></div>";
				}
			else {}
			if (isset($_GET['rec_er'])) {
				$i=$_GET['rec_er'];
				switch ($i) {
					case 1:
						echo "<div class=\"alert alert-warning\" role=\"alert\">El email ".$_POST['correo']." no pertenece a ningún usuario. <a data-toggle=\"modal\" data-target=\"#recuperar\">Reintentar</a></div>";
						break;
					case 2:
						echo "<div class=\"alert alert-warning\" role=\"alert\">No se ha podido enviar el email. <a data-toggle=\"modal\" data-target=\"#recuperar\">Reintentar</a></div>";
						break;
					case 3:
						echo "<div class=\"alert alert-success\" role=\"alert\">La nueva contraseña ha sido enviada al email asociado al usuario ".$usuario_nombre.". Si no aparece en tu bandeja de entrada, revisa la bandeja de Spam.</div>";
						break;
					case 4:
						echo "<div class=\"alert alert-success\" role=\"alert\">Has borrado correctamente tu cuenta. Au revoir!</div>";
						break;
				}
			}
			else {}
		?>
		  <div class="col-md-3">
			  <a href="#" target="_blank"><img src="img/publi/poster.png" class="poster"></a>
		  </div>
		  <div class="col-md-3">
			  <a href="#" target="_blank"><img src="img/publi/poster.png" class="poster"></a>
		  </div>
		  <div class="col-md-3">
			  <a href="#" target="_blank"><img src="img/publi/poster.png" class="poster"></a>
		  </div>
		  <div class="col-md-3">
			  <a href="#" target="_blank"><img src="img/publi/poster.png" class="poster"></a>
		  </div>
	  </div>
	</div>   
	<!-- Fin espacio elecciones FCSC -->
	
</section>
<!-- <section class="hidden-md hidden-lg">
	<div class="container">
      <div class="row">
        <div id="hola-movil" class="col-lg-12">
			<p>En Apuntomatic tratamos de que la experiencia móvil sea la mejor posible. A menos que tengas un Nokia ladrillo. Contra eso ya no podemos hacer nada.</p>
	   </div>
      </div>
    </div>
</section>-->
