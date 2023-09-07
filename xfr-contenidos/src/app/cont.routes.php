<?php
$contenidoPrefijRuta = "cont/v1/";

Route::post($contenidoPrefijRuta, 'get-contents'          , 'Cont@getContents');
Route::post($contenidoPrefijRuta, 'get-content'           , 'Cont@getContent');
Route::post($contenidoPrefijRuta, 'save-content-upload'   , 'Cont@saveContenidoUpload');

/* Obtiene la BJ para la vista al publico */
Route::post($contenidoPrefijRuta, 'contents-bj'           , 'ContBJ@contentsBJ');
Route::post($contenidoPrefijRuta, 'get-content-bj'        , 'ContBJ@getContentBJ');

/* Obtiene la biblioteca juridica segun el tipo , para la creacion de las mismas*/
Route::post($contenidoPrefijRuta, 'contents-crea-bj'      , 'CreaBJ@contentsBJ');
Route::post($contenidoPrefijRuta, 'get-bj'                , 'CreaBJ@getContentBJ');
Route::post($contenidoPrefijRuta, 'save-bj-upload'        , 'CreaBJ@saveBJUpload');

/* Para las sentencias premiadas, vista publica*/
Route::post($contenidoPrefijRuta, 'contents-sentencias'   , 'ContSentencias@getContentsSentencias');
Route::post($contenidoPrefijRuta, 'get-content-sentencia' , 'ContSentencias@getContentSentencia');
Route::post($contenidoPrefijRuta, 'save-sentencia-upload' , 'ContSentencias@saveSentenciaUpload');


Route::post($contenidoPrefijRuta, 'consulta'              , 'ContBJ@consultaQuery');
Route::post($contenidoPrefijRuta, 'get-paises'            , 'CreaBJ@paises');
// Route::post($contenidoPrefijRuta, 'get-registros_uniqos'  , 'ContBJ@distinct');


Route::post($contenidoPrefijRuta, 'migrate-tables-to-xfr-contenidos-format', 'ContMigrate@migrarTablas');
Route::post($contenidoPrefijRuta, 'migrate-modulos-disponibles'            , 'ContMigrate@obtenerModulosMigrar');



