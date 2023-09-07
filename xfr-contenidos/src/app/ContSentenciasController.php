<?php
require_once $xfrContenidos->path  . "src/libs-php/lib-normaliza.php";

use frctl\MasterController;
use frctl\Normaliza;
use FuncionesContenidosController as FuncionesContenidos;

class ContSentenciasController extends MasterController {

	public $funcionesContenidos;

	public function __construct()	{
		$this->funcionesContenidos = new FuncionesContenidos();
	}

	

	/**
	 * SENTENCIAS
	 */
	public function getContentsSentencias(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		$req = (object)$req->get_params();
		/* Quitar si no es WP */
		// $req = (object)$req->get_params();
		$site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
		if($site != 'observatorio')
			return ['status' => 'error', 'msg' => 'No es el sitio del observatorio'];

		$DB = $this;
		global $xfrContenidos;
		$condiciones = isset($req->admin) ? '' : " AND estado like 'premiada' ";
		$data = [];

		$query =
			"SELECT aa.id as id_sentencia, aa.fechayhora_enviado, aa.estado as estado_contenido, aa.tema as titulo, 
					aa.anio, p_sentencias_materias.descripcion as materia,  p_sentencias_tipos.descripcion as tipo, analisis,
					descriptores, dictada,  autoridades , /* hechos, proceso1, proceso2,proceso3, proceso4, analisis, */
					archivos, aa.orden 
					FROM x_sentencias_premiadas aa 
					LEFT JOIN xfr_parametros p_sentencias_materias on p_sentencias_materias.id = aa.idp_sentencia_materia 
					LEFT JOIN xfr_parametros p_sentencias_tipos on p_sentencias_tipos.id = aa.idp_sentencia_tipo
					WHERE 1 = 1  {$condiciones}
					ORDER BY aa.orden,  anio desc, fechayhora_enviado desc  ";

		$lista_contenidos = collect($DB->select($query));

		foreach ($lista_contenidos as $item) {
			$item->resumen =  substr(trim($item->analisis), 0, 250);
			unset($item->analisis);
		}

		global $xfrContenidos;

		return (object)[
			'data'            => $lista_contenidos->toArray(),
			'status' 	=> 'ok',
			'time'		=> microtime(true) - $tiempoInicio,
		];

	}

