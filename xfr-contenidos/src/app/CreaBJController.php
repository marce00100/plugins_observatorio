<?php
use frctl\MasterController;

class CreaBJController extends ContController { //MasterController {
	

	/** 
	 * POST CONTENIDOS TIPO_CONTENIDO de la Bilbioteca Juridica
	 * {tipo_contenido:tipo_contenido}
	 */
	public function contentsBJ(WP_REST_Request $req){
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
		return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];
		
		$DB = $this;
		global $xfrContenidos;

		$req = (object)$req->get_params();

		
		$tipoContBiblioteca =$req->tipo_contenido;
		$tipoContConfigs = $this->configsTipoContenido($tipoContBiblioteca);
		
		
		/* so es admin no filtra por estado sino que muestra todos los registros*/
		$condiciones = isset($req->admin) ? '' : ' AND aa.estado == 1 ';
		$data = [];

		/** 
		 * -----------------------------------------------------------------------------------------------------------
		 * NORMAS o JURISPRUDENCIA 
		 * -----------------------------------------------------------------------------------------------------------
		 * ambos son casi parecidos en los campos de listado que se muestran  
		 * */
		if ($tipoContBiblioteca == 'normas' || $tipoContBiblioteca == 'jurisprudencia') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$tipoContBiblioteca}"]);
			$tribunales = $this->parametrosFrom((object)["dominio" => "tribunales"]);
			$sistemas 	= $this->parametrosFrom((object)["dominio" => "sistemas"]);

			$resultado = (object)[];

			/** se colocan las que son columnas propias  */
			$columnasPropias = ($tipoContBiblioteca == 'normas') ?
					" aa.id as id_contenido, aa.titulo as titulo, aa.tipo "
					:	" aa.id as id_contenido, aa.identificador as titulo, 
											ptribunal.descripcion as nombre_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, aa.organo ";

			$leftJoinCondition = ($tipoContBiblioteca == 'normas') ?
					" LEFT JOIN archivos a on a.modulo = 'normativa' 			and a.cod_modulo = aa.id "
					:	" LEFT JOIN archivos a on a.modulo = 'jurisprudencia' and a.cod_modulo = aa.id 
								LEFT JOIN xfr_parametros ptribunal on ptribunal.id = aa.idp_tribunal ";

			$orderBy = ($tipoContBiblioteca == 'normas') ?
					" categoria, nombre_pais, psistema.orden, tema, subtema, aa.orden " 
					: " categoria, ptribunal.orden, nombre_pais, psistema.orden, tema, subtema, aa.orden ";

			$query =
				"SELECT pcategoria.descripcion as nombre_categoria, pcategoria.orden as orden_categoria,
						ptema.nombre as tema, ptema.orden as orden_tema, psubtema.nombre as subtema, psubtema.orden as orden_subtema,
						p.pais as nombre_pais, concat(p.sigla,'-',p.pais) as imagen_nombre_pais, 
						psistema.descripcion as nombre_sistema, psistema.nombre as imagen_nombre_sistema, psistema.orden as orden_sistema, aa.orden
						-- , GROUP_CONCAT(a.nombre_archivo) as archivos
						, aa.orden as orden_propio, aa.estado as estado_biblioteca, {$columnasPropias}
					FROM {$tipoContConfigs->config->tabla} aa 
					LEFT JOIN xfr_parametros pcategoria on aa.idp_categoria = pcategoria.id
					LEFT JOIN xfr_parametros ptema on aa.idp_tema = ptema.id
					LEFT JOIN xfr_parametros psubtema on aa.idp_subtema = psubtema.id 
					LEFT JOIN xfr_parametros psistema on aa.idp_sistema = psistema.id
					LEFT JOIN  paises p on aa.cod_pais = p.cod_pais
					{$leftJoinCondition}
					/* la linea anterior equivale a left join archivos a on a.modulo = normativa_o_jurisprudencia and a.cod_modulo = aa.id__o__id */
					WHERE 1 = 1 
					-- GROUP BY /* categoria, orden_categoria,*/ 
					-- tema, subtema, nombre_pais, imagen_nombre_pais, nombre_sistema, orden, id_contenido									
					ORDER BY titulo -- {$orderBy}"; // nombre_pais, nombre_sistema desc, tema, subtema, {$orderBy}";

			/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
			$data = collect($DB->select($query));

			return [
				'data' => $data,
				// 'data_complete' => $resultado,
				'status'        => 'ok',
				'time'	        => microtime(true) - $tiempoInicio,
			];
		}

		/**  
		 * -----------------------------------------------------------------------------------------------------------
		 * JURISPRUDENCIA  RELEVANTE
		 * -----------------------------------------------------------------------------------------------------------
		 * */

		 /* eliminar este bloque */
		if ( $tipoContBiblioteca == 'jurisprudencia_relevante') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_jurisprudencia_relevante"]);
			$tribunales = $this->parametrosFrom((object)["dominio" => "tribunales"]);

			$configsTipoCont = $this->configsTipoContenido($tipoContBiblioteca);
			$query =
				"SELECT  aa.id as id_contenido, 
						ptribunal.descripcion as nombre_tribunal, concat(ptribunal.nombre,'-', ptribunal.descripcion) as imagen_nombre_tribunal, ptribunal.orden as orden_tribunal, imagen,
						tema as titulo, fecha, nr_sentencia
						, aa.orden as orden_propio, aa.estado as estado_biblioteca
						, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
						ELSE '' END AS imagen_sm
					FROM {$tipoContConfigs->config->tabla} aa 
					LEFT JOIN xfr_parametros ptribunal on ptribunal.id = aa.idp_tribunal
					WHERE 1 = 1 								
					ORDER BY tema"; // nombre_pais, nombre_sistema desc, tema, subtema, {$orderBy}";

			/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
			$data = collect($DB->select($query));
			foreach ($data as $contenido) {
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
					$contenido->imagen_sm = '';
				}
			}
			return [
				'data' => $data,
				// 'data_complete' => $resultado,
				'status'        => 'ok',
				'url_archivos_ctx' => $tipoContConfigs->urlArchivosModulo,
				'url_imagenes_ctx' => $tipoContConfigs->urlImagenesModulo,
				'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',
				'time'						 => microtime(true) - $tiempoInicio,
			];
		}
		if ($tipoContBiblioteca == '____todo_jurisprudencia_relevante') {
			// $categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$tipoContBiblioteca}"]);
			// $resultado = (object)[];  

			// foreach ($categorias as $cat) {
			// 	$categoria = $cat->nombre;
			// 	$categoriaConfig =  json_decode($this->getParametro((object)['dominio' => "categoria_{$tipoContBiblioteca}", 'nombre' => $categoria])->config);
				
			// 	// $leftJoinArchivosContition = " LEFT JOIN archivos a on a.modulo = 'recomendacion' and a.cod_modulo = aa.cod_recomendacion ";
			// 	// $columnasPropias = " aa.cod_sentencia as id_contenido, aa.recomendacion as texto, comite, anio ";
			// 	$orderBy = " orden_tribunal, fecha desc, orden_jurisprudencia_relevante ";
			// 	$query =
			// 		"SELECT aa.cod_sentencia as id_contenido, 
			// 		ptribunal.descripcion as nombre_tribunal, concat(aa.categoria,'-', ptribunal.descripcion) as imagen_nombre_tribunal, ptribunal.orden as orden_tribunal,
			// 		tema as titulo, resumen, fecha, nr_sentencia as nro_sentencia, 
			// 		/* sentencia, razonamiento, desicion, */
			// 		imagen, archivos, aa.orden as orden_jurisprudencia_relevante
			// 		, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
			// 			ELSE '' END AS imagen_sm
						
			// 		FROM {$tipoContConfigs->config->tabla} aa 
			// 		LEFT JOIN xfr_parametros ptribunal on ptribunal.dominio like 'tribunal%' and aa.categoria like ptribunal.nombre 
			// 		WHERE 1 = 1
			// 		ORDER BY {$orderBy} ";

			// 	$lista_contenidos = collect($DB->select($query));
			// 	global $xfrContenidos;
			// 	/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
			// 	$configsTipoCont = $this->configsTipoContenido($tipoContBiblioteca);

			// 	foreach ($lista_contenidos as $contenido) {
			// 		// if(!$contenido->resumen || trim($contenido->resumen) == ''){
			// 		// 	$contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<[^>]*>/', '', $contenido->contenido);
			// 		// 	// $contenidoSinTags = preg_replace('/\s|\n|\r|\t/', '', $contenidoSinTags);
			// 		// 	$contenido->resumen = substr(trim($contenidoSinTags), 0, 150);
			// 		// }
			// 		$contenido->resumen = substr(trim($contenido->resumen), 0, 150);
		
			// 		/* si imagen_sm */
			// 		if(!empty($contenido->imagen_sm) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen_sm)){
			// 			$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen_sm;
			// 			continue;
			// 		}
			// 		/* si imagen */
			// 		else if(!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen)){
			// 			$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
			// 			continue;
			// 		}
			// 		else{
			// 			$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
			// 		}
			// 		// unset($contenido->contenido);
			// 	}


			// 	/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
			// 	$nivelesGroupBy = collect($categoriaConfig->niveles)->pluck('campo')->values()->all();
			// 	$lista_contenidos = $lista_contenidos->groupBy($nivelesGroupBy);		

			// 	$resultado->$categoria = [
			// 		'data'             => $lista_contenidos,
			// 		'categoria_config' => $categoriaConfig,
			// 		'url_archivos_ctx' => $xfrContenidos->urlArchivos . $tipoContConfig->directorio . '/',					
			// 		'url_imagenes_ctx' => $xfrContenidos->urlImagenes . $tipoContConfig->directorio . '/',
			// 		'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/' ,
			// 	];
			// }

			// return [
			// 	'data_complete' => $resultado,
			// 	'status'        => 'ok',
			// 	'time'	        => microtime(true) - $tiempoInicio,
			// ];

		}
		/**  
		 * -----------------------------------------------------------------------------------------------------------
		 * RECOMENDACIONES 
		 * -----------------------------------------------------------------------------------------------------------
		 * */
		if ($tipoContBiblioteca == 'recomendaciones') {
			$categorias = $this->parametrosFrom((object)["dominio" => "categoria_{$tipoContBiblioteca}"]);
			$resultado = (object)[];  

			foreach ($categorias as $cat) {
				$categoria = $cat->nombre;
				$categoriaConfig =  json_decode($this->getParametro((object)['dominio' => "categoria_{$tipoContBiblioteca}", 'nombre' => $categoria])->config);

				$query =
					"SELECT aa.id as id_contenido, pt.nombre as tema, psubtema.nombre as subtema, 
						LEFT(aa.recomendacion, 180) as recomendacion_cortada, pcomite.descripcion as nombre_comite, anio, aa.orden as orden_propio, aa.estado as estado_biblioteca
					FROM {$tipoContConfigs->config->tabla} aa 
					LEFT JOIN xfr_parametros pt on aa.idp_tema = pt.id
					LEFT JOIN xfr_parametros psubtema on aa.idp_subtema = psubtema.id 
					LEFT JOIN xfr_parametros pcomite on aa.idp_comite = pcomite.id
					WHERE 1 = 1
					ORDER BY recomendacion";

				$data = collect($DB->select($query));
			}

			return [
				'data' => $data,
				'status'        => 'ok',
				'time'	        => microtime(true) - $tiempoInicio,
			];
		}
	}

	/**
	 * POST Obtiene UN CONTENIDO de la biblioteca juridica
	 * {bilioteca:biblioteca, id_contenido:id_contenido}
	 */
	public function getContentBJ(WP_REST_Request $req){
		$tiempoInicio = microtime(true);
		
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
		return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];
		
		$req = (object)$req->get_params();

		$DB = $this;
		global $xfrContenidos;

		$id_contenido = $req->id_contenido;
		$tipoContBiblioteca = $req->tipo_contenido ?? '';
		$configsTipoCont = $this->configsTipoContenido($tipoContBiblioteca);
		
		/** NORMAS -  JURISPRUDENCIA - RECOMENDACIONES - JURISPRUDENCIA_RELEVANTE*/
		$query =
			"SELECT aa.*, aa.id as id_contenido FROM {$configsTipoCont->config->tabla} aa 
			WHERE 1 = 1 and aa.id = {$id_contenido}
			";

		$dataBiblioteca = collect($DB->select($query))->first();
		$dataBiblioteca->archivos = json_decode($dataBiblioteca->archivos);
		if (!empty($dataBiblioteca->imagen)) {
				$dataBiblioteca->imagenUrl = $configsTipoCont->urlImagenesModulo . $dataBiblioteca->imagen;
			}
		if (!empty($dataBiblioteca->archivos))
			foreach ($dataBiblioteca->archivos as $archivo) {
				$archivo->archivoUrl = $configsTipoCont->urlArchivosModulo . $archivo->archivo;
			}

		return [
			'data'             => $dataBiblioteca,
			'url_archivos_ctx' => $configsTipoCont->urlArchivosModulo,
			'url_imagenes_ctx' => $configsTipoCont->urlImagenesModulo,
			'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/',
			'time'						 => microtime(true) - $tiempoInicio,
		];


	}

	
	/**
	 * POST PAra insertar o actualizar a un contenido
	 */
	public function saveBJUpload(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		// return;
		$jsonData  = stripslashes($_POST['data_contenido_JSON']); /*Eliminar los escaper por demas '\\\' */		
		$data = json_decode($jsonData);

		$DB = $this;
		global $xfrContenidos;

		$tipoContBiblioteca = $data->tipo_contenido;
		$tipoContConfigs = $this->configsTipoContenido($tipoContBiblioteca);
		
		/* Se obtiene el contenido antes de ser modificado solo si existe*/
		$contenidoOld = (object)[];

		!isset($data->id_contenido) ? false :
			$contenidoOld = collect($DB->select("SELECT * from {$tipoContConfigs->config->tabla} where id = {$data->id_contenido}"))->first();

		$contenido = [];
		foreach ($data as $key => $value) {
			if($key == 'imagen' && $value !='')
				$contenido[$key] = $this->funcionesContenidos->codigoUnico() . '.' . pathinfo($data->imagen, PATHINFO_EXTENSION);
				
			if($key != 'tipo_contenido' && $key != 'id_contenido' && $key != 'imagen')
				$contenido[$key] = $value;
		}

		$contenido = (object)$contenido;
		$contenido->id = $data->id_contenido ?? null;

		/* GESTION ARCHIVOS  controla los nuevos y los que se deben borrar y actualizar el campo de la tabla */
		$controlArchivos = (object)[];
		$controlArchivos->nuevos 	 = [];
		$controlArchivos->delete	 = [];
		$controlArchivos->archivos = [];
		foreach (collect($data->archivos) as $archivo) {
			/* el objeto archivo: {nombre: , archivo:, accion_archivo: }*/
			if ($archivo->accion_archivo == 'new') {
				$objArch 					= (object)[];
				$objArch->nombre  = str_replace(' ', '_', $archivo->nombre);
				$nuevoNombre      = /* $objArch->nombre . '__' .*/ $this->funcionesContenidos->codigoUnico() . '.' . pathinfo($archivo->nombre, PATHINFO_EXTENSION);
				$objArch->archivo = $nuevoNombre;

				$controlArchivos->nuevos[] 	 = $objArch; 
				$controlArchivos->archivos[] = $objArch;
			}

			if ($archivo->accion_archivo == 'keep') {
				$objArch 					= (object)[];
				$objArch->nombre  = $archivo->nombre;
				$objArch->archivo = $archivo->archivo;

				$controlArchivos->archivos[] = $objArch; 
			}

			if($archivo->accion_archivo == 'delete'){
				$controlArchivos->delete[] = $archivo; 
			}
		}
		$contenido->archivos = count($controlArchivos->archivos) > 0 ? json_encode($controlArchivos->archivos, JSON_UNESCAPED_UNICODE) : '';
		
		/** AGREGA PARAM : verifica si es un campo relacinado a un dominio de parametros, si es , y tambien el texto no es numerico significa que es un parametro nuevo   */
		foreach ($contenido as $campo => $descripcion) {
			if ( substr($campo, 0, 3) == 'idp' && !empty($descripcion) && !is_numeric($descripcion)) {

				$campo = $campo;
				$idParamNuevo = $this->insertarParametro($campo, $descripcion);
				$contenido->$campo = $idParamNuevo;
			}
		}
		/* GUARDA CONTENIDO */
		$contenido->id_contenido = $this->guardarObjetoTabla($contenido, $tipoContConfigs->config->tabla);
	
		$directorioDestino = (object)['imagen' => $tipoContConfigs->pathImagenesModulo, 'archivo' => $tipoContConfigs->pathArchivosModulo];

		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);

		
		$mensajesMoveFile = [];
		/*  Este bloque solo correra si se ha cambiado de imagen desde el front, entonces se debe eliminar la imagen anterior */
		if (!empty($_FILES['imagen'])) {
			$imagenFile               = $_FILES['imagen'];
			$imagenNuevaDestino = $directorioDestino->imagen . $contenido->imagen;
			$mensajesMoveFile[] = $this->funcionesContenidos->moveFile($imagenFile['tmp_name'], $imagenNuevaDestino );

			/* reduce la dimension de la imagen a small */
			$nombreImagenSmall =  pathinfo($contenido->imagen, PATHINFO_FILENAME) . '_s.' . pathinfo($contenido->imagen, PATHINFO_EXTENSION);
			$this->funcionesContenidos->reducirImagen($imagenNuevaDestino, 300, false, $directorioDestino->imagen . $nombreImagenSmall);
			/* BORRAR IMAGEN ANTIGUA,*/
			if(isset($contenidoOld) && !empty($contenidoOld->imagen)){
				$imagenOld = $contenidoOld->imagen;
				$imagenOld_s =  pathinfo($imagenOld, PATHINFO_FILENAME) . '_s.' . pathinfo($imagenOld, PATHINFO_EXTENSION);
				$this->funcionesContenidos->deleteFile($directorioDestino->imagen . $imagenOld);
				$this->funcionesContenidos->deleteFile($directorioDestino->imagen . $imagenOld_s);
			}

		}

		if (!empty($_FILES['archivos']) && count($controlArchivos->nuevos) > 0 ) {
			$file  = $_FILES['archivos'];
			for ($i = 0; $i < count($file['name']); $i++) {
				$nombreFile = str_replace(' ', '_',  $file['name'][$i]);
				$archivoNew = collect($controlArchivos->nuevos)->first(function ($item) use ($nombreFile) {
													return $item->nombre == $nombreFile;
											});
				$mensajesMoveFile[] = $this->funcionesContenidos->moveFile($file['tmp_name'][$i], $directorioDestino->archivo . $archivoNew->archivo);
			}			
		}
		/* Elimina fisicamente los archivos deseleccionados */
		if(count($controlArchivos->delete) > 0 ){
				foreach ($controlArchivos->delete as $item) {
					$ArchivoDestino = $directorioDestino->archivo . $item->archivo;
					// $item->directorioDestino = $directorioDestino->archivo;
					$this->funcionesContenidos->deleteFile($ArchivoDestino);
				}
		}

		$errores = 0;
		$msgErrors = '';
		foreach ($mensajesMoveFile as $mensajeMove) {
			if($mensajeMove->status == 'error'){
				$errores ++;
				$msgErrors .= $mensajeMove->msg . ' ';
			}
		}

		return [
			'status' => $errores > 0 ? 'error' : 'ok',
			'msg'		 => $errores > 0 ? $msgErrors : 'Se guardo la informacion con los archivos correctamente ',
			'time'	 => microtime(true) - $tiempoInicio,
		];
	}




	private function insertarParametro($campo, $descripcion) {
		$relCampoDominio = [
			'idp_tema' => 'temas',
			'idp_subtema' => 'subtemas',
			'idp_tribunal' => 'tribunales',
			'idp_sistema' => 'sistemas',
		];
		$objParam = (object)[];
		$objParam->dominio      = $relCampoDominio[$campo];	
		$objParam->nombre       =  $descripcion;	
		$objParam->descripcion  = $descripcion;	
		$objParam->activo			  = 1;	
		$objParam->orden			  = 100000;	
		return $this->guardarObjetoTabla($objParam, 'xfr_parametros');
	}


	/**
	 * De CLASE
	 * obtiene  objeto parametros de un tipo Contenido 
	 */
	private function configsTipoContenido($tipo_contenido) {
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$paramTipoCont = $this->getParametro((object)['dominio' => 'biblioteca_juridica', 'nombre' => $tipo_contenido]);
		$configTipoCont = json_decode($paramTipoCont->config);

		$carpetaTipoCont = $configTipoCont->directorio ? $configTipoCont->directorio . '/' : ''; 

		global $xfrContenidos;

		return (object)[
			'config'							=> $configTipoCont,
			'paramTipoContenido'	=> $paramTipoCont,
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
	public function paises(WP_REST_Request $req){
		$DB = $this;
		return [
			'data' => collect($DB->select("SELECT * from paises "))
		];
	}




}
