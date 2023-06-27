<?php
$masterPrefijRuta = "gral/v1/";

Route::post($masterPrefijRuta, 'get-parametros-from', 'frctl\Master@getParametrosFrom');
Route::post($masterPrefijRuta, 'get-valorparametro'   , 'frctl\Master@getValorParametro');

