<nav class="navbar-default navbar-side" style="margin-top:20px" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
					<li class="text-center">
						<img src="http://www.gravatar.com/avatar/<?php echo $gravatarMd5; ?>&s=500" class="user-image img-responsive"/>
					</li>
                    <li>
                        <a id="sidebar-principal" href="principal.php"><i class="fa fa-desktop fa-3x"></i> Portada</a>
                    </li>
					<li>
                        <a id="sidebar-apuntes" href="apuntes.php"><i class="fa fa-file-o fa-3x"></i> Apuntomatic</a>
                    </li>
					<?php 
						if($_SESSION['admin']==1){
						echo "
						<li>
                        <a id=\"sidebar-perfil\" href=\"perfil.php?uid=".$id_sesion."\"><i class=\fa fa-user fa-3x\"></i> Perfil</a>
						</li>
						";}
							else {}
					?>
                    <li>
                        <a id="sidebar-ajustes" href="ajustes.php"><i class="fa fa-dashboard fa-3x"></i> Ajustes</a>
                    </li>
                    <li class="hidden">
                        <a id="sidebar-ranking" href="ranking.php"><i class="fa fa-paper-trophy fa-3x"></i> Ranking</a>
                    </li>
					<li class="hidden">
                        <a id="sidebar-followers" href="followers.php"><i class="fa fa-paper-plane-o fa-3x"></i> Seguidores</a>
                    </li>	
                    <li class="hidden">
                        <a id="sidebar-noticias" href="noticias.php"><i class="fa fa-newspaper-o fa-3x"></i> Noticias</a>
                    </li>
                    <li class="hidden">
                        <a id="sidebar-anuncios" href="anuncios.php"><i class="fa fa-edit fa-3x"></i> Tabl&oacute;n de anuncios</a>
                    </li> 
					<li class="hidden">
                        <a id="sidebar-stats" href="stats.php"><i class="fa fa-bar-chart-o fa-3x"></i> Estad&iacute;sticas</a>
                    </li>	
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. SIDEBAR  -->