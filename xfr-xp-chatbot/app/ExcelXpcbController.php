<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use frctl\MasterController;

// use FuncionesContenidosController as FuncionesContenidos;

class ExcelXpcbController extends MasterController {

	/**
	 * API POST
	 * Realiza el cargado de un archivo Excel 
	 * y la insercion o Borrado  a la BD en una tabla temporal STAGE
	 */
	public function cargarArchivo(WP_REST_Request $req) {
		$timeIni = microtime(true);

		set_time_limit(60 * 60 * 4);
		$timeIni = microtime(true);
		$req = (object)$req;

		$file = $_FILES['archivo']['tmp_name'];
			
			$spreadsheet = IOFactory::load($file);

			$DB = $this;
			$DB->statement("DELETE FROM xfr_textos");
			$DB->statement("ALTER TABLE xfr_textos AUTO_INCREMENT = 1 ");

			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			$list = [];
			for ($i=1; $i < Count($data); $i++) { 
				$rowArray = $data[$i];
				if (!empty(trim($rowArray[0]))) {
					$rowData = (object)[
						'nivel0'        => trim($rowArray[0]),
						'nivel1'        => trim($rowArray[1]),
						'nivel2'        => trim($rowArray[2]),
						'nivel3'        => trim($rowArray[3]),
						'nivel1_tags' 	=> trim($rowArray[4]),
						'nivel2_tags' 	=> trim($rowArray[5]),
						'nivel3_tags' 	=> trim($rowArray[6]),
					];
					$this->guardarObjetoTabla($rowData, 'xfr_textos');
					$list[] = $rowData;
				}
			}

			$totalRegistros = Count($list);
			return [
				'status' => 'ok',
				'msg' => "Se sealizó el registro de {$totalRegistros} filas",
				'data'=> $list,
				'registros' => count($list),
				'time'	 => microtime(true) - $timeIni,
			];
	
	
	}

	/**
	 * API POST
	 * Realiza el insert dentro de consolidado desde el stage, 
	 * elimina los que tienen el mismo nombre de archivo y luego inserta
	 */
	public function guardarDatos(Request $req){
			$timeIni = microtime(true);
			$nombreArchivo = $req['nombre_archivo'];
			# para conteo de existentes en consolidado
			$filasExistentes = \DB::select("SELECT * FROM st_felcv_consolidado WHERE nombre_archivo = '{$nombreArchivo}'");
			# Se borran los existente en consolidado
			\DB::delete("DELETE FROM st_felcv_consolidado WHERE nombre_archivo = '{$nombreArchivo}'");
			# Se obtienen los nuevos del stage del archivo
			$stageData = \DB::select("SELECT  * FROM st_felcv_consolidado_stage WHERE nombre_archivo = '{$nombreArchivo}'");
			try {
					foreach($stageData as $row){
							$row = collect($row)->except(['id']);
							\DB::table("st_felcv_consolidado")->insert($row->toArray());
					}
					# Se eliminan de stage
					\DB::delete("DELETE FROM st_felcv_consolidado_stage WHERE nombre_archivo = '{$nombreArchivo}'");
			} 
			catch (\Exception $e) {
					return response([
							'status' => 'error',
							'msg'    => 'Error , algo inesperado ha sucedido.' . $e->getMessage(),
					]);
			}
			$timeFin = microtime(true);
			$timeExecutionSeg = round($timeFin - $timeIni);
			return response([
					'status' => 'ok',
					'msg'    => 'Se guardó la informacion correctamente del archivo {$nombreArchivo}',
					'data'   => [
							'filas_nuevas'      => count($stageData),
							'filas_antiguas'    => count($filasExistentes),
							'tiempo_ejecucion_seg'   => $timeExecutionSeg,
					]
			]);
	}

	/**
	 * POST
	 * Elimina los registros de la tabla consolidado que tienen el nombre del archivo  
	 */
	public function eliminaDatosArchivo(Request $req){
			$nombreArchivo = $req['nombre_archivo'];
			\DB::delete("DELETE FROM st_felcv_consolidado WHERE nombre_archivo = '{$nombreArchivo}'");
			return response([
					'status' => 'ok',
					'msg' => 'Se borraron los registros del archivo: $nombreArchivo'
			]);
	}

