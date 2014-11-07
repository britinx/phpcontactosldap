<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
if($EDITFLAG != TRUE)
{ header('Location: '.$BASEDIR.'/index.php'); }

if(!empty($_POST["givenname"]) && !empty($_POST["sn"])) {
	$datos["objectclass"][0] = "top";
	$datos["objectclass"][1] = "person";
	$datos["objectclass"][2] = "organizationalPerson";
	$datos["objectclass"][3] = "inetOrgPerson";
	$datos["objectclass"][4] = "mozillaAbPersonAlpha";
	$datos["givenname"] = $_POST["givenname"];
	$datos["sn"] = $_POST["sn"];
	if(!empty($_POST["ou"])) { $datos["ou"] = $_POST["ou"]; }
	if(!empty($_POST["telephonenumber"])) { $datos["telephonenumber"] = $_POST["telephonenumber"]; }
	if(!empty($_POST["mobile"])) { $datos["mobile"] = $_POST["mobile"]; }
	if(!empty($_POST["email"])) { $datos["mail"] = $_POST["email"]; }
	$cn = $datos["givenname"].' '.$datos["sn"];
	$flag = 1;
	}
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nuevo - Contactos LDAP</title>
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
	<br/>
	<h1>Nuevo Contacto</h1>
	<h3>Por favor ingresa todos los datos para el nuevo contacto.</h3>
	<br/>
	<div class="row">
	<div class="large-12 columns">
		<div class="row collapse">
		<?php
		// Server LDAP al que se conecta, y especificaciones del protocolo.
		$conn = libreta_ldap_conectar($_SESSION['sess_user'],$_SESSION['sess_user_pass']); 
		?>
		<form data-abide id="agregar" name="agregar" method="post" action="agregar.php">
		<div class="large-9 columns">
			<div class="row">
			<div class="large-4 columns">
				<label>Nombre: <small>*Obligatorio</small>
					<input type="text" name="givenname" id="givenname" placeholder="Nombre" required />
				</label>
				<small class="error">El nombre es obligatorio.</small>
			</div>
			<div class="large-4 columns">
				<label>Apellido: <small>*Obligatorio</small>
					<input type="text" name="sn" id="sn" placeholder="Apellido" required />
				</label>
				<small class="error">El apellido es obligatorio.</small>
			</div>
			<div class="large-4 columns">
				<label>Sección: <input type="text" name="ou" id="ou" placeholder="Sección donde trabaja"/></label>
			</div>
			</div>
			<div class="row">
			<div class="large-4 columns">
				<label>Celular: <input type="text" name="mobile" id="mobile" placeholder="Celular"/></label>
			</div>
			<div class="large-4 columns">
				<label>Teléfono: <input type="text" name="telephonenumber" id="telephonenumber" placeholder="Teléfono (con interno)"/></label>
			</div>
			<div class="large-4 columns">
				<label>E-Mail: <input type="text" name="email" id="email" placeholder="E-mail"/></label>
			</div>
			</div>
			<br/>
			<input type="submit" class="button expand alert" name="button" id="button" value="Agregar Contacto"/>
		</div>
		<div class="large-3 columns">
			<center><img src="<?php echo BASEURL;?>/img/icon_persona.png" alt="Imagen Perfil"/></center>
		</div>
		</form>
		<?php
			if($flag == 1) {
				$resultado = libreta_ldap_agregar_contactos($conn,$_SESSION["sess_book"],$cn,$datos);
				header('Location: '.$BASEDIR.'/php/confirmacion.php?accion=add');
			}
			libreta_ldap_desconectar($conn);
		?>
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
