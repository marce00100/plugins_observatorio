<?php
/*
Plugin Name: xfr-data-dash
Plugin URI: 
Description: Visualizador de datos, tablas dinamicas , pivot y graficas 
Version: 1.5
Author: Marce 
Author URI: 
Licence: GPL2
Text Domain: frctl
Doman Path: /languages/
*/

if(is_admin())
    return false;

$datadash = (object)[];
$datadash->core_classname = 'XFL_Core';
$datadash->core_classfile = WP_PLUGIN_DIR . '/xfl-core/xfl-core.php';
$datadash->url            = plugin_dir_url(__FILE__);
$datadash->path           = plugin_dir_path(__FILE__);

if (!class_exists($datadash->core_classname)) {
    require_once $datadash->core_classfile;
}

$datadash->core_url  = $datadash->core_classname::getUrl();
$datadash->core_path = $datadash->core_classname::getPath();

require_once 'app/app.datadash.php';

