<?php
require_once $xfrContenidos->path  . "src/libs-php/lib-normaliza.php";

use frctl\MasterController;
use frctl\Normaliza;
use FuncionesContenidosController as FuncionesContenidos;

class ContController extends MasterController {

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

		$condicion = '';
		$condicion .= empty($obj->estado_contenido) ? '' : " AND estado_contenido = {$obj->estado_contenido} ";
		$condicion .= empty($obj->tipo_contenido)   ? '' : " AND tipo_contenido like '{$obj->tipo_contenido}%' ";
		
		$busqueda = $obj->searchValue ? $obj->searchValue : ($obj->textoBusqueda ? $obj->textoBusqueda : '');
		$busqueda_token = !empty($busqueda) ? Normaliza::lematizaConStemSW($busqueda) : '';
		$condicionSearch = empty($busqueda_token) ? '' : 
			" AND (titulo_token like '%{$busqueda_token}%' OR resumen_token like '%{$busqueda_token}%' OR texto_token like '%{$busqueda_token}%' ) 
			OR fecha_publicacion like '%{$busqueda_token}%' OR DATE_FORMAT(fecha_publicacion, '%d/%m/%Y') like  '%{$busqueda_token}%'  ";
		// if($obj->searchValue )
		// 	$condicionSearch =  $obj->searchValue ? " AND (titulo like '%{$obj->searchValue}%' OR resumen like '%{$obj->searchValue}%' OR texto like '%{$obj->searchValue}%' )" : "";
		// else
		// 	$condicionSearch = $obj->textoBusqueda ?  " AND (titulo like '%{$obj->textoBusqueda}%' OR resumen like '%{$obj->textoBusqueda}%' OR texto like '%{$obj->textoBusqueda}%' )" : "";

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
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido si tienen tipo_contenido */
		/* si no hay tipo_contenido se esta llamando desde el buscador general */
		if (!empty($obj->tipo_contenido)) {
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
				else if(!empty($contenido->url_primera_imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen)){
					$contenido->imagen_sm = $configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
					continue;
				}
				else{
					// $contenido->path = $configsTipoCont->pathImagenesModulo . $contenido->url_primera_imagen;
					// $contenido->url =$configsTipoCont->urlImagenesModulo . $contenido->url_primera_imagen;
					$contenido->imagen_sm = $xfrContenidos->urlImagenes . 'default_img.png';
				}
			}
			unset($contenido->contenido);
			$recordsTotal = collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  "))->first()->total;
			$recordsFiltered =  collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  {$condicionSearch} "))->first()->total;
	
