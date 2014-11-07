<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
isset($_GET['cn']) or die(header('Location: '.$BASEDIR.'/buscar.php'));
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Detalle - Contactos LDAP</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/webicons.css">
	<link rel="stylesheet" href="fonts/fonts.css">
	<script src="js/vendor/modernizr.js"></script>
</head>
<body>

<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name">
    		<h1><a href="index.php"><img src="img/logo_t.png" alt="Logo"/></a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>

	<section class="top-bar-section">
    <!-- Right Section -->
	<ul class="right">
		<?php
		if(isset($_SESSION['sess_user_nombre'])) {
			print('<li class="has-dropdown"><a href="php/logout.php">Cuenta: '.$_SESSION["sess_user_nombre"].'</a>');
		} else {
			print('<li class="has-dropdown"><a href="login.php">Iniciar Sesion</a>');
		}
		?>
			<ul class="dropdown">
				<?php
				if(isset($_SESSION['sess_user_nombre'])) {
					print('<li><a href="php/logout.php">Cerrar Sesion</a></li>
							<li><a href="opciones.php">Opciones</a></li>');
				} else {
					print('<li><a href="login.php">Iniciar Sesion</a></li>');
				}
				?>
			</ul>
		</li>
	</ul>

    <!-- Left Section -->
	<ul class="left">
		<li class="has-dropdown"><a href="buscar.php">Contactos</a>
			<ul class="dropdown">
			<li><a href="buscar.php">Ver Contactos</a></li>
			<?php if($EDITFLAG == TRUE) { echo '<li><a href="agregar.php">Agregar Contactos</a></li>';
			echo '<li><a href="editar.php">Editar Contactos</a></li>'; } ?>
			</ul>
		</li>
		<!--<li><a href="opciones.php">Opciones</a></li>-->
		<li><a href="ayuda.php">Ayuda</a></li>
	</ul>
	</section>
</nav>

<div class="row">
<div class="large-12 columns">
	<br/>
	<h1>Detalle del Contacto</h1>
	<h3>Mostrando datos de <?php echo $_GET['cn']; ?></h3>
	<br/>
	<div class="row">
	<div class="large-12 columns">
		<div class="row collapse">
		<?php
		// Conectarse e iniciar sesion en el server.
		$conn = libreta_ldap_conectar($_SESSION['sess_user'],$_SESSION['sess_user_pass']);
		
		// Búsqueda:
		// Especifica tanto donde comenzar a buscar, y el criterio de busqueda.
		if (isset($_GET['cn']))
		{ $busqueda = libreta_ldap_buscar_contactos($conn,$_SESSION['sess_book'],$_GET['cn']); }
		
		// Terminamos y salimos.
		libreta_ldap_desconectar($conn);
		?>
		<form id="detalle" name="detalle" method="post" action="editar.php">
		<div class="large-9 columns">
			<div class="row">
			<div class="large-4 columns">
				<input type="hidden" name="gn" id="gn" value="<?php echo $busqueda[0]["givenname"][0];?>"/>
				<label class="inline">Nombre: <b><?php echo $busqueda[0]["givenname"][0];?></b></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="sn" id="sn" value="<?php echo $busqueda[0]["sn"][0];?>"/>
				<label class="inline">Apellido: <b><?php echo $busqueda[0]["sn"][0];?></b></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="ou" id="ou" value="<?php echo $busqueda[0]["ou"][0];?>"/>
				<label class="inline">Sección: <b><?php echo $busqueda[0]["ou"][0];?></b></label>
			</div>
			</div>
			<div class="row">
			<div class="large-4 columns">
				<input type="hidden" name="mobile" id="mobile" value="<?php echo $busqueda[0]["mobile"][0];?>"/>
				<label class="inline">Celular: <b><?php echo $busqueda[0]["mobile"][0];?></b></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="tel" id="tel" value="<?php echo $busqueda[0]["telephonenumber"][0];?>"/>
				<label class="inline">Teléfono: <b><?php echo $busqueda[0]["telephonenumber"][0];?></b></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="mail" id="mail" value="<?php echo $busqueda[0]["mail"][0];?>"/>
				<label class="inline">E-Mail: <b><?php echo $busqueda[0]["mail"][0];?></b></label>
			</div>
			<input type="hidden" name="libreta" id="libreta" value="<?php echo $_SESSION['sess_book'];?>"/>
			</div>
			<?php if($EDITFLAG == TRUE)
				{ echo '<a href="editar.php"><input type="submit" class="button expand alert" name="button" id="button" value="Editar Contacto"/></a>'; }
			?>
		</div>
		<div class="large-3 columns">
			<center><img src="img/icon_persona.png" alt="Imagen Perfil"/></center>
		</div>
		</form>
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
    

<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
</body>
</html>
