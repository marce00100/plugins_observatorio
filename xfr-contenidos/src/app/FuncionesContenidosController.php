<?php

use frctl\MasterController;


class FuncionesContenidosController extends MasterController {
  /**
   * clase: obtiene un codigo unico de length caracteres ; su rango de opciones es length^36
   */
  public function codigoUnico($length = 10) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890'), 0, $length);
  }

  /**
	 * De CLASE
	 * obtiene  objeto parametros de un tipo Contenido 
	 * @tipo_contenido string 
	 */
	public function objTipoContenido($tipo_contenido) {
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$paramTipoCont = $this->getParametro((object)['dominio' => 'tipo_contenido', 'nombre' => $tipo_contenido]);
		$configTipoCont = json_decode($paramTipoCont->config);
		
		$carpetaTipoCont = '';
		if ($site == 'magistratura') /* Si es de Magistratura solo se tiene la carpeta noticias */
			$carpetaTipoCont = 'noticias/';
		else if ($site == 'observatorio') {
			$carpetaTipoCont = $configTipoCont->directorio ? $configTipoCont->directorio . '/' : ''; 
		}
		global $xfrContenidos;

		return (object)[
			'config'			 				=> $configTipoCont,
			/*TODO cambiar por el nuevo valor paramTipoContenido  antes param_tipo_contenido*/
			'paramTipoContenido'	=> $paramTipoCont,
			'pathImagenesModulo'  => $xfrContenidos->pathImagenes . $carpetaTipoCont,
			'pathArchivosModulo'  => $xfrContenidos->pathArchivos . $carpetaTipoCont,
			'urlImagenesModulo'   => $xfrContenidos->urlImagenes  . $carpetaTipoCont,
			'urlArchivosModulo'  	=> $xfrContenidos->urlArchivos  . $carpetaTipoCont,
		];
	}

    /**
   * Quita los tags html, styles y su contenido, xml, etc  
   */
  public function quitarHtmlTags($textoHtml) {
    $contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<script>[^>]*<\/script>|<[^>]*>/', '', $textoHtml);
    $contenidoSinTags = trim($contenidoSinTags);
    return $contenidoSinTags;
  }


	/* de prueba para comparara uuid s*/
	public function testCripts() {
		$arry = [];
		for ($i = 0; $i < 50; $i++) {
			$arry[] = uniqid('', false);
		}
		for ($i = 0; $i < 50; $i++) {
			$arry[] =  bin2hex(uniqid('', false));
		}
		for ($i = 0; $i < 50; $i++) {
			$arry[] =  bin2hex(random_bytes(16));
		}
		for ($i = 0; $i < 50; $i++) {
			$arry[] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890'), 0, 16);
		}
		return $arry;
	}

		/** PARA MEDIR TIEMPOS DE CONSULTAS ELIMINAR */
	// public function getContents(WP_REST_Request $req) {
	// 	$DB = $this;
	// 	$tiempopromedio1 = 0;
	// 	$tiempopromedio2 = 0;
	// 	$tiempopromedio3 = 0;
	// 	$n = 10;
	// 	for ($i = 0; $i < $n; $i++) {

	// 		$tiempoInicio = microtime(true);

	// 		$noticias = collect($DB->select("SELECT * from noticias n, imagenes i 
	// 																	where i.codigo = n.cod_noticia and i.modulo = 'noticia' 
	// 																	and i.categoria = 'grande' limit 0, 100"));

	// 		$tiempoInicio1 = microtime(true);
	// 		foreach ($noticias as $contenido) {
	// 			$imagen_sm 		= pathinfo($contenido->nombre, PATHINFO_FILENAME) . '_small' . "." . pathinfo($contenido->nombre, PATHINFO_EXTENSION);
	// 			$contenido->imagen_sm = $imagen_sm;

	// 			// $contenido->imagenes = $DB->select("SELECT * from imagenes where modulo = 'noticia' and codigo = {$contenido->cod_noticia}");
	// 		}
	// 		$tiempoInicio2 = microtime(true);

	// 		$time1 = $tiempoInicio1 - $tiempoInicio;
	// 		$time2 = $tiempoInicio2 - $tiempoInicio;
	// 		$tiempopromedio1 += $time1;
	// 		$tiempopromedio2 += $time2;

	// 	}
	// 	for ($i = 0; $i < $n; $i++) {

	// 		$tiempoInicio = microtime(true);

	// 		$noticiasquery = collect($DB->select("SELECT n.*, i.nombre, i.categoria as tamanio, i.ancho 
	// 								, SUBSTR(noticia, LOCATE('<img src=\"', noticia) + 10, LOCATE('\"', noticia, LOCATE('<img src=\"', noticia) + 10) - (LOCATE('<img src=\"', noticia) + 10)) AS url_primera_imagen
	// 					-- , SUBSTRING(contenido, LOCATE('<img', contenido), LOCATE('>', contenido, LOCATE('<img', contenido)) - LOCATE('<img', contenido) + 1) AS primera_imagen
	// 					, CASE WHEN (nombre IS NOT NULL AND nombre != '') THEN  CONCAT(SUBSTRING_INDEX(nombre, '.', 1), '_s.', SUBSTRING_INDEX(nombre, '.', -1)) 
	// 					ELSE '' END AS imagen_sm
	// 								from noticias n, imagenes i 
	// 																	where i.codigo = n.cod_noticia and i.modulo = 'noticia' 
	// 																	and i.categoria = 'grande' limit 0, 100"));

	// 		$tiempoInicio3 = microtime(true);
	// 		$time3 = $tiempoInicio3 - $tiempoInicio;
	// 		$tiempopromedio3 += $time3;
	// 	}
	// 	for ($i = 0; $i < $n; $i++) {

	// 		$tiempoInicio = microtime(true);

	// 		$noticiasjson = collect($DB->select("SELECT * 
	// 								, SUBSTR(noticia, LOCATE('<img src=\"', noticia) + 10, LOCATE('\"', noticia, LOCATE('<img src=\"', noticia) + 10) - (LOCATE('<img src=\"', noticia) + 10)) AS url_primera_imagen
	// 					-- , SUBSTRING(contenido, LOCATE('<img', contenido), LOCATE('>', contenido, LOCATE('<img', contenido)) - LOCATE('<img', contenido) + 1) AS primera_imagen
	// 					, CASE WHEN (nombre IS NOT NULL AND nombre != '') THEN  CONCAT(SUBSTRING_INDEX(nombre, '.', 1), '_s.', SUBSTRING_INDEX(nombre, '.', -1)) 
	// 					ELSE '' END AS imagen_sm
	// 								from noticias n, imagenes i 
	// 																	where i.codigo = n.cod_noticia and i.modulo = 'noticia' 
	// 																	and i.categoria = 'grande' limit 0, 100"));

	// 		$tiempoInicio3 = microtime(true);
	// 		$time3 = $tiempoInicio3 - $tiempoInicio;
	// 		$tiempopromedio3 += $time3;
	// 	}



	// 	return [
	// 		'noticias' => $noticias,
	// 		'noticiasquery' => $noticiasquery,
	// 		'promedio1' => $tiempopromedio1 / $n,
	// 		'promedio2' => $tiempopromedio2 / $n,
	// 		'promedio3' => $tiempopromedio3 / $n,
	// 	];
	// }




}
