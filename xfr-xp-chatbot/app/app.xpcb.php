<?php
/** Comentar los controllers para LARAVEL , descomentar cuando controllers y routes sean del plugin */

/** CONTROLLERS  */
require_once $chatbot->path . "app/XpcbController.php"; 
require_once $chatbot->path . "app/ExcelXpcbController.php"; 
require_once $chatbot->path . "app/xpcb.routes.php";
/** VIEWS */
require_once $chatbot->path . "app/views/xpcb-chatbot-view.blade.php";
require_once $chatbot->path . "app/views/xpcb-control-panel-view.blade.php";

// require_once $chatbot->path . "app/views/xpm-geolocaliza-view.blade.php";
// require_once $chatbot->path . "app/views/xpm-geolocaliza-clon-view.blade.php";

/** SHORTS */
require_once $chatbot->path . "app/xpcb.shortcodes.php";