	/**
	 * POST obtener UN CONTENIDO Sentencia premiada
	 * {id_contenido: id_contenido}
	 */
	public function getContentSentencia(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$req = (object)$req->get_params();
		$DB = $this;
		// replace(introtext, 'img src="images/', 'img src="../images/')
		$contenido = collect($DB->select(
									"SELECT aa.*, aa.id as id_sentencia, p_sentencias_materias.descripcion as materia,  p_sentencias_tipos.descripcion as tipo
									FROM x_sentencias_premiadas aa 
									LEFT JOIN xfr_parametros p_sentencias_materias on p_sentencias_materias.id = aa.idp_sentencia_materia 
									LEFT JOIN xfr_parametros p_sentencias_tipos on p_sentencias_tipos.id = aa.idp_sentencia_tipo
									WHERE aa.id = {$req->id_sentencia}"))->first();

		if(!$contenido)
			return ['status' => 'error', 'msg' => 'No existe el registro.'];

			/* Se obtiene las configuraciones de rutas del parametro del tipo contenido */
		// $configsTipoCont = $this->funcionesContenidos->objTipoContenido($contenido->tipo_contenido);

		// /** Acondicionamos algunos campos */
		// if (!empty($contenido->imagen) && file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
		// 	$contenido->imagen = $configsTipoCont->urlImagenesModulo . $contenido->imagen;
		// /* si apunta a una imagen pero no existe */
		// else if(!empty($contenido->imagen) && !file_exists($configsTipoCont->pathImagenesModulo . $contenido->imagen))
		// 	$contenido->imagen = null;

		// $contenido->campos_extra = json_decode($contenido->campos_extra); 
		$carpetaTipoCont = 'sentencia/'; 
		global $xfrContenidos;
		$configsTipoCont = (object)[
			'pathImagenesModulo'  => $xfrContenidos->pathImagenes . $carpetaTipoCont,
			'pathArchivosModulo'  => $xfrContenidos->pathArchivos . $carpetaTipoCont,
			'urlImagenesModulo'   => $xfrContenidos->urlImagenes  . $carpetaTipoCont,
			'urlArchivosModulo'  	=> $xfrContenidos->urlArchivos  . $carpetaTipoCont,
		];

		$contenido->archivos = json_decode($contenido->archivos);
		if (!empty($contenido->archivos))
			foreach ($contenido->archivos as $archivo) {
				$archivo->archivoUrl = $configsTipoCont->urlArchivosModulo . $archivo->archivo;
			}

		return (object)[
			'data'    => $contenido,
			// 'config'	=> $configsTipoCont->config,
			'status' 	=> 'ok',
			'time'		=> microtime(true) - $tiempoInicio,
		];
	}


	/**
	 * POST PAra insertar o actualizar a un contenido
	 */
	public function saveSentenciaUpload(WP_REST_Request $req) {
		$tiempoInicio = microtime(true);
		// return;
		$jsonData  = stripslashes($_POST['data_contenido_JSON']); /*Eliminar los escaper por demas '\\\' */		
		$data = json_decode($jsonData);

		$DB = $this;
		global $xfrContenidos;

		// $tipoContBiblioteca = $data->tipo_contenido;
		// $tipoContConfigs = $this->configsTipoContenido($tipoContBiblioteca);
		$carpetaTipoCont = 'sentencia/';
		$tipoContConfigs = (object)[
			'pathImagenesModulo'  => $xfrContenidos->pathImagenes  . $carpetaTipoCont,
			'pathArchivosModulo'  => $xfrContenidos->pathArchivos  . $carpetaTipoCont,
			'urlImagenesModulo'   => $xfrContenidos->urlImagenes  . $carpetaTipoCont,
			'urlArchivosModulo'  	=> $xfrContenidos->urlArchivos  . $carpetaTipoCont,
		];
		
		/* Se obtiene el contenido antes de ser modificado solo si existe*/
		$contenidoOld = (object)[];

		!isset($data->id_sentencia) ? false :
			$contenidoOld = collect($DB->select("SELECT * from x_sentencias_premiadas where id = {$data->id_sentencia}"))->first();

		$contenido = [];
		foreach ($data as $key => $value) {
			if($key == 'imagen' && $value !='')
				$contenido[$key] = $this->funcionesContenidos->codigoUnico() . '.' . pathinfo($data->imagen, PATHINFO_EXTENSION);
				
			if($key != 'tipo_contenido' && $key != 'id_sentencia' && $key != 'imagen')
				$contenido[$key] = $value;
		}

		$contenido = (object)$contenido;
		$contenido->id = $data->id_sentencia ?? null;
		$contenido->fechayhora_enviado = $this->now();

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
		
		/** AGREGA PARAM : verifica si es un campo relacinado a un dominio de parametros (empieza con idp_  ), 
		 * si es , el texto deberia ser un id numerico pero si el texto no es numerico significa que es un parametro nuevo   */
		foreach ($contenido as $campo => $descripcion) {
			if ( substr($campo, 0, 3) == 'idp' && !empty($descripcion) && !is_numeric($descripcion)) {
				$campo = $campo;
				$idParamNuevo = $this->insertarParametro($campo, $descripcion);
				$contenido->$campo = $idParamNuevo;
			}
		}
		/* GUARDA CONTENIDO */
		$contenido->id_sentencia = $this->guardarObjetoTabla($contenido, 'x_sentencias_premiadas');
	
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
			'idp_sentencia_materia' => 'sentencias_materias',
			'idp_sentencia_tipo' => 'sentencias_tipos',
		];
		$objParam = (object)[];
		$objParam->dominio      = $relCampoDominio[$campo];	
		$objParam->nombre       =  $descripcion;	
		$objParam->descripcion  = $descripcion;	
		$objParam->activo			  = 1;	
		$objParam->orden			  = 100000;	
		return $this->guardarObjetoTabla($objParam, 'xfr_parametros');
	}
}
