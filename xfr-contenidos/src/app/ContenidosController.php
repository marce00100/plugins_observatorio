<?php
use frctl\MasterController;
use FuncionesContenidosController as FuncionesContenidos;
class ContenidosController extends MasterController {

	public $funcionesContenidos;

	public function __construct()	{
		$this->funcionesContenidos = new FuncionesContenidos();
	}

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

		/* SENTENCIAS */
		if ($obj->tipo_contenido == 'sentencias') {
			$contenidos = $this->contentsSentencias($obj);
			return ['TODO' => 'Revisar sentencias premiadas '];
		}	

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
			$orderBy = " orden desc, fecha_publicacion desc, id_contenido desc";
		else
			$orderBy = " {$obj->columnName} {$obj->columnSortOrder}, id_contenido {$obj->columnSortOrder} ";

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
		$configsTipoCont = $this->funcionesContenidos->objTipoContenido($obj->tipo_contenido);

		foreach ($lista_contenidos as $contenido) {
			if(!$contenido->resumen || trim($contenido->resumen) == ''){
				$contenidoSinTags = $this->funcionesContenidos->quitarHtmlTags($contenido->contenido);
				$contenido->resumen = substr(trim($contenidoSinTags), 0, 180) . '...';
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
			// else if(!empty($contenido->url_primera_imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen)){
			// 	$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
			// 	continue;
			// }
			else{
				$contenido->path = $configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen;
				$contenido->url =$configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
				$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
			}
			unset($contenido->contenido);
		}

		$recordsTotal = collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  "))->first()->total;
		$recordsFiltered =  collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  {$condicionSearch} "))->first()->total;

		
		return (object)[
			'data'                 => $lista_contenidos->toArray(),
			'dataTipoContenido'  	 => $configsTipoCont,
			'draw'                 => $obj->draw,
			'recordsTotal'         => $recordsTotal,
			'recordsFiltered'      => $recordsFiltered,
			'time'					       => microtime(true) - $tiempoInicio,
			// 'query' 					     => $query,
		];
	}

