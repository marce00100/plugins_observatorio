<?php
use frctl\MasterController;

class ContenidosController extends MasterController {

	/**
	 * POST Obtiene los contenidos, $request tiene la informacion del datatable
	 * Exclusivo PARA DATATABLES desde servidor con  Ajax 
	 */
	public function getContents(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$req = (object)$req->get_params();

		$this->debugDeshabilitado();
		$obj = (object)[];
		/* Parametros de DataTable */
		$obj->draw            = $req->draw ?? 1;
		$obj->start           = $req->start ?? 0;
		$obj->length          = $req->length ?? 10;
		$obj->columnIndex     = $req->order ?  $req->order[0]['column'] : 0; // Column index
		$obj->columnName      = $req->columns ? $req->columns[$obj->columnIndex]['data'] :  null; // Column name
		$obj->columnSortOrder = $req->order ? $req->order[0]['dir'] : null; // asc or desc
		$obj->searchValue     = $req->search ? html_entity_decode($req->search['value'], ENT_QUOTES | ENT_HTML5, 'UTF-8') :  null;
		$this->debugNormal();
		/* si no es admin (estado = 'todos') selecciona Solo Activos */
		(isset($req->estado_contenido) && $req->estado_contenido == 'todos') ? true : $obj->estado_contenido = 1; 
		$obj->tipo_contenido   = $req->tipo_contenido ?? '';
		
		$obj->textoBusqueda = $req->texto_busqueda ?? null;

		if ($obj->tipo_contenido != 'sentencias') {
			$contenidos = $this->getContenidos($obj);
		} else {
			$contenidos = $this->contentsSentencias($obj);
		}	

		return [
			'data'                 => $contenidos->data,
			'data_tipo_contenido'  => $contenidos->data_tipo_contenido,
			'draw'                 => $contenidos->draw,
			'recordsTotal'         => $contenidos->recordsTotal,
			'recordsFiltered'      => $contenidos->recordsFiltered,
			'query'							   => $contenidos->query,
			'time'					       => microtime(true) - $tiempoInicio,
			'obj' => $obj
		];
		
	}

