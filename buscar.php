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
	<title>Buscar - Contactos LDAP</title>
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/normalize.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/foundation.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/css/webicons.css">
	<link rel="stylesheet" href="<?php echo BASEURL;?>/fonts/fonts.css">
	<script src="<?php echo BASEURL;?>/js/vendor/modernizr.js"></script>
	<script src="<?php echo BASEURL;?>/js/sorttable.js"></script>
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
	<h1>Buscar Contactos</h1>
	<div class="section-container tabs" data-section>
	<section class="section">
		<div class="content" data-slug="panel1">
		<form id="busqueda" name="busqueda" method="get" action="<?php echo BASEURL;?>/buscar.php">
			<div class="row collapse">
			<div class="large-8 columns">
				<input type="text" name="parametro" id="parametro" placeholder="Ingrese su búsqueda acá"/>
			</div>
			<div class="large-4 columns">
				<input style="height:2.3125rem" type="submit" class="button small expand alert" name="button" id="button" value="Buscar"/>
			</div>
			</div>
		</form>
		</div>
	<table width="100%" class="sortable">
		<thead>
		<tr>
			<th width='31%'>Nombre Completo</th>
			<th width='23%'>E-Mail</th>
			<th width='23%'>Teléfono</th>
			<th width='23%'>Sección</th>
		</tr>
		</thead>
		
		<tbody>
	<?php
	// Server LDAP al que se conecta, y especificaciones del protocolo.
	$conn = libreta_ldap_conectar($_SESSION['sess_user'],$_SESSION['sess_user_pass']);

	// Búsqueda:
	// Especifica tanto donde comenzar a buscar, y el criterio de busqueda.
	if (!isset($_GET['parametro']) || $_GET['parametro'] == '')
	{ $busqueda = libreta_ldap_buscar_contactos($conn,$_SESSION['sess_book'],NULL); }
	else
	{ $busqueda = libreta_ldap_buscar_contactos($conn,$_SESSION['sess_book'],$_GET['parametro']); }

	// En un for, se imprime cada entrada
	for ($i=0; $i<$busqueda["count"]; $i++) {
		echo "<tr>";
		echo "<td><a href='".BASEURL."/detalle.php?cn=".$busqueda[$i]["cn"][0]."'>".$busqueda[$i]["cn"][0]."</a></td>";
		echo "<td>".$busqueda[$i]["mail"][0]."</td>";
		if (isset($busqueda[$i]["mobile"][0])) { echo "<td>".$busqueda[$i]["mobile"][0]."</td>"; }
		elseif (isset($busqueda[$i]["telephonenumber"][0])) { echo "<td>".$busqueda[$i]["telephonenumber"][0]."</td>"; }
		else { echo "<td></td>"; }
		echo "<td>".$busqueda[$i]["ou"][0]."</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

	// Conteo de entradas encontradas.
	$conteo = count($busqueda) - 1;
	echo "Número de contactos encontrados: ".$conteo."<br/>";

	// Terminamos y salimos.
	ldap_close($conn);
	?>
	</section>
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