	/** 
	 * LOCAL
	 * Tranforma los datos a un tipo dado, si no se puede devuelve el objeto con error y el valor en string,
	 */
	public static function transformaTipo($tipo, $value){
			$value = (isset($value) && trim($value) != '') ? trim($value) : '';
			$retorno = [];

			if ($tipo == 'int' || $tipo == 'integer') {
					# Si es Null significa celda vacia, se queda null 
					if($value == null)
							return ['value' => null, 'error' => false];
					
					# Se verifica si contiene cualquiera de las palabras INDETERMINADO, A DETERMINAR, POR DETERMINAR, NO DETERMINADO etc
					if(str_contains( strtoupper($value) , 'DETERMI') ) 
							return ['value' => 'INDETERMINADO', 'error' => false];

					# SI se puede convertir a enter entonces esta bien
					if (filter_var($value, FILTER_VALIDATE_INT)) {
							return ['value' => (int)$value, 'error' => false];
					} else {
							return ['value' => null, 'error' => "El valor no es un Entero. O Coloque INDETERMINADO"];
					}
			}

			if ($tipo == 'date') {
					$e = 0;
					$newValue = '';
					try {
							$newValue = date_format(date_create_from_format('j/n/Y', $value), "Y-m-d");
					} catch (\Throwable $th) {
							$e++;
					}
					if ($e == 1) {
							try {
									$newValue = date_format(date_create_from_format('j/n/y', $value), "Y-m-d");
							} catch (\Throwable $th) {
									$e++;
							}
					}

					return [
							'value' => $e < 2 ? $newValue : null, 
							'error' => $e < 2 ? false : "No es un Fecha Valida"
					];
			}

			if ($tipo == 'string' || $tipo == 'varchar' || $tipo == '') {
					return [
							'error' => false,
							'value' => $value
					];
			}

	}