	/**
	 * DE CLASE Obtiene los contenidos, $obj  tiene la informacion del datatable 
	 * Exclusivo PARA DATATABLES desde servidor con  Ajax 
	 */
	private function getContenidos($obj) {
		// mysqli_set_charset(wpdb, "utf8");
		$condicion = '';
		$condicion .= empty($obj->estado_contenido) ? '' : " AND estado_contenido = {$obj->estado_contenido} ";
		$condicion .= empty($obj->tipo_contenido)   ? '' : " AND tipo_contenido like '{$obj->tipo_contenido}%' ";
		if($obj->searchValue )
			$condicionSearch =  $obj->searchValue ? " AND (titulo like '%{$obj->searchValue}%' OR resumen like '%{$obj->searchValue}%' OR texto like '%{$obj->searchValue}%' )" : "";
		else
			$condicionSearch = $obj->textoBusqueda ?  " AND (titulo like '%{$obj->textoBusqueda}%' OR resumen like '%{$obj->textoBusqueda}%' OR texto like '%{$obj->textoBusqueda}%' )" : "";

		/* SI la columna para ordenar  es 0 lo ordena por defecto : orden desc, fecha_publicacion desc 
		(al ingresar siempre columnIndex es 0, eso en caso de que sea la vista publica, en admin la columna 0 es invisible)*/
		$orderBy = '';
		if($obj->columnIndex == 0)
			$orderBy = " orden desc, fecha_publicacion desc";
		else
			$orderBy = " {$obj->columnName} {$obj->columnSortOrder}";

		$DB = $this;
		$query = "SELECT id as id_contenido, tipo_contenido, titulo, resumen, contenido, estado_contenido, imagen
						, fecha_publicacion, numero_vistas, orden
						, SUBSTR(contenido, LOCATE('<img src=\"', contenido) + 10, LOCATE('\"', contenido, LOCATE('<img src=\"', contenido) + 10) - (LOCATE('<img src=\"', contenido) + 10)) AS url_primera_imagen
						-- , SUBSTRING(contenido, LOCATE('<img', contenido), LOCATE('>', contenido, LOCATE('<img', contenido)) - LOCATE('<img', contenido) + 1) AS primera_imagen
						, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
						ELSE '' END AS imagen_sm
						FROM xfr_contenidos  WHERE TRUE {$condicion} {$condicionSearch}
						ORDER BY {$orderBy}
						LIMIT {$obj->start}, {$obj->length} 
						";
		$lista_contenidos = collect($DB->select($query));	

		global $xfrContenidos;
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$configsTipoCont = $this->objTipoContenido($obj->tipo_contenido);

		foreach ($lista_contenidos as $contenido) {
			if(!$contenido->resumen || trim($contenido->resumen) == ''){
				$contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<[^>]*>/', '', $contenido->contenido);
				// $contenidoSinTags = preg_replace('/\s|\n|\r|\t/', '', $contenidoSinTags);
				$contenido->resumen = substr(trim($contenidoSinTags), 0, 150);
			}

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
			/* si primera_imagen */
			else if(!empty($contenido->url_primera_imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen)){
				$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
				continue;
			}
			else{
				$contenido->path = $configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen;
				$contenido->url =$configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
				$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
			}
			// unset($contenido->contenido);
		}

		$recordsTotal = collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  "))->first()->total;
		$recordsFiltered =  collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  {$condicionSearch} "))->first()->total;

		return (object)[
			'data'                 => $lista_contenidos->toArray(),
			'data_tipo_contenido'  => $configsTipoCont,
			'draw'                 => $obj->draw,
			'recordsTotal'         => $recordsTotal,
			'recordsFiltered'      => $recordsFiltered,
			'query' 					     => $query,

		];

	}

