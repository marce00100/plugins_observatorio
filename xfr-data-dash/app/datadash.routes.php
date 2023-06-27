<?php
// require_once PluginDirXP::getPath()  . 'classes/route.php';




$xpcbPrefijRuta = "st/v1/";

Route::post($xpcbPrefijRuta, 'obtener-campos'     , 'datadash@obtenerCampos');
Route::post($xpcbPrefijRuta, 'obtener-dataset'   , 'datadash@getDataset');

/** Para de prueba, test de stemmer   */
Route::get($xpcbPrefijRuta , 'test'     , 'datadash@test');



