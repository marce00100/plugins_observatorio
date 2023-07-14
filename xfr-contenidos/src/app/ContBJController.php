<?php
use frctl\MasterController;

class ContBJController extends ContController { //MasterController {
	

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
		// $condiciones = isset($req->admin) ? '' : ' AND aa.estado == 1 ';
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

				/** se colocan las que son columnas propias  */
				$columnasPropias = ($biblioteca == 'normas') ?
				" aa.titulo as titulo, aa.tipo " :
				" aa.identificador as titulo, 
									ptribunal.descripcion as nombre_tribunal, ptribunal.orden as orden_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, aa.organo ";

				$leftJoinCondition = ($biblioteca == 'normas') ?
				" " :
				" LEFT JOIN xfr_parametros ptribunal on aa.idp_tribunal = ptribunal.id ";


				$query =
					"SELECT /* pcategoria.nombre as categoria, pcategoria.orden as orden_categoria,*/  
					aa.id as id_biblioteca, ptema.descripcion as tema, ptema.orden as orden_tema, psubtema.descripcion as subtema, psubtema.orden as orden_subtema,
					p.pais as nombre_pais, concat(p.sigla,'-',p.pais) as imagen_nombre_pais, 
						psistema.descripcion as nombre_sistema, psistema.nombre as imagen_nombre_sistema, psistema.orden as orden_sistema, 
						aa.orden as orden_propio, aa.archivos, {$columnasPropias}
					FROM {$bibliotecaConfig->tabla} aa 
					LEFT JOIN xfr_parametros pcategoria on aa.idp_categoria = pcategoria.id
					LEFT JOIN xfr_parametros ptema on aa.idp_tema = ptema.id
					LEFT JOIN xfr_parametros psubtema on aa.idp_subtema = psubtema.id 
					LEFT JOIN xfr_parametros psistema on aa.idp_sistema = psistema.id
					LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
					{$leftJoinCondition}

					WHERE 1 = 1 AND aa.estado = 1  AND pcategoria.nombre like '{$categoria}' 
					ORDER BY {$categoriaConfig->orden}, orden_propio "; 

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
				
				$query =
					"SELECT aa.id as id_biblioteca, 
					ptribunal.descripcion as nombre_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, ptribunal.orden as orden_tribunal,
					tema as titulo, resumen, fecha, nr_sentencia as nro_sentencia, 
					/* sentencia, razonamiento, desicion, */
					imagen, archivos, aa.orden as orden_propio
					, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
						ELSE '' END AS imagen_sm
						
					FROM {$bibliotecaConfig->tabla} aa 
					LEFT JOIN xfr_parametros ptribunal on ptribunal.dominio like 'tribunal%' and aa.idp_tribunal like ptribunal.id 
					WHERE 1 = 1
					ORDER BY {$categoriaConfig->orden}, orden_propio  ";

				$lista_contenidos = collect($DB->select($query));
				global $xfrContenidos;
				/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
				$configsTipoCont = $this->objTipoContenido($biblioteca);

				foreach ($lista_contenidos as $contenido) {
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
				
				$query =
					"SELECT aa.id as id_biblioteca, ptema.descripcion as tema, ptema.orden as orden_tema, psubtema.descripcion as subtema, 
						aa.orden as orden_propio, aa.archivos,  aa.recomendacion as texto, pcomite.descripcion as comite, anio 
					FROM {$bibliotecaConfig->tabla} aa 
					LEFT JOIN xfr_parametros ptema on aa.idp_tema = ptema.id
					LEFT JOIN xfr_parametros psubtema on aa.idp_subtema = psubtema.id 
					LEFT JOIN xfr_parametros pcomite on aa.idp_comite = pcomite.id
					WHERE 1 = 1
					ORDER BY {$categoriaConfig->orden}, orden_propio "; 

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
			" " :
			"	LEFT JOIN xfr_parametros ptribunal on aa.idp_tribunal = ptribunal.id ";
		
			$columnasPropias = ($biblioteca == 'normas') ?
				" aa.id as id_biblioteca, aa.titulo as titulo, aa.resumen, aa.tipo, aa.texto, aa.fecha, aa.pagina " :
				" aa.id as id_biblioteca, aa.identificador as titulo, 
				ptribunal.descripcion as nombre_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, aa.organo, 
				sintesis, relevancia, extracto, fallo, dictamen, decision, recomendacion, fecha, pagina  ";

			$query =
				"SELECT pcategoria.nombre as categoria, pcategoria.descripcion as categoria_descripcion, ptema.descripcion as tema, psubtema.descripcion as subtema, 
					p.pais as nombre_pais, concat(p.sigla,'-',p.pais) as imagen_nombre_pais, 
					psistema.descripcion as nombre_sistema, psistema.nombre as imagen_nombre_sistema, aa.archivos, {$columnasPropias}
				FROM {$bibliotecaConfig->tabla} aa 
				LEFT JOIN xfr_parametros pcategoria on aa.idp_categoria = pcategoria.id
				LEFT JOIN xfr_parametros ptema on aa.idp_tema = ptema.id
				LEFT JOIN xfr_parametros psubtema on aa.idp_subtema = psubtema.id 
				LEFT JOIN xfr_parametros psistema on aa.idp_sistema = psistema.id 
				LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
				{$leftJoinContition}
		
				WHERE 1 = 1 and aa.id = {$id_biblioteca} ";

			$data = collect($DB->select($query))->first();

			return [
				'data'             => $data,
				'url_archivos_ctx' => $xfrContenidos->urlArchivos . $bibliotecaConfig->directorio . '/',
				'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $bibliotecaConfig->directorio . '/',
				'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',

			];
		}

		if ($biblioteca == 'jurisprudencia_relevante') {

			$query =
				"SELECT aa.id as id_biblioteca, 
				ptribunal.descripcion as nombre_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, ptribunal.orden as orden_tribunal,
				tema as titulo, resumen, fecha, nr_sentencia as nro_sentencia, 
				sentencia, razonamiento, decision, 
				imagen, archivos			
				FROM {$bibliotecaConfig->tabla} aa 
				LEFT JOIN xfr_parametros ptribunal on ptribunal.dominio like 'tribunal%' and aa.idp_tribunal like ptribunal.id 
				WHERE 1 = 1 and aa.id = {$id_biblioteca} 
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
	/* POST retorn a lista de paises */
	// public function paises(WP_REST_Request $req){
	// 	$DB = $this;
	// 	return collect($DB->select("SELECT * from paises "));
	// }
	// /* POST retorn a lista de paises */
	// public function distinct(WP_REST_Request $req){
	// 	$req = (object)$req->get_params();
	// 	$tabla = $req->modulo;
	// 	$campo = $req->campo;
	// 	$DB = $this;
	// 	return collect($DB->select("SELECT distinct({$campo}) from {$tabla} "));
	// }









}
