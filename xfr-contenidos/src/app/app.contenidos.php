<?php
/** Comentar para LARAVEL , descomentar cuando controller y routes del plug */
require_once $xfrContenidos->path . "src/app/FuncionesContenidosController.php"; 
require_once $xfrContenidos->path . "src/app/ContenidosController.php"; 
require_once $xfrContenidos->path . "src/app/ContenidosBJController.php"; 
require_once $xfrContenidos->path . "src/app/ContenidosMigrateController.php"; 
require_once $xfrContenidos->path . "src/app/contenidos.routes.php";

/** Se cargan los archivos de los shortcodes y sus vistas  */
require_once $xfrContenidos->path . "src/app/views/cont-contenidos.blade.php";
require_once $xfrContenidos->path . "src/app/views/cont-biblioteca-juridica.blade.php";
require_once $xfrContenidos->path . "src/app/views/cont-sentencias.blade.php";
require_once $xfrContenidos->path . "src/app/views/cont-create.blade.php";
require_once $xfrContenidos->path . "src/app/views/cont-migrate.blade.php";

require_once $xfrContenidos->path . "src/app/contenidos.shortcodes.php";

/** Se cargan funciones adicionales  */

/* Funcion gloobal  en _todo el sitio : agregar boton de busqueda al final del menu */
/* funcion que agrega un boton con jquery */
require_once $xfrContenidos->path . "src/app/views/cont-addaction-search.blade.php";
add_action('wp_footer', 'agregarBotonSearch');
