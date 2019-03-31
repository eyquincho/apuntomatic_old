<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
</button>
<div class="navbar-collapse collapse" id="navbar-main">
	<ul class="nav navbar-nav navbar-left">
		<li class="active"><a href="registro.php">Regístrate</a></li>
	</ul>
	<form id="loginForm" action="includes/comprobar.php" method="post" class="navbar-form navbar-right" role="search">
		<div class="form-group">
			<input type="text" class="form-control" name="nick" placeholder="Usuario">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Contraseña">
		</div>
		<button type="submit" name="send" id="send" class="btn btn-default">Entrar</button>
	</form>
</div>
