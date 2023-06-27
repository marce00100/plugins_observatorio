<?php
$xpcbPrefijRuta = "xpcb/v1/";

Route::post($xpcbPrefijRuta, 'training'     , 'Xpcb@training');
Route::post($xpcbPrefijRuta, 'xp-request'   , 'Xpcb@xpRequest');
Route::post($xpcbPrefijRuta, 'xp-mensaje'   , 'Xpcb@xpMensaje');

/** Para de prueba, test de stemmer   */
Route::get($xpcbPrefijRuta , 'frase'     , 'Xpcb@frase');