	/**
	 * POST obtener UN CONTENIDO 
	 * {id_contenido: id_contenido}
	 */
	public function getContent(WP_REST_Request $req) {
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		

		$DB = $this;
		// replace(introtext, 'img src="images/', 'img src="../images/')
		$contenido = collect($DB->select("SELECT c.id as id_contenido, c.tipo_contenido, 
										c.fecha_publicacion, c.titulo, c.resumen
										, c.contenido
										, c.imagen
										, c.estado_contenido, c.numero_vistas, c.orden, c.campos_extra, c.archivos
										FROM xfr_contenidos c 
										WHERE c.id = {$req->id_contenido}"))->first();

		if(!$contenido)
			return ['status' => 'error', 'msg' => 'No existe el registro.'];

		$this->incrementaNumeroVistas($contenido->id_contenido);
		$contenido->numero_vistas ++;

		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$carpetaSeccion = '';
		if ($site == 'magistratura') /* Si es de Magistratura solo se tiene la carpeta noticias */
			$carpetaSeccion = 'noticias/';
		else if ($site == 'observatorio') {
			$config = json_decode($this->getParametro((object)['dominio'=>'tipo_contenido','nombre'=>$contenido->tipo_contenido])->config);
			$carpetaSeccion = $config->directorio ? $config->directorio . '/' : ''; 
		}
		
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$configsTipoCont = $this->objTipoContenido($contenido->tipo_contenido);

		/** Acondicionamos algunos campos */
		if (!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
			$contenido->imagen = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
		/* si apunta a una imagen pero no existe */
		else if(!empty($contenido->imagen) && !file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
			$contenido->imagen = null;

		// Obtener el HTML modificado
		// $contenido->contenido = $dom->textContent;//->saveHTML();
		// $contenido->contenido = html_entity_decode($contenido->contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		//TODO realizar transformacion de imagen o palabraclave por la url e imagen correcta
		$contenido->contenido = str_replace('<img src="', '<img src="' . $configsTipoCont->urlImagenesModulo, $contenido->contenido);	

		$contenido->campos_extra = json_decode($contenido->campos_extra); 
		// $contenido->sistema = $site;

		return (object)[
			'data'    => $contenido,
			'config'	=> $configsTipoCont->config,
			'status' => 'ok'
		];
	}

	/**
	 * SENTENCIAS
	 */
	public function contentsSentencias($obj) {
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		// $req = (object)$req->get_params();
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
			return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];
		$condicionSearch = '';
		if($obj->searchValue )
			$condicionSearch =  $obj->searchValue ? 
			" AND (titulo like '%{$obj->searchValue}%' 
			OR materia like '%{$obj->searchValue}%' OR tipo like '%{$obj->searchValue}%' OR anio like '%{$obj->searchValue}%' )" : "";

		$DB = $this;
		global $xfrContenidos;

		$data = [];

		/**  
		 * -----------------------------------------------------------------------------------------------------------
		 * SENTENCIAS PREMIADAS
		 * -----------------------------------------------------------------------------------------------------------
		 * */
		$tipoContenidoConfig =  json_decode($this->getParametro((object)['dominio' => "tipo_contenido", 'nombre' => 'sentencias'])->config);
				
				// $leftJoinArchivosContition = " LEFT JOIN archivos a on a.modulo = 'recomendacion' and a.cod_modulo = aa.cod_recomendacion ";
				// $columnasPropias = " aa.cod_sentencia as id_biblioteca, aa.recomendacion as texto, comite, anio ";
				$orderPropio = " anio desc, fechayhora_enviado desc ";
				$query =
					"SELECT aa.cod_sentencia as id_biblioteca, aa.fechayhora_enviado, aa.estado, aa.tema as titulo, aa.anio, sm.materia,  st.tipo, analisis,
					/* descriptores, dictada,  autoridades, hechos, proceso1, proceso2,proceso3, proceso4, analisis, */
					imagen, archivos, aa.orden as orden_jurisprudencia_relevante
					, CASE WHEN (imagen IS NOT NULL AND imagen != '') THEN  CONCAT(SUBSTRING_INDEX(imagen, '.', 1), '_s.', SUBSTRING_INDEX(imagen, '.', -1)) 
						ELSE '' END AS imagen_sm
						
					FROM sentencias aa 
					LEFT JOIN sentencias_materias sm on sm.cod_materia = aa.cod_materia
					LEFT JOIN sentencias_tipos st on st.cod_tipo = aa.cod_tipo 
					WHERE 1 = 1 and estado like 'premiada' {$condicionSearch}
					ORDER BY {$orderPropio} 
					LIMIT {$obj->start}, {$obj->length}  ";

				$lista_contenidos = collect($DB->select($query));

				foreach($lista_contenidos as $item){
					$item->resumen =  substr(trim($item->analisis), 0, 250);
				}




				global $xfrContenidos;
				/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
				// $configsTipoCont = $this->objTipoContenido($biblioteca);

				// foreach ($lista_contenidos as $contenido) {
				// 	// if(!$contenido->resumen || trim($contenido->resumen) == ''){
				// 	// 	$contenidoSinTags = preg_replace('/<w[^>]*>[^>]*<\/w[^>]*>|<xml>[^>]*<\/xml>|<style>[^>]*<\/style>|<[^>]*>/', '', $contenido->contenido);
				// 	// 	// $contenidoSinTags = preg_replace('/\s|\n|\r|\t/', '', $contenidoSinTags);
				// 	// 	$contenido->resumen = substr(trim($contenidoSinTags), 0, 150);
				// 	// }
				// 	$contenido->resumen = substr(trim($contenido->resumen), 0, 150);
		
				// 	/* si imagen_sm */
				// 	if(!empty($contenido->imagen_sm) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen_sm)){
				// 		$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen_sm;
				// 		continue;
				// 	}
				// 	/* si imagen */
				// 	else if(!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen)){
				// 		$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
				// 		continue;
				// 	}
				// 	else{
				// 		$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
				// 	}
				// 	// unset($contenido->contenido);
				// }


				/** se obtiene un array con los elementos campo, que son los campos que seran parte de los niveles,  */
				// $nivelesGroupBy = collect($tipoContenidoConfig->niveles)->pluck('campo')->values()->all();
				// $lista_contenidos = $lista_contenidos->groupBy($nivelesGroupBy);		

				$recordsTotal = collect($DB->select("SELECT count(*) as total  FROM sentencias  WHERE estado like 'premiada'  "))->first()->total;

				$recordsFiltered =  collect($DB->select("SELECT count(*) as total  FROM sentencias  WHERE estado like 'premiada'  {$condicionSearch} "))->first()->total;

				return (object)[
					// 'data'             => $lista_contenidos,
					'data'            => $lista_contenidos->toArray(),
					// 'data_tipo_contenido'  => $configsTipoCont,
					'draw'            => $obj->draw,
					'recordsTotal'    => $recordsTotal,
					'recordsFiltered' => $recordsFiltered,
					'query' 					=> $query,
					'categoria_config' => $tipoContenidoConfig,
					'url_archivos_ctx' => $xfrContenidos->urlArchivos,					
					'url_imagenes_ctx' => $xfrContenidos->urlImagenes,
					'url_recursos_ctx' => $xfrContenidos->urlRecursos . 'img/' ,
				];
			// }

	}

	/**
	 * DE CLASE incrementa el numero de vistas en 1
	 */
	private function incrementaNumeroVistas($id_contenido){
		$this->statement("UPDATE xfr_contenidos set numero_vistas = numero_vistas + 1 WHERE id = {$id_contenido}");
	}


	/**
	 * POST PAra insertar o actualizar a un contenido
	 */
	public function saveContenido(WP_REST_Request $req) {
		$req = (object)$req->get_params();

		$obj                       = (object)[];
		$obj->id                   = $req->id_contenido ?? null;
		$obj->tipo_contenido       = $req->tipo_contenido;
		$obj->fecha_publicacion    = $req->fecha_publicacion;
		$obj->titulo               = $req->titulo;
		$obj->resumen              = $req->resumen??'';
		$obj->contenido            = $req->contenido;
		$req->imagen ? $obj->imagen = $req->imagen : false;
		$obj->estado_contenido     = $req->estado_contenido;
		$obj->texto                = $req->texto ??'';
		$obj->texto_corto          = $req->texto_corto ?? '';
		$obj->texto_token          = $req->texto_token ?? '';
		$obj->orden                = $req->orden ?? 1;
		$obj->campos_extra         = $req->campos_extra ? json_encode($req->campos_extra) : '';
		$obj->imagenes             = $req->imagenes ?? '';
		$obj->archivos             = (isset($req->archivos) && count($req->archivos)>0 )? implode(',', $req->archivos) : '';
		
		$user_id = get_current_user_id();
		// //TODO: no esta guardando el valor de user_id  solo coloca cero- no jala de WP
		// /** solo para insert */
		!($req->id_contenido) ? $obj->numero_vistas  = 1            : false;
		!($req->id_contenido) ? $obj->created_at  = $this->now()    : false;
		!($req->id_contenido) ? $obj->created_by  = $user_id        : false;
		/** Solo para caso update */
		($req->id_contenido) ? $obj->updated_at = $this->now() : false;
		($req->id_contenido) ? $obj->updated_by = $user_id          : false;

		$obj->id_contenido = $this->guardarObjetoTabla($obj, 'xfr_contenidos');

		return [
			'data'   => $obj,
			'msg'    => "Se guardó correctamente el registro.",
			"status" => "ok"
		];
	}

	/**
	 * POST
	 * recibe los archivos y los copia a sus carpetas respectivas
	 * se reciben a travez de metodos $_FILES y $_POST ya que vieneen uobjeto formData
	 */
	public function fileUpload() {
		// return;
		$obj  = (object)$_POST;
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$obj->paramTipoCont = $this->objTipoContenido($obj->tipo_contenido);
		$obj->site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$respuesta = [];
		if (!empty($_FILES['imagen'])) {
			$imagenFile               = $_FILES['imagen'];
			$obj->nombre              = $imagenFile['name'];
			$obj->archivoTemporal     = $imagenFile['tmp_name'];
			// $obj->size             = $imagenFile['size'];
			$obj->tipo                = 'imagen';
			$respuesta[] = $this->moveFile($obj);

		}
		if (!empty($_FILES['imagen_s'])) {
			$imagenFile               = $_FILES['imagen_s'];
			$extension = pathinfo($imagenFile['name'], PATHINFO_EXTENSION); // Obtener la extensión del archivo
			$nuevoNombre = pathinfo($imagenFile['name'], PATHINFO_FILENAME) . '_s.' . $extension; // Agregar el sufijo al nombre del archivo
			$obj->nombre              = $nuevoNombre;
			$obj->archivoTemporal     = $imagenFile['tmp_name'];
			// $obj->size             = $imagenFile['size'];
			$obj->tipo                = 'imagen';
			$respuesta[] = $this->moveFile($obj);
		}
		if (!empty($_FILES['archivos'])) {
			$file                     = $_FILES['archivos'];
			for ($i = 0; $i < count($file['name']); $i++) {
				$obj->nombre              = $file['name'][$i];
				$obj->archivoTemporal     = $file['tmp_name'][$i];
				// $obj->size             = $imagenFile['size'];
				$obj->tipo                = 'archivo';
				$respuesta[] = $this->moveFile($obj);
			}
		}

		$errores = 0;
		$msgErrors = '';
		foreach ($respuesta as $item) {
			if($item->status == 'error'){
				$errores ++;
				$msgErrors .= $item->msg;
			}
		}

		return [
			'status' => $errores > 0 ? 'error' : 'ok',
			'msg'		 => $errores > 0 ? $msgErrors : 'Se subieron todos los archivos'
		];
	}

	private function moveFile($obj) {
		// Verificar si se ha subido correctamente el archivo
		$respuesta = (object)[];
		if (is_uploaded_file($obj->archivoTemporal)) {
			$directorioDestino = ($obj->tipo == 'imagen') ?
				$obj->paramTipoCont->pathImagenesModulo : $obj->paramTipoCont->pathArchivosModulo; 

			// Mover el archivo del directorio temporal al directorio de destino
			if (move_uploaded_file($obj->archivoTemporal, $directorioDestino . $obj->nombre)) {
				$respuesta->status = "ok";
				$respuesta->msg = "{$obj->tipo} {$obj->nombre}, almacenado correctamente.";
			} 
			else {
				$respuesta->status = "error";
				$respuesta->msg =  "{$obj->tipo} {$obj->nombre}, Error al almacenar el archivo.";
			}
		} 
		else {
			$respuesta->status = "error";
			$respuesta->msg = "{$obj->tipo} {$obj->nombre}, Error al subir el archivo.";
		}
		return $respuesta;
	}

	/**
	 * De CLASE
	 * obtiene  objeto parametros de un tipo Contenido 
	 */
	private function objTipoContenido($tipo_contenido) {
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
			'config'							     => $configTipoCont,
			'param_tipo_contenido'		 => $paramTipoCont,
			'pathImagenesModulo'       => $xfrContenidos->pathImagenes . $carpetaTipoCont,
			'pathArchivosModulo'       => $xfrContenidos->pathArchivos . $carpetaTipoCont,
			'urlImagenesModulo'        => $xfrContenidos->urlImagenes  . $carpetaTipoCont,
			'urlArchivosModulo'  	     => $xfrContenidos->urlArchivos  . $carpetaTipoCont,
		];
	}

}
