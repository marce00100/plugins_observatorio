<?php

/** short para estadistica */
add_shortcode('dd_estadistica_admin', function ($atts, $content) {
	global $datadash;
	$html = get_view_dd_estadistica_admin($datadash);
	// $html = get_view_dd_estadistica_admin(['datadash_url' => $datadash->url, 'core_url' => $datadash->core_url]);
	return $html;
});

/** short para estadistica public dashboard*/
add_shortcode('dd_estadistica_public', function ($atts, $content) {
	global $datadash;
	$html = get_view_st_dash_public(['datadash_url' => $datadash->url, 'core_url' => $datadash->core_url]);
	return $html;
});

/** short para estadistica public dashboard*/
add_shortcode('dd_indicadores', function ($atts, $content) {
	global $datadash;
	$html = get_view_dd_indicadores($datadash);
	// $html = get_view_dd_indicadores(['datadash_url' => $datadash->url, 'core_url' => $datadash->core_url]);
	return $html;
});


/** short para panel control */
add_shortcode('dd_control_panel', function ($atts, $content) {
	global $datadash;
	$html = get_view_dd_control_panel(['datadash_url' => $datadash->url, 'core_url' => $datadash->core_url]);
	return $html;
});