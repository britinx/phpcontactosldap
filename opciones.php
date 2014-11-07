<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
if($EDITFLAG != TRUE)
{ header('Location: '.$BASEDIR.'/index.php'); }

if(!empty($_POST["claveactual"]) && !empty($_POST["clavenueva"]) && !empty($_POST["claveconfirm"])) {
	if($_SESSION['sess_user_pass'] == $_POST["claveactual"]) {
		if($_POST["clavenueva"] == $_POST["claveconfirm"]) {
			
			$datos["userpassword"] = hash(sha512,$_POST["clavenueva"]);
			$flag = 1;
		}
		else {
			$codigo = "Las contraseñas no son iguales.";
			header("Location: ".$BASEDIR."/php/confirmacion.php?accion=error&code=".$codigo);
		}
	}
	else {
		$codigo = "Contraseña actual incorrecta.";
		header("Location: ".$BASEDIR."/php/confirmacion.php?accion=error&code=".$codigo);
	}
}
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Opciones - Contactos LDAP</title>
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
	<h1>Opciones</h1>
	<h3>Acá podrá modificar los datos de su usuario.</h3>
	<br/>
	<div class="row">
	<div class="large-12 columns">
		<div class="row collapse">
		<?php
		// Server LDAP al que se conecta, y especificaciones del protocolo.
		$conn = libreta_ldap_conectar($_SESSION['sess_user'],$_SESSION['sess_user_pass']); 
		?>
		<form data-abide id="opciones" name="opciones" method="post" action="<?php echo BASEURL;?>/opciones.php">
		<div class="large-12 columns">
			<div class="row">
			<div class="large-4 columns">
				<label>Contraseña Actual: <small>*Obligatorio</small>
					<input type="password" name="claveactual" id="claveactual" placeholder="Contraseña Actual" required />
				</label>
				<small class="error">Ingrese su contraseña actual.</small>
			</div>
			<div class="large-4 columns">
				<label>Contraseña Nueva: <small>*Obligatorio</small>
					<input type="password" name="clavenueva" id="clavenueva" placeholder="Contraseña Nueva" required />
				</label>
				<small class="error">Ingrese la contraseña nueva.</small>
			</div>
			<div class="large-4 columns">
				<label>Confirmar Contraseña: <small>*Obligatorio</small>
					<input type="password" name="claveconfirm" id="claveconfirm" placeholder="Confirmar Contraseña" required />
				</label>
				<small class="error">Repita la contraseña para confirmar.</small>
			</div>
			</div>
			<br/>
			<input type="submit" class="button expand alert" name="button" id="button" value="Guardar Opciones"/>
		</div>
		</form>
		<?php
			if($flag == 1) {
				if($_SESSION['sess_user_nombre'] == $CN_ADMIN)
				{ ldap_modify($conn, "o=".$_SESSION['sess_user_nombre'].$SEP.$BASEDN, $datos); }
				else
				{ ldap_modify($conn, "cn=".$_SESSION['sess_user_nombre'].$OU_USUARIOS.$SEP.$BASEDN, $datos); }
				
				header('Location: '.$BASEDIR.'/php/confirmacion.php?accion=pass');
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
