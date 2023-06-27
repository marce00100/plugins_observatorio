<?php
use frctl\MasterController;

class ContenidosBJController extends ContenidosController { //MasterController {
	

	/** 
	 * POST CONTENIDOS TIPO_CONTENIDO de la Bilbioteca Juridica
	 * {tipo_contenido:tipo_contenido}
	 */
	public function contentsBJ(WP_REST_Request $req){
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
			return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];

		$DB = $this;
		global $xfrContenidos;

		$biblioteca = $req->biblioteca ?? '';
		$categoria = $req->categoria ?? '';
		$bibliotecaConfig = json_decode($this->getParametro((object)['dominio' => 'biblioteca_juridica', 'nombre' => $biblioteca])->config);
		$categoriaConfig = '';
		/* so es admin no filtra por estado sino que muestra todos los registros*/
		$condiciones = isset($req->admin) ? '' : ' AND aa.estado == 1 ';
		$data = [];

		/** 
		 * -----------------------------------------------------------------------------------------------------------
		 * NORMAS o JURISPRUDENCIA 
		 * -----------------------------------------------------------------------------------------------------------
		 * ambos son casi parecidos en los campos de listado que se muestran  
		 * */
		if ($biblioteca == 'normas' || $biblioteca == 'jurisprudencia') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$biblioteca}"]);
			$tribunales = $this->parametrosFrom((object)["dominio" => "tribunales"]);
			$sistemas 	= $this->parametrosFrom((object)["dominio" => "sistemas"]);
			$resultado = (object)[];  

			foreach ($categorias as $cat) {
				$categoria = $cat->nombre;
				$categoriaConfig =  json_decode($this->getParametro((object)['dominio' => "categoria_{$biblioteca}", 'nombre' => $categoria])->config);

				
				$leftJoinCondition = ($biblioteca == 'normas') ?
					" LEFT JOIN archivos a on a.modulo = 'normativa' 			and a.cod_modulo = aa.cod_normativa " :
					" LEFT JOIN archivos a on a.modulo = 'jurisprudencia' and a.cod_modulo = aa.cod_jurisprudencia 
						LEFT JOIN xfr_parametros ptrib on ptrib.dominio like 'tribunal%' and aa.tribunal = ptrib.nombre ";

				$orderPropio = ($biblioteca == 'normas') ?
					" nombre_pais, psis.orden, tema, subtema, aa.orden " :
					" ptrib.orden, nombre_pais, psis.orden, tema, subtema, aa.orden ";

				/** se colocan las que son columnas propias  */
				$columnasPropias = ($biblioteca == 'normas') ?
				" aa.cod_normativa as id_biblioteca, aa.titulo as titulo, aa.tipo " :
				" aa.cod_jurisprudencia as id_biblioteca, aa.identificador as titulo, 
					ptrib.descripcion as nombre_tribunal, concat(aa.tribunal,'-', ptrib.descripcion) as imagen_nombre_tribunal, aa.organo ";

				$query =
					"SELECT /* pcat.nombre as categoria, pcat.orden as orden_categoria,*/  
					pt.nombre as tema, pst.nombre as subtema, 
					p.pais as nombre_pais, concat(p.sigla,'-',p.pais) as imagen_nombre_pais, 
						aa.sistema as nombre_sistema, aa.sistema as imagen_nombre_sistema, aa.orden, GROUP_CONCAT(a.nombre_archivo) as archivos, {$columnasPropias}
					FROM {$bibliotecaConfig->tabla} aa 
					LEFT JOIN xfr_parametros pcat on aa.categoria = pcat.id
					LEFT JOIN xfr_parametros pt on aa.cod_tema = pt.id
					LEFT JOIN xfr_parametros pst on aa.cod_subtema = pst.id and pst.id_padre = pt.id
					LEFT JOIN xfr_parametros psis on psis.dominio like 'sistemas' and aa.sistema = psis.nombre
					LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
					{$leftJoinCondition}
					/* la linea anterior equivale a left join archivos a on a.modulo = normativa_o_jurisprudencia and a.cod_modulo = aa.cod_normativa__o__cod_jurisprudencia */
					WHERE 1 = 1 and pcat.nombre like '{$categoria}'
					GROUP BY /* categoria, orden_categoria,*/ 
					tema, subtema, nombre_pais, imagen_nombre_pais, nombre_sistema, orden, id_biblioteca									
					ORDER BY {$orderPropio}"; // nombre_pais, nombre_sistema desc, tema, subtema, {$orderpropio}";

				/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
				$data = collect($DB->select($query));
				$nivelesGroupBy = collect($categoriaConfig->niveles)->pluck('campo')->values()->all();
				$data = $data->groupBy($nivelesGroupBy);

				$resultado->$categoria = [
					'data'             => $data,
					'categoria_config' => $categoriaConfig,
					'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',
					'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
					'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',
					'tribunales'			 => $tribunales,
					'sistemas'				 => $sistemas
				];
			}

			return [
				'data_complete' => $resultado,
				'status'        => 'ok',
				'time'	        => microtime(true) - $tiempoInicio,
			];
		}

		/**  
		 * -----------------------------------------------------------------------------------------------------------
		 * JURISPRUDENCIA  RELEVANTE
		 * -----------------------------------------------------------------------------------------------------------
		 * */
		if ($biblioteca == 'jurisprudencia_relevante') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$biblioteca}"]);
			$resultado = (object)[];  

			foreach ($categorias as $cat) {
				$categoria = $cat->nombre;
				$categoriaConfig =  json_decode($this->getParametro((object)['dominio' => "categoria_{$biblioteca}", 'nombre' => $categoria])->config);
				
				// $leftJoinArchivosContition = " LEFT JOIN archivos a on a.modulo = 'recomendacion' and a.cod_modulo = aa.cod_recomendacion ";
				// $columnasPropias = " aa.cod_sentencia as id_biblioteca, aa.recomendacion as texto, comite, anio ";
				$orderPropio = " orden_tribunal, fecha desc, orden_jurisprudencia_relevante ";
				$query =
					"SELECT aa.cod_sentencia as id_biblioteca, 
					ptrib.descripcion as nombre_tribunal, concat(aa.categoria,'-', ptrib.descripcion) as imagen_nombre_tribunal, ptrib.orden as orden_tribunal,
					tema as titulo, resumen, fecha, nr_sentencia as nro_sentencia, 
					/* sentencia, razonamiento, desicion, */
					imagen, archivos, aa.orden as orden_jurisprudencia_relevante
					, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
						ELSE '' END AS imagen_sm
						
					FROM {$bibliotecaConfig->tabla} aa 
					LEFT JOIN xfr_parametros ptrib on ptrib.dominio like 'tribunal%' and aa.categoria like ptrib.nombre 
					WHERE 1 = 1
					ORDER BY {$orderPropio} ";

				$lista_contenidos = collect($DB->select($query));
				global $xfrContenidos;
				/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
				$configsTipoCont = $this->objTipoContenido($biblioteca);

				foreach ($lista_contenidos as $contenido) {
					// if(!$contenido->resumen || trim($contenido->resumen) == ''){
					// 	$contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<[^>]*>/', '', $contenido->contenido);
					// 	// $contenidoSinTags = preg_replace('/\s|\n|\r|\t/', '', $contenidoSinTags);
					// 	$contenido->resumen = substr(trim($contenidoSinTags), 0, 150);
					// }
					$contenido->resumen = substr(trim($contenido->resumen), 0, 150);
		
					/* si imagen_sm */
					if(!empty($contenido->imagen_sm) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen_sm)){
						$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen_sm;
						continue;
					}
					/* si imagen */
					else if(!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen)){
						$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
						continue;
					}
					else{
						$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
					}
					// unset($contenido->contenido);
				}


				/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
				$nivelesGroupBy = collect($categoriaConfig->niveles)->pluck('campo')->values()->all();
				$lista_contenidos = $lista_contenidos->groupBy($nivelesGroupBy);		

				$resultado->$categoria = [
					'data'             => $lista_contenidos,
					'categoria_config' => $categoriaConfig,
					'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',					
					'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
					'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/' ,
				];
			}

			return [
				'data_complete' => $resultado,
				'status'        => 'ok',
				'time'	        => microtime(true) - $tiempoInicio,
			];

		}
		/**  
		 * -----------------------------------------------------------------------------------------------------------
		 * RECOMENDACIONES 
		 * -----------------------------------------------------------------------------------------------------------
		 * */
		if ($biblioteca == 'recomendaciones') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$biblioteca}"]);
			$resultado = (object)[];  

			foreach ($categorias as $cat) {
				$categoria = $cat->nombre;
				$categoriaConfig =  json_decode($this->getParametro((object)['dominio' => "categoria_{$biblioteca}", 'nombre' => $categoria])->config);
				
				$columnasPropias = " aa.cod_recomendacion as id_biblioteca, aa.recomendacion as texto, comite, anio ";
				$leftJoinContition = " LEFT JOIN archivos a on a.modulo = 'recomendacion' and a.cod_modulo = aa.cod_recomendacion ";

				$query =
					"SELECT pt.nombre as tema, pst.nombre as subtema, 
						aa.orden, GROUP_CONCAT(a.nombre_archivo) as archivos, {$columnasPropias}
					FROM {$bibliotecaConfig->tabla} aa 
					-- left join xfr_parametros pcat on aa.categoria = pcat.id
					LEFT JOIN xfr_parametros pt on aa.cod_tema = pt.id
					LEFT JOIN xfr_parametros pst on aa.cod_subtema = pst.id and pst.id_padre = pt.id
					-- LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
					{$leftJoinContition}
					/* la linea anterior equivale a LEFT JOIN archivos a on a.modulo = normativa_o_jurisprudencia and a.cod_modulo = aa.cod_normativa__o__cod_jurisprudencia */
					LEFT JOIN comites com on aa.cod_comite = com.cod_comite
					WHERE 1 = 1
					GROUP BY tema, subtema, orden, id_biblioteca
					ORDER BY comite, tema, subtema, aa.orden";

				$data = collect($DB->select($query));
				/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
				$nivelesGroupBy = collect($categoriaConfig->niveles)->pluck('campo')->values()->all();
				$data = $data->groupBy($nivelesGroupBy);		

				$resultado->$categoria = [
					'data'             => $data,
					'categoria_config' => $categoriaConfig,
					'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',					
					'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
					'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/' ,
				];
			}

			return [
				'data_complete' => $resultado,
				'status'        => 'ok',
				'time'	        => microtime(true) - $tiempoInicio,
			];

		}

	}

	/**
	 * POST Obtiene UN CONTENIDO de la biblioteca juridica
	 * {bilioteca:biblioteca, id_biblioteca:id_biblioteca}
	 */
	public function getContentBJ(WP_REST_Request $req){
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
			return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];

		$DB = $this;
		global $xfrContenidos;

		$id_biblioteca = $req->id_biblioteca;
		$biblioteca = $req->biblioteca ?? '';
		$bibliotecaConfig = json_decode($this->getParametro((object)['dominio' => 'biblioteca_juridica', 'nombre' => $biblioteca])->config);
		
		/** NORMAS y JURISPRUDENCIA*/
		if ($biblioteca == 'normas' || $biblioteca == 'jurisprudencia') {

			$leftJoinContition = ($biblioteca == 'normas') ?
			" LEFT JOIN archivos a on a.modulo = 'normativa' 			and a.cod_modulo = aa.cod_normativa " :
			" LEFT JOIN archivos a on a.modulo = 'jurisprudencia' and a.cod_modulo = aa.cod_jurisprudencia 
				LEFT JOIN xfr_parametros ptrib on ptrib.dominio like 'tribunal%' and aa.tribunal = ptrib.nombre ";

			$whereCondition = ($biblioteca == 'normas') ?
				" and cod_normativa = {$id_biblioteca} " :
				" and cod_jurisprudencia = {$id_biblioteca} ";
			
			$columnasPropias = ($biblioteca == 'normas') ?
				" aa.cod_normativa as id_biblioteca, aa.titulo as titulo, aa.resumen, aa.tipo, aa.texto, aa.fecha, aa.pagina " :
				" aa.cod_jurisprudencia as id_biblioteca, aa.identificador as titulo, 
				ptrib.descripcion as nombre_tribunal, concat(aa.tribunal,'-', ptrib.descripcion) as imagen_nombre_tribunal, aa.organo, 
				sintesis, relevancia, extracto, fallo, dictamen, decision, recomendacion, fecha, pagina  ";

			$query =
				"SELECT pcat.nombre as categoria, pcat.descripcion as categoria_descripcion, pt.nombre as tema, pst.nombre as subtema, 
					p.pais as nombre_pais, concat(p.sigla,'-',p.pais) as imagen_nombre_pais, 
					aa.sistema as nombre_sistema, aa.sistema as imagen_nombre_sistema, GROUP_CONCAT(a.nombre_archivo) as archivos, {$columnasPropias}
				FROM {$bibliotecaConfig->tabla} aa 
				LEFT JOIN xfr_parametros pcat on aa.categoria = pcat.id
				LEFT JOIN xfr_parametros pt on aa.cod_tema = pt.id
				LEFT JOIN xfr_parametros pst on aa.cod_subtema = pst.id and pst.id_padre = pt.id
				LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
				{$leftJoinContition}
				/* la linea anterior equivale a left join archivos a on a.modulo = normativa_o_jurisprudencia and a.cod_modulo = aa.cod_normativa__o__cod_jurisprudencia */
				WHERE 1 = 1 {$whereCondition}
				GROUP BY categoria, tema, subtema, pais, sigla, sistema, id_biblioteca
				";

			$data = collect($DB->select($query))->first();

			return [
				'data'             => $data,
				'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',
				'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
				'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',

			];
		}

		if ($biblioteca == 'jurisprudencia_relevante') {

			$whereCondition = " and cod_sentencia = {$id_biblioteca} ";	
			$query =
				"SELECT aa.cod_sentencia as id_biblioteca, 
				ptrib.descripcion as nombre_tribunal, concat(aa.categoria,'-', ptrib.descripcion) as imagen_nombre_tribunal, ptrib.orden as orden_tribunal,
				tema as titulo, resumen, fecha, nr_sentencia as nro_sentencia, 
				sentencia, razonamiento, decision, 
				imagen, archivos, aa.orden as orden_jurisprudencia_relevante					
				FROM {$bibliotecaConfig->tabla} aa 
				LEFT JOIN xfr_parametros ptrib on ptrib.dominio like 'tribunal%' and aa.categoria like ptrib.nombre 
				WHERE 1 = 1  {$whereCondition}
				";

			$data = collect($DB->select($query))->first();
			if(!$data)
				return ['status' => 'error', 'msg' => 'No existe el registro.'];

			/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
			$configsTipoCont = $this->objTipoContenido($biblioteca);

			/** Acondicionamos algunos campos */
			if (!empty($data->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $data->imagen))
			$data->imagen = $configsTipoCont->urlImagenesModulo . $data->imagen;
			/* si apunta a una imagen pero no existe */
			else if (!empty($data->imagen) && !file_exists($configsTipoCont->pathImagenesModulo . $data->imagen))
			$data->imagen = null;

			return [
				'data'             => $data,
				'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',
				'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
				'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',

			];
		}

		return [
			// 'global' => $xfrContenidos,
		];

	}

	// public function contentsSentencias(WP_REST_Request $req) {
	// 	$tiempoInicio = microtime(true);
	// 	/* Quitar si no es WP */
	// 	$req = (object)$req->get_params();
	// 	$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
	// 	if($site != 'observatorio')
	// 		return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];

	// 	$DB = $this;
	// 	global $xfrContenidos;

	// 	$biblioteca = $req->tipo_contenido ?? '';
	// 	$categoria = $req->categoria ?? '';
	// 	$bibliotecaConfig = json_decode($this->getParametro((object)['dominio' => 'tipo_contenido', 'nombre' => $biblioteca])->config);
	// 	$tipoContenidoConfig = '';
	// 	/* so es admin no filtra por estado sino que muestra todos los registros*/
	// 	$condiciones = isset($req->admin) ? '' : ' AND aa.estado == 1 ';
	// 	$data = [];

	// 	/**  
	// 	 * -----------------------------------------------------------------------------------------------------------
	// 	 * SENTENCIAS PREMIADAS
	// 	 * -----------------------------------------------------------------------------------------------------------
	// 	 * */
	// 	// if ($biblioteca == 'sentencias') {
	// 	// 	$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$biblioteca}"]);
	// 	// 	$resultado = (object)[];  

	// 	// foreach ($categorias as $cat) {
	// 	// $categoria = $cat->nombre;
	// 	$tipoContenidoConfig =  json_decode($this->getParametro((object)['dominio' => "tipo_contenido", 'nombre' => 'sentencias']));
				
	// 			// $leftJoinArchivosContition = " LEFT JOIN archivos a on a.modulo = 'recomendacion' and a.cod_modulo = aa.cod_recomendacion ";
	// 			// $columnasPropias = " aa.cod_sentencia as id_biblioteca, aa.recomendacion as texto, comite, anio ";
	// 			$orderPropio = " anio desc, fechayhora_enviado desc ";
	// 			$query =
	// 				"SELECT aa.cod_sentencia as id_biblioteca, aa.fechayhora_enviado, aa.estado, aa.tema as titulo, aa.anio, sm.materia,  st.tipo, 
	// 				/* descriptores, dictada,  autoridades, hechos, proceso1, proceso2,proceso3, proceso4, analisis, */
	// 				imagen, archivos, aa.orden as orden_jurisprudencia_relevante
	// 				, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
	// 					ELSE '' END AS imagen_sm
						
	// 				FROM sentencias aa 
	// 				LEFT JOIN sentencias_materias sm on sm.cod_materia = aa.cod_materia
	// 				LEFT JOIN sentencia_tipo st on st.cod_tipo = aa.cod_tipo 
	// 				WHERE 1 = 1 and estado like 'premiada'
	// 				ORDER BY {$orderPropio} ";

	// 			$lista_contenidos = collect($DB->select($query));
	// 			global $xfrContenidos;
	// 			/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
	// 			$configsTipoCont = $this->objTipoContenido($biblioteca);

	// 			// foreach ($lista_contenidos as $contenido) {
	// 			// 	// if(!$contenido->resumen || trim($contenido->resumen) == ''){
	// 			// 	// 	$contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<[^>]*>/', '', $contenido->contenido);
	// 			// 	// 	// $contenidoSinTags = preg_replace('/\s|\n|\r|\t/', '', $contenidoSinTags);
	// 			// 	// 	$contenido->resumen = substr(trim($contenidoSinTags), 0, 150);
	// 			// 	// }
	// 			// 	$contenido->resumen = substr(trim($contenido->resumen), 0, 150);
		
	// 			// 	/* si imagen_sm */
	// 			// 	if(!empty($contenido->imagen_sm) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen_sm)){
	// 			// 		$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen_sm;
	// 			// 		continue;
	// 			// 	}
	// 			// 	/* si imagen */
	// 			// 	else if(!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen)){
	// 			// 		$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
	// 			// 		continue;
	// 			// 	}
	// 			// 	else{
	// 			// 		$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
	// 			// 	}
	// 			// 	// unset($contenido->contenido);
	// 			// }


	// 			/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
	// 			// $nivelesGroupBy = collect($tipoContenidoConfig->niveles)->pluck('campo')->values()->all();
	// 			// $lista_contenidos = $lista_contenidos->groupBy($nivelesGroupBy);		

	// 			return [
	// 				'data'             => $lista_contenidos,
	// 				'categoria_config' => $tipoContenidoConfig,
	// 				'url_archivos_ctx' => $xfrContenidos->urlArchivos,					
	// 				'url_imagenes_ctx' => $xfrContenidos->urlImagenes,
	// 				'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/' ,
	// 			];
	// 		// }

	// }


	/**
	 * POST CONSULTA GENERICA
	 * $req = { qry:query, } 
	 */
	public function consultaQuery(WP_REST_Request $req){
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);

		$DB = $this;
		$data = collect($DB->select($req->qry));
		if($req->first)
			$data = $data->first();

		return [
			'data' => $data,

		];
	}





	
	/**
	 * POST PAra insertar o actualizar a un contenido
	 */
	public function saveContenido(WP_REST_Request $req) {
		$req = (object)$req->get_params();
		$req = (object)$req->data_save;
		
		// return ['data'=>$req];
		$obj                    = (object)[];
		$obj->id                = $req->id_contenido ?? null;
		$obj->titulo            = $req->titulo;
		$obj->texto             = $req->texto;
		$obj->tipo_contenido    = $req->tipo_contenido;
		$obj->imagen            = $req->imagen ?? null;
		$obj->estado_contenido  = $req->estado_contenido;
		$obj->prioridad         = $req->prioridad;
		$obj->fecha_publicacion = $req->fecha_publicacion;

		/** solo para insert */
		!($req->id_contenido) ? $obj->fecha_registro    = $this->now() : false;
		!($req->id_contenido) ? $obj->numero_visitas    = 1 : false;
		!($req->id_contenido) ? $obj->created_at        = $this->now() : false;
		!($req->id_contenido) ? $obj->created_by        = 1 : false;
		/** Solo para caso update */
		($req->id_contenido) ? $obj->updated_at    		= $req->updated_at : false;
		($req->id_contenido) ? $obj->updated_by    		= 1 : false;

		$obj->id_contenido = $this->guardarObjetoTabla($obj, 'xfr_contenidos');

		return [
			'data'   => $obj,
			'msg'    => "Se guardó correctamente",
			"status" => "ok"
		];
	}


	/**
	 * De CLASE
	 * obtiene  objeto parametros de un tipo Contenido 
	 */
	private function objTipoContenido($tipo_contenido) {
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$paramTipoCont = $this->getParametro((object)['dominio' => 'biblioteca_juridica', 'nombre' => $tipo_contenido]);
		$configTipoCont = json_decode($paramTipoCont->config);

		$carpetaTipoCont = $configTipoCont->directorio ? $configTipoCont->directorio . '/' : ''; 

		global $xfrContenidos;

		return (object)[
			'config'							=> $configTipoCont,
			'pathImagenesModulo'  => $xfrContenidos->pathImagenes  . $carpetaTipoCont,
			'pathArchivosModulo'  => $xfrContenidos->pathArchivos  . $carpetaTipoCont,
			'urlImagenesModulo'   => $xfrContenidos->urlImagenes  . $carpetaTipoCont,
			'urlArchivosModulo'  	=> $xfrContenidos->urlArchivos  . $carpetaTipoCont,
		];
	}









}