	/**
	 * POST obtener UN CONTENIDO 
	 * {id_contenido: id_contenido}
	 */
	public function getContent(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		$DB = $this;
		// replace(introtext, 'img src="images/', 'img src="../images/')
		$contenido = collect($DB->select(
									"SELECT c.id as id_contenido, c.tipo_contenido, 
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

		/* Se determina el SITIO */
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		$carpetaSeccion = '';
		if ($site == 'magistratura') /* Si es de Magistratura solo se tiene la carpeta noticias */
			$carpetaSeccion = 'noticias/';
		else if ($site == 'observatorio') {
			$config = json_decode($this->getParametro((object)['dominio'=>'tipo_contenido','nombre'=>$contenido->tipo_contenido])->config);
			$carpetaSeccion = $config->directorio ? $config->directorio . '/' : ''; 
		}
		
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$configsTipoCont = $this->funcionesContenidos->objTipoContenido($contenido->tipo_contenido);

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
		$contenido->archivos = json_decode($contenido->archivos);
		// $contenido->sistema = $site;

		return (object)[
			'data'    => $contenido,
			'config'	=> $configsTipoCont->config,
			'status' => 'ok',
			'time'					       => microtime(true) - $tiempoInicio,
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
	 * POST 
	 * Guarda el fomulario de contenidos, con sus componentes extras,  tambien realiza el upload de imagenes  archivos
	 * la informacion del contenido esta en data_contenido_JSON
	 */
	public function saveContenidoUpload() {
		// return;
		$jsonData  = stripslashes($_POST['data_contenido_JSON']); /*Eliminar los escaper por demas '\\\' */
		
		$data = json_decode($jsonData);
		$contenido                       = (object)[];
		$contenido->id                   = $data->id_contenido ?? null;
		$contenido->tipo_contenido       = $data->tipo_contenido;
		$contenido->fecha_publicacion    = empty($data->fecha_publicacion) ? null : $data->fecha_publicacion;
		$contenido->titulo               = $data->titulo;
		$contenido->resumen              = $data->resumen ?? '';
		$contenido->contenido            = $data->contenido;
		$contenido->estado_contenido     = $data->estado_contenido;
		$contenido->texto                = $data->texto ?? '';
		$contenido->texto_corto          = $data->texto_corto ?? '';
		$contenido->texto_token          = $data->texto_token ?? '';
		$contenido->orden                = $data->orden ?? 1;
		$contenido->campos_extra         = $data->campos_extra ? json_encode($data->campos_extra) : '';
		$contenido->imagenes             = $data->imagenes ?? '';
		
		/* Si no llega imagen o vacio , entonces no se cambio la imagen*/
		empty($data->imagen) ? false : $contenido->imagen = $data->imagen;

		/* gestion Archivos controla los nuevos y los que se deben borrar */
		$controlArchivos = (object)[];
		$controlArchivos->nuevos = [];
		$controlArchivos->delete = [];
		$controlArchivos->archivos = [];
		foreach (collect($data->archivos) as $archivo) {
			if ($archivo->estado == 'nuevo') {
				$objArch = (object)[];
				$objArch->nombre = str_replace(' ', '_', $archivo->nombre);
				$objArch->archivo = $objArch->nombre . '__' . $this->funcionesContenidos->codigoUnico(). '.' . pathinfo($archivo->nombre, PATHINFO_EXTENSION);
				$controlArchivos->nuevos[] = $objArch; 
				$controlArchivos->archivos[] = $objArch;
			}
			if($archivo->estado == 'delete'){
				// unset($archivo->estado);
				$controlArchivos->delete[] = $archivo; 
			}
			if ($archivo->estado == 'server') {
				unset($archivo->estado);
				$controlArchivos->archivos[] = $archivo; 
			}
		}
		$contenido->archivos = count($controlArchivos->archivos) > 0 ? json_encode($controlArchivos->archivos) : '';
		

		
		$user_id = get_current_user_id();
		// /** solo para insert */
		!($data->id_contenido) ? $contenido->numero_vistas  = 1            : false;
		!($data->id_contenido) ? $contenido->created_at  = $this->now()    : false;
		!($data->id_contenido) ? $contenido->created_by  = $user_id        : false;
		/** Solo para caso update */
		($data->id_contenido) ? $contenido->updated_at = $this->now() 		 : false;
		($data->id_contenido) ? $contenido->updated_by = $user_id          : false;

		/* GUARDA CONTENIDO */
		$contenido->id_contenido = $this->guardarObjetoTabla($contenido, 'xfr_contenidos');
	
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$paramTipoCont = $this->funcionesContenidos->objTipoContenido($data->tipo_contenido);
		$directorioDestino = ['imagen' => $paramTipoCont->pathImagenesModulo, 'archivo' => $paramTipoCont->pathArchivosModulo];

		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);

		$respuesta = [];
		if (!empty($_FILES['imagen'])) {
			$imagenFile               = $_FILES['imagen'];
			$archivo = (object)[
				'nombre'            => $imagenFile['name'],
				'archivoTemporal'   => $imagenFile['tmp_name'],
				'directorioDestino' => $directorioDestino['imagen']
			];
			// $data->nombre              = $imagenFile['name'];
			// $data->archivoTemporal     = $imagenFile['tmp_name'];
			// $data->tipo                = 'imagen';
			$respuesta[] = $this->moveFile($archivo);
		}
		if (!empty($_FILES['imagen_s'])) {
			$imagenFile               = $_FILES['imagen_s'];
			$extension = pathinfo($imagenFile['name'], PATHINFO_EXTENSION); // Obtener la extensión del archivo
			$nuevoNombre = pathinfo($imagenFile['name'], PATHINFO_FILENAME) . '_s.' . $extension; // Agregar el sufijo al nombre del archivo
			$archivo = (object)[
				'nombre'            => $nuevoNombre,
				'archivoTemporal'   => $imagenFile['tmp_name'],
				'directorioDestino' => $directorioDestino['imagen']
			];
			// $data->nombre              = $nuevoNombre;
			// $data->archivoTemporal     = $imagenFile['tmp_name'];
			// $data->tipo                = 'imagen';
			$respuesta[] = $this->moveFile($archivo);
		}
		if (!empty($_FILES['archivos'])) {
			$file  = $_FILES['archivos'];
			for ($i = 0; $i < count($file['name']); $i++) {
				$nombreFile = str_replace(' ', '_',  $file['name'][$i]);
				$archivoNew = collect($controlArchivos->nuevos)->first(function ($item) use ($nombreFile) {
													return $item->nombre == $nombreFile;
											});
				$archivo = (object)[
					'nombre'            => $archivoNew->archivo,
					'archivoTemporal'   => $file['tmp_name'][$i],
					'directorioDestino' => $directorioDestino['archivo']
				];
				// $data->nombre              = $archivoNew->archivo;
				// $data->archivoTemporal     = $file['tmp_name'][$i];
				// // $data->size             = $imagenFile['size'];
				// $data->tipo                = 'archivo';
				$respuesta[] = $this->moveFile($archivo);
			}
		}

		$errores = 0;
		$msgErrors = '';
		foreach ($respuesta as $item) {
			if($item->status == 'error'){
				$errores ++;
				$msgErrors .= $item->msg . ' ';
			}
		}

		return [
			'status' => $errores > 0 ? 'error' : 'ok',
			'msg'		 => $errores > 0 ? $msgErrors : 'Se subieron todos los archivos'
		];
	}

	private function moveFile($obj) {
		// Verificar si se ha subido correctamente el archivo
		$respuesta = [];
		if (is_uploaded_file($obj->archivoTemporal)) {
			// Mover el archivo del directorio temporal al directorio de destino
			if (move_uploaded_file($obj->archivoTemporal, $obj->directorioDestino . $obj->nombre)) 
				$respuesta = ['status' => "ok", 'msg' => "{$obj->nombre}, almacenado correctamente."];
			else 
				$respuesta = ['status' => "error", 'msg' =>  "{$obj->nombre}, Error al almacenar el archivo."];
		} 
		else
			$respuesta = ['status' => "error", 'msg' => "{$obj->nombre}, Error al subir el archivo."];
		
		return (object)$respuesta;
	}

	private function deleteFile($obj) {
		// $directorioDestino = ($obj->tipo == 'imagen') ?
		// 	$obj->paramTipoCont->pathImagenesModulo : $obj->paramTipoCont->pathArchivosModulo;
		$rutaArchivo = $obj->directorioDestino . $obj->archivo;
		if (file_exists($rutaArchivo)) {
			if (unlink($rutaArchivo)) {
				echo "El archivo se ha eliminado correctamente.";
			} else {
				echo "No se pudo eliminar el archivo.";
			}
		}
	}

	

}
