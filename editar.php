<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
if($EDITFLAG != TRUE)
{ header('Location: '.$BASEDIR.'/index.php'); }
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Editar - Contactos LDAP</title>
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
	<h1>Editar Contacto</h1>
	<h3>Por favor modifica los datos del contacto:</h3>
	<br/>
	<div class="row">
	<div class="large-12 columns">
		<div class="row collapse">
		<form data-abide id="editar" name="editar" method="post" action="<?php echo BASEURL;?>/php/edit.php">
		<div class="large-9 columns">
			<div class="row">
			<div class="large-4 columns">
				<input type="hidden" name="orig_givenname" id="orig_givenname" value="<?php echo $_POST["gn"];?>"/>
				<label>Nombre: <small>*Obligatorio</small>
					<input type="text" name="givenname" id="givenname" value="<?php echo $_POST["gn"];?>" required />
				</label>
				<small class="error">El nombre es obligatorio.</small>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="orig_sn" id="orig_sn" value="<?php echo $_POST["sn"];?>"/>
				<label>Apellido: <small>*Obligatorio</small>
					<input type="text" name="sn" id="sn" value="<?php echo $_POST["sn"];?>" required />
				</label>
				<small class="error">El apellido es obligatorio.</small>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="orig_ou" id="orig_ou" value="<?php echo $_POST["ou"];?>"/>
				<label>Sección: <input type="text" name="ou" id="ou" value="<?php echo $_POST["ou"];?>"/></label>
			</div>
			</div>
			<div class="row">
			<div class="large-4 columns">
				<input type="hidden" name="orig_mobile" id="orig_mobile" value="<?php echo $_POST["mobile"];?>"/>
				<label>Celular: <input type="text" name="mobile" id="mobile" value="<?php echo $_POST["mobile"];?>"/></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="orig_telephonenumber" id="orig_telephonenumber" value="<?php echo $_POST["tel"];?>"/>
				<label>Teléfono: <input type="text" name="telephonenumber" id="telephonenumber" value="<?php echo $_POST["tel"];?>"/></label>
			</div>
			<div class="large-4 columns">
				<input type="hidden" name="orig_email" id="orig_email" value="<?php echo $_POST["mail"];?>"/>
				<label>E-Mail: <input type="text" name="email" id="email" value="<?php echo $_POST["mail"];?>"/></label>
			</div>
			</div>
			<div class="row">
			<div class="large-12 columns">
				<input type="hidden" name="orig_libreta" id="orig_libreta" value="<?php echo $_POST["libreta"];?>"/>
				<label>Libreta de contactos
					<select id="new_libreta" name="new_libreta">
					<?php
					for ($x=0; $x<count($libretas)-1; $x++) {
						if($_POST["libreta"] == $libretas[$x])
						{ echo '<option selected value="'.$libretas[$x].'">'.$libretas[$x].'</option>'; }
						else
						{ echo '<option value="'.$libretas[$x].'">'.$libretas[$x].'</option>'; }
					}
					?>
					</select>
				</label>
			</div>
			</div>
			<br/>
			<input type="submit" class="button expand alert" name="button" id="button" value="Editar Contacto"/>
		</div>
		<div class="large-3 columns">
			<center><img src="<?php echo BASEURL;?>/img/icon_persona.png" alt="Imagen Perfil"/></center>
			<br/><br/>
			<input type="submit" class="button tiny alert expand" name="button" id="button" value="¡ELIMINAR CONTACTO!"/>
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
    

<script src="<?php echo BASEURL;?>/js/vendor/jquery.js"></script>
<script src="<?php echo BASEURL;?>/js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
</body>
</html>
