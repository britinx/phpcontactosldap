<?php
require(realpath($_SERVER["DOCUMENT_ROOT"]).$BASEDIR."/lib/config.php");

function libreta_ldap_sanatize($linea)
{
	// Reemplaza \\, *, ( y ) por sus caracteres HTML para evitar que editen la QUERY de LDAP.
	$linea = str_replace(array('\\', '*', '(', ')'), array('\5c', '\2a', '\28', '\29'), $linea);
	for ($i = 0; $i<strlen($linea); $i++) {
		$char = substr($linea, $i, 1);
		if (ord($char)<32) {
        		$hex = dechex(ord($char));
		        if (strlen($hex) == 1) $hex = '0' . $hex;
		        $linea = str_replace($char, '\\' . $hex, $linea);
			}
	}
	return $linea;
}

function libreta_ldap_error($conn)
{
	// Manejo de errores con una página
	$codigo = ldap_error($conn);
	header("Location: ".$BASEDIR."/php/confirmacion.php?accion=error&code=".$codigo);
}

function libreta_ldap_conectar($session,$password)
{
	$session = libreta_ldap_sanatize($session);
	$password = libreta_ldap_sanatize($password);
	
	// Formateo de variables
	global $SERVIDOR,$PUERTO,$SEP,$BASEDN,$CN_ADMIN,$OU_USUARIOS;
	if ($session == $CN_ADMIN)
	{ $session = "cn=".$session; }
	else // El formato de usuario Manager es distinto a los usuarios normales
	{ $session = "cn=".$session.$SEP.$OU_USUARIOS; }

	// Conexion al servidor
	$conn = ldap_connect($SERVIDOR, $PUERTO) or die(libreta_ldap_error($conn));
	
	// Opciones de protocolo
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

	// Inicio de sesion
	$bind = ldap_bind($conn, $session.$SEP.$BASEDN, $password) or die(libreta_ldap_error($conn));

	return $conn;
};

function libreta_ldap_desconectar($conn)
{
	ldap_close($conn);
}

function libreta_ldap_buscar_usuarios($conn)
{
	global $OU_USUARIOS,$SEP,$BASEDN;
	// Busca usuarios solo con la CN, se podría mejorar
	$busqueda = ldap_search($conn,$OU_USUARIOS.$SEP.$BASEDN, "(cn=*)") or die(libreta_ldap_error($conn));
	$info = ldap_get_entries($conn, $busqueda);
	return $info;
}
function libreta_ldap_buscar_libretas($conn)
{
	global $OU_LIBRETA,$SEP,$BASEDN;
	// Esto funciona siempre y cuando las libretas se manejen como organizaciones.
	$busqueda = ldap_search($conn,$OU_LIBRETA.$SEP.$BASEDN, "(o=*)") or die(libreta_ldap_error($conn));
	$info = ldap_get_entries($conn, $busqueda);
	return $info;
}
function libreta_ldap_buscar_contactos($conn,$libreta,$parametros)
{
	$parametros = libreta_ldap_sanatize($parametros);

	global $OU_LIBRETA,$SEP,$BASEDN;
	if($parametros == NULL) // Busca contactos solo con la CN, tambien se podría mejorar.
	{ $busqueda = ldap_search($conn,"o=".$libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN, "(cn=*)") or die(libreta_ldap_error($conn)); }
	else
	{ $busqueda = ldap_search($conn,"o=".$libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN, "(cn=*".$parametros."*)") or die(libreta_ldap_error($conn)); }
	$info = ldap_get_entries($conn, $busqueda);
	return $info;
}
function libreta_ldap_agregar_contactos($conn,$libreta,$cn,$parametros)
{
	$cn = libreta_ldap_sanatize($cn);
	$parametros = libreta_ldap_sanatize($parametros);

	global $OU_LIBRETA,$SEP,$BASEDN;
	$agregado = ldap_add($conn, "cn=".$cn.$SEP."o=".$libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN, $parametros) or die(libreta_ldap_error($conn));
	return $agregado;
}
function libreta_ldap_eliminar_contactos($conn,$libreta,$parametros)
{
	$parametros = libreta_ldap_sanatize($parametros);

	global $OU_LIBRETA,$SEP,$BASEDN;
	$borrado = ldap_delete($conn, "cn=".$parametros.$SEP."o=".$libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN) or die(libreta_ldap_error($conn));
	return $borrado;
}
function libreta_ldap_editar_contactos($conn,$libreta,$cn,$parametros)
{
	$cn = libreta_ldap_sanatize($cn);
	$parametros = libreta_ldap_sanatize($parametros);

	global $OU_LIBRETA,$SEP,$BASEDN;
	// Se especifica que CN editar, y se tira el array con datos para cambiar
	// los que no existan quedan como estan, no se borran. 
	$editado = ldap_modify($conn, "cn=".$cn.$SEP."o=".$libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN, $parametros) or die(libreta_ldap_error($conn));
	return $agregado;
}
function libreta_ldap_renombrar_contactos($conn,$orig_libreta,$new_libreta,$orig_cn,$new_cn,$parametros) //$parametro => TRUE = Mover; FALSE = Copiar
{
	$new_cn = libreta_ldap_sanatize($new_cn);
	$orig_cn = libreta_ldap_sanatize($orig_cn);
	$parametros = libreta_ldap_sanatize($parametros);

	global $OU_LIBRETA,$SEP,$BASEDN;
	// Se edita poniendo el cn original con toda la query completa, y el cn nuevo, pero sin los datos. 
	$editado = ldap_rename($conn, "cn=".$orig_cn.$SEP."o=".$orig_libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN, "cn=".$new_cn,"o=".$new_libreta.$SEP.$OU_LIBRETA.$SEP.$BASEDN,$parametros) or die(libreta_ldap_error($conn));
	return $editado;
}
?>