	/**
	 * LOCAL
	 * Tiene la configuracion de los campos de la base de datos en orden y con su respectivo tipo y la columna (letra) equivalente en archivo excel
	 */
	public static function columnasBdRelacionConExcel(){
			return collect([
					// [ "campo" => "id",                                            "col_letra" =>  "" , "tipo" => "",        "columna_like" => "" ],
					// [ "campo" => "nombre_archivo",                                "col_letra" =>  "" , "tipo" => "",        "columna_like" => "" ],
					// [ "campo" => "fecha_de_cargado",                              "col_letra" =>  "" , "tipo" => "",        "columna_like" => "" ],
						 [ "campo" => "gestion",                                       "col_letra" =>  "C" , "tipo" => "int",    "columna_like" => "gestion" ],
						 [ "campo" => "mes_registro",                                  "col_letra" =>  "D" , "tipo" => "",       "columna_like" => "mes registro" ],
						 [ "campo" => "fecha_de_la_denuncia",                          "col_letra" =>  "E" , "tipo" => "date",   "columna_like" => "fecha de la denuncia" ],
						 [ "campo" => "fecha_del_hecho",                               "col_letra" =>  "F" , "tipo" => "date",   "columna_like" => "fecha del hecho" ],
					// [ "campo" => "hora_del_hecho",                                "col_letra" =>  "G" , "tipo" => "",       "columna_like" => "" ],
						 [ "campo" => "departamento",                                  "col_letra" =>  "H" , "tipo" => "",       "columna_like" => "departamento" ],
						 [ "campo" => "provincia",                                     "col_letra" =>  "I" , "tipo" => "",       "columna_like" => "provincia" ],
						 [ "campo" => "municipio",                                     "col_letra" =>  "J" , "tipo" => "",       "columna_like" => "municipio" ],
					// [ "campo" => "zona",                                          "col_letra" =>  "L" , "tipo" => "",       "columna_like" => "" ],
						 [ "campo" => "latitud",                                       "col_letra" =>  "O" , "tipo" => "",       "columna_like" => "latitud" ],
						 [ "campo" => "longitud",                                      "col_letra" =>  "O" , "tipo" => "",       "columna_like" => "latitud" ],
						 [ "campo" => "delitos",                                       "col_letra" =>  "P" , "tipo" => "",       "columna_like" => "delitos" ],
						 [ "campo" => "tipos_de_violencia",                            "col_letra" =>  "Q" , "tipo" => "",       "columna_like" => "tipos de violencia" ],
						 [ "campo" => "agresion",                                      "col_letra" =>  "R" , "tipo" => "",       "columna_like" => "agresion" ],
						 [ "campo" => "donde_se_denuncio",                             "col_letra" =>  "S" , "tipo" => "",       "columna_like" => "donde se denun" ],
					// [ "campo" => "denunciante",                                   "col_letra" =>  "X" , "tipo" => "",       "columna_like" => "" ],
					// [ "campo" => "victima",                                       "col_letra" =>  "Y" , "tipo" => "",       "columna_like" => "" ],
						 [ "campo" => "sexo_victima",                                  "col_letra" =>  "" , "tipo" => "",        "columna_like" => "sexo@1" ], 
						 [ "campo" => "genero_victima",                                "col_letra" =>  "" , "tipo" => "",        "columna_like" => "genero@1" ],
						 [ "campo" => "edad_victima",                                  "col_letra" =>  "" , "tipo" => "",        "columna_like" => "edad@1" ],
						 [ "campo" => "ocupacion_victima",                             "col_letra" =>  "" , "tipo" => "",        "columna_like" => "ocupacion@1" ],
						 [ "campo" => "temperancia_victima",                           "col_letra" =>  "" , "tipo" => "",        "columna_like" => "temperancia@1" ],
						 [ "campo" => "relacion_con_el_agresor",                       "col_letra" =>  "" , "tipo" => "",        "columna_like" => "relacion con el agresor" ],
					// [ "campo" => "persona_con_discapacidad",                      "col_letra" =>  "" , "tipo" => "",        "columna_like" => "" ],
					// [ "campo" => "agresor",                                       "col_letra" =>  "" , "tipo" => "",        "columna_like" => "" ],
						 [ "campo" => "edad_aprox_del_agresor",                        "col_letra" =>  "" , "tipo" => "",        "columna_like" => "edad@2" ],
						 [ "campo" => "sexo_del_agresor",                              "col_letra" =>  "" , "tipo" => "",        "columna_like" => "sexo@2" ],
						 [ "campo" => "genero_del_agresor",                            "col_letra" =>  "" , "tipo" => "",        "columna_like" => "genero@2" ],
						 [ "campo" => "ocupacion_del_agresor",                         "col_letra" =>  "" , "tipo" => "",        "columna_like" => "ocupacion@2" ],
						 [ "campo" => "temperancia_del_agresor",                       "col_letra" =>  "" , "tipo" => "",        "columna_like" => "temperancia@2" ],
						 [ "campo" => "nacionalidad_del_agresor",                      "col_letra" =>  "" , "tipo" => "",        "columna_like" => "nacionalidad@2" ],
						 [ "campo" => "relacion_con_la_victima",                       "col_letra" =>  "" , "tipo" => "",        "columna_like" => "relacion con la vict" ],
						 [ "campo" => "causas_de_la_agresion",                         "col_letra" =>  "" , "tipo" => "",        "columna_like" => "causas" ],
						 [ "campo" => "instrumento_utilizado",                         "col_letra" =>  "" , "tipo" => "",        "columna_like" => "instrumento" ],
						 [ "campo" => "aprehendido",                                   "col_letra" =>  "" , "tipo" => "",        "columna_like" => "aprehendido" ],
						 [ "campo" => "arrestado",                                     "col_letra" =>  "" , "tipo" => "",        "columna_like" => "arrestado" ],
						 [ "campo" => "situacion_procesal_del_agresor",                "col_letra" =>  "" , "tipo" => "",        "columna_like" => "procesal" ],
						 [ "campo" => "etapa_del_proceso",                             "col_letra" =>  "" , "tipo" => "",        "columna_like" => "etapa del proceso" ],
						 [ "campo" => "arma_utilizada",                                "col_letra" =>  "" , "tipo" => "",        "columna_like" => "arma utilizada" ],
						 [ "campo" => "denuncia_previa",                               "col_letra" =>  "" , "tipo" => "",        "columna_like" => "denuncia previa" ],
						 [ "campo" => "si_existe_cambio_de_tipo_penal_a_otro_delito",  "col_letra" =>  "" , "tipo" => "",        "columna_like" => "cambio de tipo penal" ],
						 [ "campo" => "medidas_de_proteccion_otorgadas_para_infantes", "col_letra" =>  "" , "tipo" => "",        "columna_like" => "otorgadas para infantes" ],
						 [ "campo" => "medidas_de_proteccion_otorgadas_para_mujeres",  "col_letra" =>  "" , "tipo" => "",        "columna_like" => "otorgadas para mujeres" ],
						 [ "campo" => "camara_gessel",                                 "col_letra" =>  "" , "tipo" => "",        "columna_like" => "gessel" ],
			]);
	}




	

	

}
