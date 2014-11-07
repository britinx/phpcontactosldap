<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contactos LDAP</title>
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/normalize.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/foundation.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/webicons.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/fonts/fonts.css">
	<script src="<?php echo BASEURL;?>/js/vendor/modernizr.js"></script>
</head>
<body>

<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name">
    		<h1><a href="<?php echo BASEURL;?>/index.php"><img src="<?php echo BASEURL;?>/img/logo_t.png" alt="Logo"/></a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>

	<section class="top-bar-section">
    <!-- Right Section -->
	<ul class="right">
		<?php
		if(isset($_SESSION['sess_book'])) {
			for ($x=0; $x<count($libretas)-1; $x++) {
				if($_SESSION['sess_book'] == $libretas[$x])
				{ echo '<li class="has-dropdown"><a href="'.BASEURL.'/php/changebook.php?book='.$libretas[$x].'">Libreta: '.$libretas[$x].'</a>'; }
			}
		}
		echo '<ul class="dropdown">';
		
		for ($x=0; $x<count($libretas)-1; $x++)
		{ echo '<li><a href="'.BASEURL.'/php/changebook.php?book='.$libretas[$x].'">'.$libretas[$x].'</a></li>'; }
		echo '</ul></li>';
		
		if(isset($_SESSION['sess_user_nombre'])) {
			print('<li class="has-dropdown"><a href="'.BASEURL.'/php/logout.php">Cuenta: '.$_SESSION["sess_user_nombre"].'</a>');
		} else {
			print('<li class="has-dropdown"><a href="'.BASEURL.'/login.php">Iniciar Sesion</a>');
		}
		?>
			<ul class="dropdown">
				<?php
				if(isset($_SESSION['sess_user_nombre'])) {
					print('<li><a href="'.BASEURL.'/php/logout.php">Cerrar Sesion</a></li>
							<li><a href="'.BASEURL.'/opciones.php">Opciones</a></li>');
				} else {
					print('<li><a href="'.BASEURL.'/login.php">Iniciar Sesion</a></li>');
				}
				?>
			</ul>
		</li>
	</ul>

    <!-- Left Section -->
	<ul class="left">
		<li class="has-dropdown"><a href="<?php echo BASEURL;?>/buscar.php">Contactos</a>
			<ul class="dropdown">
			<li><a href="<?php echo BASEURL;?>/buscar.php">Ver Contactos</a></li>
			<?php if($EDITFLAG == TRUE) { echo '<li><a href="'.BASEURL.'/agregar.php">Agregar Contactos</a></li>';
			echo '<li><a href="'.BASEURL.'/editar.php">Editar Contactos</a></li>'; } ?>
			</ul>
		</li>
		<!--<li><a href="opciones.php">Opciones</a></li>-->
		<li><a href="<?php echo BASEURL;?>/ayuda.php">Ayuda</a></li>
	</ul>
	</section>
</nav>

<div class="row">
<div class="large-12 columns">
	<br/><br/>
	<div class="row">
	<?php if($EDITFLAG == TRUE) { echo '
	<div class="large-4 columns">
		<a href="'.BASEURL.'/buscar.php" class="button expand alert">
			<img src="'.BASEURL.'/img/icon_list.png" alt="Buscar Contactos"/>
			<br/><br/>
			Buscar Contactos
		</a>
	</div>
	<div class="large-4 columns">
		<a href="'.BASEURL.'/agregar.php" class="button expand alert">
			<img src="'.BASEURL.'/img/icon_lapiz.png" alt="Agregar Contactos"/>
			<br/><br/>
			Agregar Contactos
		</a>
	</div>
	<div class="large-4 columns">
		<a href="'.BASEURL.'/php/logout.php" class="button expand alert">
			<img src="'.BASEURL.'/img/icon_lock.png" alt="Cerrar Sesion"/>
			<br/><br/>
			Cerrar Sesion
		</a>
	</div>
	'; }
	else { echo '
	<div class="large-6 columns">
		<a href="'.BASEURL.'/buscar.php" class="button expand alert">
			<img src="'.BASEURL.'/img/icon_list.png" alt="Buscar Contactos"/>
			<br/><br/>
			Buscar Contactos
		</a>
	</div>
	<div class="large-6 columns">
		<a href="'.BASEURL.'/php/logout.php" class="button expand alert">
			<img src="'.BASEURL.'/img/icon_lock.png" alt="Cerrar Sesion"/>
			<br/><br/>
			Cerrar Sesion
		</a>
	</div>
	'; } ?>
	</div>
	<div class="row">
	<div class="large-12 columns">
		<a href="<?php echo BASEURL;?>/ayuda.php" class="button expand alert">
			<img src="<?php echo BASEURL;?>/img/icon_ayuda.png" alt="Opciones"/>
			<br/><br/>
			Ayuda
		</a>
	</div>
	</div>
</div>
</div>

<footer class="row">
	<div class="large-12 columns">
	<hr/>
	<div class="row">
	<div class="large-6 columns">
		<h7><?php echo $FOOTER;?></h7>
		<br/>
		<h7><?php echo $CREDITS;?></h7>
	</div>
	</div>
	</div>
</footer>
    

<script src="<?php echo BASEURL;?>/js/vendor/jquery.js"></script>
<script src="<?php echo BASEURL;?>/js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
</body>
</html>
