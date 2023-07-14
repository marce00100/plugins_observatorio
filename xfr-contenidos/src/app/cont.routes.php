<?php
$contenidoPrefijRuta = "cont/v1/";

Route::post($contenidoPrefijRuta, 'get-contents'          , 'Cont@getContents');
Route::post($contenidoPrefijRuta, 'get-content'           , 'Cont@getContent');
Route::post($contenidoPrefijRuta, 'save-content-upload'   , 'Cont@saveContenidoUpload');

Route::post($contenidoPrefijRuta, 'contents-bj'           , 'ContBJ@contentsBJ');
Route::post($contenidoPrefijRuta, 'get-content-bj'        , 'ContBJ@getContentBJ');

Route::post($contenidoPrefijRuta, 'contents-crea-bj'      , 'CreaBJ@contentsBJ');
Route::post($contenidoPrefijRuta, 'get-bj'                , 'CreaBJ@getContentBJ');
Route::post($contenidoPrefijRuta, 'save-bj-upload'        , 'CreaBJ@saveBJUpload');

//TODO ver quitar o mantener
Route::post($contenidoPrefijRuta, 'contents-sentencias'   , 'Cont@getContents');
Route::post($contenidoPrefijRuta, 'get-content-sentencia' , 'ContBJ@getContentSentencia');


Route::post($contenidoPrefijRuta, 'consulta'              , 'ContBJ@consultaQuery');
Route::post($contenidoPrefijRuta, 'get-paises'            , 'CreaBJ@paises');
// Route::post($contenidoPrefijRuta, 'get-registros_uniqos'  , 'ContBJ@distinct');


Route::post($contenidoPrefijRuta, 'migrate-tables-to-xfr-contenidos-format', 'ContMigrate@migrarTablas');
Route::post($contenidoPrefijRuta, 'migrate-modulos-disponibles'            , 'ContMigrate@obtenerModulosMigrar');



