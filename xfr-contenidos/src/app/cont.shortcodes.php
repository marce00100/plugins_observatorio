<?php
/**
 * Muestra los contenidos [fr_contenidos tipo_contenido="noticias" ]
 */
add_shortcode('fr_contenidos', function ($atts, $content) {
	$default_atts = [
		'tipo_contenido' 	=> 'noticias',
	];
  // Fusionar los atributos predeterminados con los atributos proporcionados por el usuario
  $atts = shortcode_atts($default_atts, $atts);
	global $xfrContenidos;
	//TODO modificar 
	if ($atts['tipo_contenido'] != 'sentencias')
		$html = get_view_contenidos($xfrContenidos, $atts);
	else
		$html = get_view_sentencias($xfrContenidos, $atts);
	return $html;
});

/**
 * Para Biblioteca Jjridica
 */
add_shortcode('fr_biblioteca_juridica', function ($atts, $content) {
	$default_atts = [
		'biblioteca' 			=> 'normas', // normas, jurisprudencia, recomendaciones
		'categoria' 		  => '' // nacional, regional, internacional, ddhh, ''
	];
  // Fusionar los atributos predeterminados con los atributos proporcionados por el usuario
  $atts = shortcode_atts($default_atts, $atts);
	global $xfrContenidos;
	$html = get_view_contenidos_biblioteca_juridica($xfrContenidos, $atts);
	return $html;
});

/**
 * Editor de contenidos tambien tiene la funcion de migraciosn [fr_editorcontenidos]
 */
add_shortcode('fr_contenidos_create', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_contenidos_create($xfrContenidos);
	return $html;
});
/**
 * Editor de contenidos tambien tiene la funcion de migraciosn [fr_editorcontenidos]
 */
add_shortcode('fr_normas_create', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_normas_create($xfrContenidos);
	return $html;
});
add_shortcode('fr_jurisprudencia_create', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_jurisprudencia_create($xfrContenidos);
	return $html;
});
add_shortcode('fr_recomendaciones_create', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_recomendaciones_create($xfrContenidos);
	return $html;
});
add_shortcode('fr_jurisprudencia_relevante_create', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_jurisprudencia_relevante_create($xfrContenidos);
	return $html;
});












/**
 * Migracion tablas
 */
add_shortcode('fr_contenidos_migrate', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_contenidos_migrate($xfrContenidos);
	return $html;
});
