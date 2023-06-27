<?php
/**
 * Muestra los contenidos [fr_contenidos tipo_contenido="noticias" sub_tipo="nacionales"]
 */
add_shortcode('fr_contenidos', function ($atts, $content) {
	$default_atts = [
		'tipo_contenido' 	=> 'noticias',
		'sub_tipo' 				=> ''
	];
  // Fusionar los atributos predeterminados con los atributos proporcionados por el usuario
  $atts = shortcode_atts($default_atts, $atts);
	global $xfrContenidos;
	//TODO modificar 
	if($atts['tipo_contenido'] != 'sentencias')
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
 * Migracion tablas
 */
add_shortcode('fr_contenidos_migrate', function ($atts, $content) {
	global $xfrContenidos;
	$html = get_view_contenidos_migrate($xfrContenidos);
	return $html;
});
