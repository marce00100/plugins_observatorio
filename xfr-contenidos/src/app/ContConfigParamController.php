<?php
use frctl\MasterController;

class ConConfigParamController extends ContController { //MasterController {
	

	/** 
	 * POST CONTENIDOS TIPO_CONTENIDO de la Bilbioteca Juridica
	 * {tipo_contenido:tipo_contenido}
	 */
	public function getConfigs(WP_REST_Request $req){
		$tiempoInicio = microtime(true);
		/* Quitar si no es WP */
		$DB = $this;

		$configs = $DB->select(" SELECT  * from xfr_parametros where LENGTH(config) > 10 and dominio not in ('tipo_contenido', 'biblioteca_juridica') ");
		return [
			'data' => $configs
		];
		


	}

	public function guardaConfig(WP_REST_Request $req){
		$req = (object)$req->get_params();
		
	}







}
