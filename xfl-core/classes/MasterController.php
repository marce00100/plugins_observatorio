<?php
namespace frctl;

use WP_REST_Request;
use XFL_Core;

class MasterController {

	/***********************  FUNCIONES GENERALES *********************** */
	
	protected function params() {
		$dirWP = ABSPATH; /* el path del ditrrectorio donde esta wordpress */
		$urlWP = get_site_url(); /*La url   del itio wordpress */
		return (object)[
			'pathWP'         => $dirWP,
			'urlWP'          => $urlWP,
			'pathArchivos'	 => $dirWP . 'wp-content/archivos/',
			// 'urlArchivos'	 	 => $dirWP . 'wp-content/archivos/',
		];
	}

	/**
	 * Retorna la fecha y hora en zona horaria -4  BOLIVIA
	 */
	protected function now() {
		return date("Y-m-d H:i:s", time() - 4 * 60 * 60);
	}

/** ******** ****          FUNCIONES PARA CONSULTAS Y QUERYS PARA LA BD                ************************/

	/** FUNCIONES SQL para LARAVEL , si no se utilizan comentarlas -  No Borrar */
	// protected function select($query){
	//     return \DB::select($query);
	// } 

	// protected function statement($query){
	//     return \DB::statement($query);
	// }

	/** FUNCIONES SQL para WP , si no se utilizan comentarlas -  No Borrar */
	protected function select($query) {
		global $wpdb;
		$wpdb = isset($wpdb) ? $wpdb : (object)[];
		return $wpdb->get_results($query);
	}

	protected function statement($query) {
		global $wpdb;
		$wpdb = isset($wpdb) ? $wpdb : (object)[];
		return $wpdb->query($query);
	}


		/**
	 * Funcion Generica para incsertar o modificar las tablas
	 * (se usa cuando el id se autogenera en la BD, autonumerico, serial, trigger, etc)
	 */
	protected function guardarObjetoTabla($obj, $tabla, $datosAuditoria = false) {

		try {
			global $wpdb;
			if (isset($obj->id) && $obj->id !== '') // UPDATE 
			{
				// $obj->activo =  true;
				// if ($datosAuditoria) {
				// 	$obj->updated_by = $this->usuario->id ?? null;
				// 	$obj->updated_at = $this->now();
				// }
				$wpdb->update($tabla, get_object_vars($obj),  array('id' => $obj->id));
				// \DB::table($tabla)->where('id', $obj->id)->update(get_object_vars($obj));
				return $obj->id;
			} else // INSERT
			{
				unset($obj->id);
				// $obj->activo = true;
				// if ($datosAuditoria) {
				// 	$obj->created_by =  $this->usuario->id ?? null;
				// 	$obj->created_at =  $this->now();
				// }
				$wpdb->insert($tabla, get_object_vars($obj));
				return $wpdb->insert_id;
				// return \DB::table($tabla)->insertGetId(get_object_vars($obj));
			}
		} catch (Exception $e) {
			return (object)[
				'status' => "error",
				'msg'    => $e->getMessage()
			];
			// return response()->json(
			// 	array(
			// 		'status' => "error",
			// 		'msg'    => $e->getMessage()
			// 	)
			// );
		}
	}

	/**
	 * Desactivar temporariamente la opción de debug
	 */
	protected function debugDeshabilitado(){
		if (WP_DEBUG) {
			error_reporting(0);
			ini_set('display_errors', 0);
		}
	}
	/**
	 * Restaurar la configuración original de debug
	 */
	protected function debugNormal(){
		if (WP_DEBUG) {
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
	}

	/**
	 * CLASE Obtiene losparametros de un dominio 
	 * {dominio:dominio} 
	 * o hijos de un padre {id_padre:id_padre} 
	 * o hijos de un parametro con dominio y nombre {hijos_de:1, dominio_dominio, nombre:nombre}
	 */
	protected function parametrosFrom($obj) {
		$DB = $this;
		$parametros = [];
		if (isset($obj->dominio)) 
			$parametros = $DB->select("SELECT * FROM xfr_parametros WHERE dominio = '{$obj->dominio}' and activo = 1 
																ORDER by orden, nombre");
		if (isset($obj->id_padre)) 
			$parametros = $DB->select("SELECT * FROM xfr_parametros WHERE id_padre = {$obj->id_padre} and activo = 1 
																ORDER by orden, nombre");
		if (isset($obj->hijos_de) && isset($obj->dominio) && isset($obj->nombre)) {
			$padre = $this->getParametro((object)['dominio' => $obj->dominio, 'nombre' => $obj->nombre]);
			$parametros = $DB->select("SELECT * FROM xfr_parametros WHERE id_padre = {$padre->id} and activo = 1 
																ORDER BY orden , nombre");
		}
		
		return $parametros;
	}

	/**
	 * CLASE el objeto parametro 
	 * con id {id_parametro:id_parametro}, 
	 * o con dominio nombre {dominio:dominio, nombre:nombre} 
	 */
	protected function getParametro($obj) {
		$DB = $this;
		if(isset($obj->id))
			return collect($DB->select("SELECT * FROM xfr_parametros 
																	WHERE id = '{$obj->id_parametro}' "))->first();
		if(isset($obj->dominio) and isset($obj->nombre))
			return collect($DB->select("SELECT * FROM xfr_parametros 
																	WHERE dominio = '{$obj->dominio}' 
																	AND nombre = '{$obj->nombre}' "))->first();
	}

	/**
	 * CLASE obtiene el valor de un parametro obj = (object)[dominio=>dominio, nombre=>nombre]
	 */
	protected function valorParametro($obj) {
		$DB = $this;
		$param = collect($DB->select("SELECT * FROM xfr_parametros 
																	WHERE dominio = '{$obj->dominio}' AND nombre = '{$obj->nombre}' AND activo = 1 "))->first();
		return $param ? $param->valor : null;
	}




	/** *********************************    FIN FUNCIONES DB   ********************************************** */
	
	/** *********************************   FUNCIONES API GENERALES ***************************************** */
	/**
	 * POST : Obtiene losparametros de un dominio
	 * URL ROUTE: gral/v1/get-parametros-from
	 * request  {dominio:dominio} o {id_padre:id_padre}
	 */
	public function getParametrosFrom(WP_REST_Request $req) {
		$req = (object)$req->get_params();
		return [
			'data' => $this->parametrosFrom($req),
			'status' => 'ok'
		];
	}



	/**
	 * POST Recupera un valor especifico de un parametro,si no encuentra el valor devuelve null y error
	 * URL ROUTE: gral/v1/get-valorparametro
	 * request {dominio:dominio, nombre:nombre}
	 */
	public function getValorParametro(WP_REST_Request $req){
		$req = (object)$req->get_params();
		$dominio = $req->dominio;
		$nombre = $req->nombre;
		$valor = $this->valorParametro((object)[
																			'dominio' => $dominio,
																			'nombre' => $nombre
																		]);
		if(!$valor){
			return [
				'data' => null,
				'status' => 'error',
				'msg' => 'no existe el valor'
			];
		}
		
		return (object)[
			'data' => $valor->valor,
			'status' =>'ok'
		];
	}

	/** *******************************  FIN  FUNCIONES API GENERALES ***************************************** */


	
}
