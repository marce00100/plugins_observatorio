<?php
/*
Plugin Name: xfr-contenidos
Plugin URI: 
Description: Generador de contenidos en frontend
Version: 1.1
Author: Marce 
Author URI: 
Licence: GPL2
Text Domain: XYZ-Cod
Doman Path: /languages/
*/

$xfrContenidos = (object)[];
$xfrContenidos->core_classname = 'XFL_Core';
$xfrContenidos->core_classfile = WP_PLUGIN_DIR . '/xfl-core/xfl-core.php';
$xfrContenidos->url            = plugin_dir_url(__FILE__);
$xfrContenidos->path           = plugin_dir_path(__FILE__);


if (is_admin())
	return false;


if (!class_exists($xfrContenidos->core_classname)) {
	require_once $xfrContenidos->core_classfile;
}

$xfrContenidos->core_url  = $xfrContenidos->core_classname::getUrl();
$xfrContenidos->core_path = $xfrContenidos->core_classname::getPath();

/*Directorios de los archivos, imagenes etc */
$xfrContenidos->pathImagenes	 = ABSPATH . 'wp-content/anexos/imagenes/';
$xfrContenidos->urlImagenes 	 = '../wp-content/anexos/imagenes/';
$xfrContenidos->pathArchivos	 = ABSPATH . 'wp-content/anexos/archivos/';
$xfrContenidos->urlArchivos 	 = '../wp-content/anexos/archivos/';

/* Directorio de los recuros com img , logos, banderas paises etc */
$xfrContenidos->pathRecursos	 = ABSPATH . 'wp-content/anexos/recursos/';
$xfrContenidos->urlRecursos 	 = '../wp-content/anexos/recursos/';

require_once 'src/app/app.contenidos.php';




