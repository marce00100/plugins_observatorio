<?php
$contenidoPrefijRuta = "cont/v1/";

Route::post($contenidoPrefijRuta, 'get-contents'   , 'Contenidos@getContents');
Route::post($contenidoPrefijRuta, 'get-content'    , 'Contenidos@getContent');
Route::post($contenidoPrefijRuta, 'save-content'   , 'Contenidos@saveContenido');
Route::post($contenidoPrefijRuta, 'file-upload'    , 'Contenidos@fileUpload');

Route::post($contenidoPrefijRuta, 'contents-bj'    , 'ContenidosBJ@contentsBJ');
Route::post($contenidoPrefijRuta, 'get-content-bj' , 'ContenidosBJ@getContentBJ');

//TODO ver quitar o mantener
Route::post($contenidoPrefijRuta, 'contents-sentencias'    , 'Contenidos@getContentsPublic');
Route::post($contenidoPrefijRuta, 'get-content-sentencia' , 'ContenidosBJ@getContentSentencia');


Route::post($contenidoPrefijRuta, 'consulta'       , 'ContenidosBJ@consultaQuery');

Route::post($contenidoPrefijRuta, 'migrate-tables-to-xfr-contenidos-format', 'ContenidosMigrate@migrarTablas');



