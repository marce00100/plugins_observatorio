<?php
/*
Plugin Name: xfr-xp-chatbot
Plugin URI: 
Description: Chat bot, de Sistema Experto v.2 para WP Libs Laravel
Version: 2
Author: Marce 
Author URI: 
Licence: GPL2
Text Domain: XYZ-Cod
Doman Path: /languages/
*/

$chatbot = (object)[];
$chatbot->core_classname = 'XFL_Core';
$chatbot->core_classfile = WP_PLUGIN_DIR . '/xfl-core/xfl-core.php';
$chatbot->url            = plugin_dir_url(__FILE__);
$chatbot->path           = plugin_dir_path(__FILE__);


if (is_admin())
	return false;


if (!class_exists($chatbot->core_classname)) {
	require_once $chatbot->core_classfile;
}

$chatbot->core_url  = $chatbot->core_classname::getUrl();
$chatbot->core_path = $chatbot->core_classname::getPath();

/* Instancia de direcciones para geolocaliza */
$geolocaliza = $chatbot;

require_once 'app/app.xpcb.php';
