<?php
/*
Plugin Name: xfl-core
Plugin URI: 
Description: Nucleo delos plugins XFR
Version: 1.5
Author: Marce F.
Author URI: 
Licence: GPL2
Text Domain: XFL
Doman Path: /languages/
*/


if (!class_exists('XFL_Core')) {
  class XFL_Core {
    private $corePath = null;
    private $coreUrl = null;

    public static function getPath() {
      if (empty($corePath))
        $corePath = plugin_dir_path(__FILE__);
      return $corePath;
    }

    public static function getUrl() {
      if (empty($coreUrl))
        $coreUrl = plugin_dir_url(__FILE__);
      return $coreUrl;
    }
  }
}
/**
 * ESTILO CSSs
 */
add_action('wp_enqueue_scripts', 'carga_estilosreg');
function carga_estilosreg() {

  /** ---------------- C S S --------------------------------- */
  /* Carga Font Awesome */
  wp_enqueue_style('load-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css');
  /* Carga estilo comun theme para plugin */
  wp_enqueue_style('theme-frctl'
            , XFL_Core::getUrl() . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme-frctl.css'
            , array(), 
            '1.0.9', 'all');
  
  /* Carga stilo propio*/
  wp_enqueue_style('estilo.css'
                  , XFL_Core::getUrl() . 'assets/css/estilo.css'
                  , array()
                  , '1.0.67');
  
  /** ------------------ J S ---------------------------------- */
  /* scrips js */
  wp_enqueue_script('xyz-functions'
                  , XFL_Core::getUrl() . 'assets/js/xyz-functions.js'
                  , array()
                  , '1.0.453', false);
}


/**Carga la librerias de vendor Composer laravel para poder usar collect() y todos sus metodos */
/*if (!class_exists('ComposerAutoloaderInit7ec658522a46107b331db410568cea2b')) {
  require_once XFL_Core::getPath()  . 'classes/vendor/autoload.php';
}*/
if (!class_exists('ComposerAutoloaderInit3dc9303180f8a626c123ddad83a04b0f')) {
    require_once XFL_Core::getPath()  . 'classes/vendor/autoload.php';
}

/**
 * Carga controlador de rutas en formato class@function
 */
if (!class_exists('Route')) {
  require_once XFL_Core::getPath()  . 'classes/route.php';
}

/**
 * Carga el Master Controller que controla funciones Backend genericas
 */
if (!class_exists('MasterController')) {
  require_once XFL_Core::getPath()  . 'classes/MasterController.php';
  require_once XFL_Core::getPath()  . 'classes/master.general.routes.php';
}