			return (object)[
				'data'                 => $lista_contenidos->toArray(),
				'dataTipoContenido'  	 => $configsTipoCont,
				'draw'                 => $obj->draw,
				'recordsTotal'         => $recordsTotal,
				'recordsFiltered'      => $recordsFiltered,
				'time'					       => microtime(true) - $tiempoInicio,
				'query'								=> $query
			];
		}
		/* si es de busqueda general no tienen un tipo contenido definido */
		else{

			foreach ($lista_contenidos as $contenido) {
				if(!$contenido->resumen || trim($contenido->resumen) == ''){
					$contenidoSinTags = $this->funcionesContenidos->quitarHtmlTags($contenido->contenido);
					$contenido->resumen = substr(trim($contenidoSinTags), 0, 180) . '...';
				}
			}
			unset($contenido->contenido);
			$recordsTotal = collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  "))->first()->total;
			$recordsFiltered =  collect($DB->select("SELECT count(*) as total  FROM xfr_contenidos  WHERE TRUE {$condicion}  {$condicionSearch} "))->first()->total;
	
			return (object)[
				'data'                 => $lista_contenidos->toArray(),
				// 'dataTipoContenido'  	 => $configsTipoCont,
				'draw'                 => $obj->draw,
				'recordsTotal'         => $recordsTotal,
				'recordsFiltered'      => $recordsFiltered,
				'time'					       => microtime(true) - $tiempoInicio,
			];

		}
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
	
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$configsTipoCont = $this->funcionesContenidos->objTipoContenido($contenido->tipo_contenido);

		/** Acondicionamos algunos campos */
		if (!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
			$contenido->imagen = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
		/* si apunta a una imagen pero no existe */
		else if(!empty($contenido->imagen) && !file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
			$contenido->imagen = null;

		$contenido->campos_extra = json_decode($contenido->campos_extra); 
		$contenido->archivos = json_decode($contenido->archivos);
		if (!empty($contenido->archivos))
			foreach ($contenido->archivos as $archivo) {
				$archivo->archivoUrl = $configsTipoCont->urlArchivosModulo . $archivo->archivo;
			}

		return (object)[
			'data'    => $contenido,
			'config'	=> $configsTipoCont->config,
			'status' 	=> 'ok',
			'time'		=> microtime(true) - $tiempoInicio,
		];
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
		$tiempoInicio = microtime(true);
		// return;
		$jsonData  = stripslashes($_POST['data_contenido_JSON']); /*Eliminar los escaper por demas '\\\' */
		
		$data = json_decode($jsonData);
		/* Se obtiene el contenido antes de ser modificado solo si existe*/
		$DB = $this;
		$contenidoOld = (object)[];
		!$data->id_contenido ? false :
			$contenidoOld = collect($DB->select("SELECT * from xfr_contenidos where id = {$data->id_contenido}"))->first();

		$contenido                       = (object)[];
		$contenido->id                   = $data->id_contenido ?? null;
		$contenido->tipo_contenido       = $data->tipo_contenido;
		$contenido->fecha_publicacion    = empty($data->fecha_publicacion) ? null : $data->fecha_publicacion;
		$contenido->titulo               = $data->titulo;
		$contenido->resumen              = $data->resumen ?? '';
		$contenido->contenido            = $data->contenido;
		$contenido->estado_contenido     = $data->estado_contenido;
		$contenido->texto                = $data->texto ?? '';
		$contenido->texto_token          = $data->texto_token ?? '';
		$contenido->orden                = $data->orden ?? 1;
		$contenido->campos_extra         = $data->campos_extra ? json_encode($data->campos_extra, JSON_UNESCAPED_UNICODE) : '';
		// $contenido->imagenes             = $data->imagenes ?? '';
		$contenido->texto 							 = $this->funcionesContenidos->quitarHtmlTags($contenido->contenido);
		$contenido->titulo_token				 = Normaliza::lematizaConStemSW($contenido->titulo);
		$contenido->resumen_token				 = Normaliza::lematizaConStemSW($contenido->resumen);
		$contenido->texto_token					 = Normaliza::lematizaConStemSW($contenido->texto);

		/* Si no llega imagen o vacio , entonces no se cambio la imagen*/
		empty($data->imagen) ? false : $contenido->imagen = $this->funcionesContenidos->codigoUnico() . '.' . pathinfo($data->imagen, PATHINFO_EXTENSION);;

		$user_id = get_current_user_id();
		// /** solo para insert */
		!($data->id_contenido) ? $contenido->numero_vistas  = 1            : false;
		!($data->id_contenido) ? $contenido->created_at  = $this->now()    : false;
		!($data->id_contenido) ? $contenido->created_by  = $user_id        : false;
		/** Solo para caso update */
		($data->id_contenido) ? $contenido->updated_at = $this->now() 		 : false;
		($data->id_contenido) ? $contenido->updated_by = $user_id          : false;

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
		

		/* GUARDA CONTENIDO */
		$contenido->id_contenido = $this->guardarObjetoTabla($contenido, 'xfr_contenidos');
	
		/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		$paramTipoCont = $this->funcionesContenidos->objTipoContenido($data->tipo_contenido);
		$directorioDestino = (object)['imagen' => $paramTipoCont->pathImagenesModulo, 'archivo' => $paramTipoCont->pathArchivosModulo];

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
			'msg'		 => $errores > 0 ? $msgErrors : 'Se subieron todos los archivos',
			'time'	 => microtime(true) - $tiempoInicio,
		];
	}

	

	

}
