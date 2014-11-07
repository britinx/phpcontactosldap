<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
if($EDITFLAG != TRUE)
{ header('Location: '.$BASEDIR.'/index.php'); }

// Conectarse e iniciar sesion en el server.
$conn = libreta_ldap_conectar($_SESSION['sess_user'],$_SESSION['sess_user_pass']); 
		
// Armamos datos nuevos y datos viejos, para verificar:
$orig_cn = $_POST["orig_givenname"].' '.$_POST["orig_sn"];
$new_cn = $_POST["givenname"].' '.$_POST["sn"];

// Si se apreto el boton de borrar, no seguir editando, solo borrar y salir.
if ($_POST["button"] == "¡ELIMINAR CONTACTO!") {
	$resultado = libreta_ldap_eliminar_contactos($conn,$_SESSION['sess_book'],$orig_cn);
	header('Location: '.$BASEDIR.'/php/confirmacion.php?accion=del');
}

// Armamos la info para editar, en un array, con la info disponible.
$datos["objectclass"][0] = "top";
$datos["objectclass"][1] = "person";
$datos["objectclass"][2] = "organizationalPerson";
$datos["objectclass"][3] = "inetOrgPerson";
$datos["objectclass"][4] = "mozillaAbPersonAlpha";

if(!empty($_POST["givenname"]))
{ $datos["givenname"] = $_POST["givenname"]; }
if(!empty($_POST["sn"]))
{ $datos["sn"] = $_POST["sn"]; }
if(!empty($_POST["ou"]))
{ $datos["ou"] = $_POST["ou"]; }
else {
	$buf["ou"] = array();
	ldap_mod_del($conn, "cn=".$orig_cn.",o=".$_SESSION['sess_book'].$SEP.$OU_LIBRETA.$SEP.$BASEDN, $buf);
}
if(!empty($_POST["telephonenumber"]))
{ $datos["telephonenumber"] = $_POST["telephonenumber"]; }
else {
	$buf["telephonenumber"] = array();
	ldap_mod_del($conn, "cn=".$orig_cn.",o=".$_SESSION['sess_book'].$SEP.$OU_LIBRETA.$SEP.$BASEDN, $buf);
}
if(!empty($_POST["mobile"]))
{ $datos["mobile"] = $_POST["mobile"]; }
else {
	$buf["mobile"] = array();
	ldap_mod_del($conn, "cn=".$orig_cn.",o=".$_SESSION['sess_book'].$SEP.$OU_LIBRETA.$SEP.$BASEDN, $buf);
}
if(!empty($_POST["email"]))
{ $datos["mail"] = $_POST["email"]; }
else {
	$buf["mail"] = array();
	ldap_mod_del($conn, "cn=".$orig_cn.",o=".$_SESSION['sess_book'].$SEP.$OU_LIBRETA.$SEP.$BASEDN, $buf);
}

// Si no se cambio el nombre, simplemente editar los atributos con ldap_modify();
if ($orig_cn != $new_cn || $_POST["new_libreta"] != $_SESSION['sess_book']) {
	echo $_POST["orig_libreta"]."<br/>";
	echo $_POST["new_libreta"]."<br/>";
	$resultado = libreta_ldap_editar_contactos($conn,$_SESSION['sess_book'],$orig_cn,$datos);
	$resultado = libreta_ldap_renombrar_contactos($conn,$_POST["orig_libreta"],$_POST["new_libreta"],$orig_cn,$new_cn,TRUE);
}
else { // Si se cambio el nombre, tambien editar el cn=
	$resultado = libreta_ldap_editar_contactos($conn,$_SESSION['sess_book'],$orig_cn,$datos);
}
libreta_ldap_desconectar($conn);
header('Location: '.$BASEDIR.'/php/confirmacion.php?accion=edit');
?>
