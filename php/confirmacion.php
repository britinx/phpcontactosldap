<?php
session_start();
require(realpath(dirname(__FILE__)."/../lib/config.php"));
require(realpath(dirname(__FILE__)."/../php/init.php"));

if($_GET["accion"] == "add")
{ $msg = 1; }
elseif($_GET["accion"] == "del")
{ $msg = 2; }
elseif($_GET["accion"] == "edit")
{ $msg = 3; }
elseif($_GET["accion"] == "error") {
	$msg = 4;
	
	if($_GET["code"] == "Invalid credentials") {
		$texto = "Razón: El usuario no existe o la contraseña es inválida."; }
	elseif($_GET["code"] == "Invalid DN syntax") {
		$texto = "Razón: Error interno de LDAP [Invalid DN syntax]."; }
	else {
	$texto = "Razón: [".$_GET["code"]."]"; }
}
elseif($_GET["accion"] == "pass")
{ $msg = 5; }
else
{ header('Location: '.$BASEDIR.'/index.php'); }
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mensaje - Contactos LDAP</title>
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
	<br/>
	<?php
		if($msg == 1) {
			echo "<h1>Contacto agregado correctamente</h1>";
			echo '<a href="'.BASEURL.'/buscar.php"><input type="submit" class="button alert expand" name="button" id="button" value="Haga click aquí para regresar"/></a>';
		}
		elseif($msg == 3) {
			echo "<h1>Contacto editado correctamente</h1>";
			echo '<a href="'.BASEURL.'/buscar.php"><input type="submit" class="button alert expand" name="button" id="button" value="Haga click aquí para regresar"/></a>';
		}
		elseif($msg == 2) {
			echo "<h1>Contacto eliminado correctamente</h1>";
			echo '<a href="'.BASEURL.'/buscar.php"><input type="submit" class="button alert expand" name="button" id="button" value="Haga click aquí para regresar"/></a>';
		}
		elseif($msg == 4) {
			echo "<h1>Ocurrió un error</h1>";
			echo "<h3>".$texto."</h3>";
			echo "<br/>";
			echo '<a href="'.$_SERVER["HTTP_REFERER"].'"><input type="submit" class="button alert expand" name="button" id="button" value="Haga click aquí para regresar"/></a>';
			//<!--<h3><a href="/contactos/buscar.php">Haga click aquí para regresar</a></h3>-->
		}
		elseif($msg == 5) {
			echo "<h1>Contraseña modificada correctamente</h1>";
			echo "<h3>Debe volver a iniciar sesión con su nueva clave.</h3>";
			echo "<br/>";
			echo '<a href="'.BASEURL.'/login.php"><input type="submit" class="button alert expand" name="button" id="button" value="Haga click aquí para ir al inicio."/></a>';
		}
	?>
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
