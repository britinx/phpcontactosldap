<?php
session_start();
require(realpath($_SERVER["DOCUMENT_ROOT"]).$BASEDIR."/lib/libreta_ldap.php");

// URL Base, para incluir archivos sin importar donde este instalada la app
define("BASEURL", "http://".$_SERVER[HTTP_HOST].$BASEDIR);

$conn = libreta_ldap_conectar($CN_ADMIN,$CN_PASSWORD);

// AddressBooks
$info = libreta_ldap_buscar_libretas($conn);
$libretas[$info["count"]] = array();
for ($i=0; $i<$info["count"]; $i++) {
	$libretas[$i] = $info[$i]["o"][0];
}

// Usuarios
$info = libreta_ldap_buscar_usuarios($conn);
$usuarios[$info["count"]] = array();
for ($i=0; $i<$info["count"]; $i++) {
	$usuarios[$i] = $info[$i]["cn"][0];
}

// Verificación de permisos
if($_SESSION["sess_user_nombre"] == $CN_ADMIN) {
	$ADMINFLAG = TRUE;
	if ($EDITAR_ADMIN[$_SESSION["sess_book"]] == TRUE)
	{ $EDITFLAG = TRUE; }
	else
	{ $EDITFLAG = FALSE; }
}
else {
	$ADMINFLAG = FALSE;
	if ($EDITAR_USER[$_SESSION["sess_book"]] == TRUE)
	{ $EDITFLAG = TRUE; }
	else
	{ $EDITFLAG = FALSE; }
}

libreta_ldap_desconectar($conn);
?>
