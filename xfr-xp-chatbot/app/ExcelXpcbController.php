<?php
// require_once $xfrContenidos->core_path . 'classes/vendor/maatwebsite/src/facades/Excel';

// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/PhpOffice/PhpSpreadsheet/Spreadsheet.php';
// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/PhpOffice/PhpSpreadsheet/IOFactory.php';
// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/PhpOffice/PhpSpreadsheet/Shared/File.php';
// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/PhpOffice/PhpSpreadsheet/Reader/Xlsx.php';
// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/PhpOffice/PhpSpreadsheet/Reader/BaseReader.php';

// require_once $xfrContenidos->core_path . 'classes/libs/phpspreadsheet/src/PhpSpreadsheet/Xlsx.php';

// use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use frctl\MasterController;

// use FuncionesContenidosController as FuncionesContenidos;

class ExcelXpcbController extends MasterController {
	/**
	 * API GET
	 * 
	 */
	public function exportarExcel(WP_REST_Request $req) {
		$inputFileName = 'd:/archivo.xlsx';

		$spreadsheet = IOFactory::load($inputFileName);

		$worksheet = $spreadsheet->getActiveSheet();

		$data = [];

		foreach ($worksheet->getRowIterator() as $row) {
			$rowData = [];
			foreach ($row->getCellIterator() as $cell) {
				$rowData[] = $cell->getValue();
			}
			$data[] = $rowData;
		}

		return [
				'data' => $data
			];
		echo json_encode($data);


			// $titulo = $req['titulo'];
			// $coleccion = collect([
			//     ['x' => $titulo, 'y' => 2],
			//     ['x' => 4, 'y' => 2],
			//     ['x' => 7, 'y' => 2],
			//     ['x' => 8, 'y' => 2],
			// ]);

			// Excel::create('proyecto', function($excel){
			//     $excel->sheet('ProyPDES-SP', function ($hoja){
			//         $fila = 1;
			//         $codDemanda = 0;  
			//         $color = 0;       
			//         $cabecera =  ['COD_DEMANDA', 'NOMBRE_PROYECTO', 'SECTOR', 'PILAR', 'META', 'P', 'M', 'R', 'A', 'DESCRIPCION_ACCION', 'INDICADOR_PROCESO', 'ENTIDAD', 'N sisin'];
			//         $hoja->row( $fila, $cabecera );
			//         $hoja->row($fila, function($row) { 
			//             $row->setBackground('#22CC33'); 
			//             $row->setAlignment('center');
			//             $row->setFontWeight('bold');
			//         });
			//     });
			// } )->export('xlsx');

			// return Excel::download(new EjemploExport($coleccion), 'marferguer.xlsx');
			// return Excel::download(new EjemploExport, 'marferguer.xlsx');



			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Hello World !');

			// $writer = new Xlsx($spreadsheet);
			// $writer->save('hello world.xlsx');

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="ExportForm.xlsx"');
			header('Cache-Control: max-age=0');
			$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
			ob_end_clean(); 
			$writer->save("php://output");
			exit;
	}

	/**
	 * API POST
	 * Realiza el cargado de un archivo Excel 
	 * y la insercion o Borrado  a la BD en una tabla temporal STAGE
	 */
	public function cargarArchivo(Request $req) {
			// return response()->json([
			//     'time'                  => $this->now(),
			//     'concert'               =>  date_create_from_format('j/n/Y', '03/15/1999')->format('Y-m-d'),
			//     'string oso    '        => self::transformaTipo("string", 'oso    '),
			//     'varchar "" '           => self::transformaTipo("varchar", ''),
			//     'varchar "INDETERMINADO" '           => self::transformaTipo("varchar", 'INDETERMINADO'),
			//     'string null '          => self::transformaTipo("string", null),
			//     'int 3    '             => self::transformaTipo("int", '3     '),
			//     'integer null '         => self::transformaTipo("integer", null),
			//     'integer palabra '      => self::transformaTipo("integer", 'palabra'),
			//     'integer 3.14 '         => self::transformaTipo("integer", '3.14'),
			//     'int "INDETERMINADO" '  => self::transformaTipo("int", 'INDETERMINADO'),
			//     'date 3/06/99 '         => self::transformaTipo("date", '3/06/99     '),
			//     'date      3/06/1999 '  => self::transformaTipo("date", '    3/06/1999     '),
			//     'date      03/6/1999 ' => self::transformaTipo("date", '    03/06/1999     '),
			//     'date      03/16/1999 '  => self::transformaTipo("date", '    3/06/1999     '),
			//     'date   null '          => self::transformaTipo("date", null),
			//     'date   3 '          => self::transformaTipo("date", 3),
			//     'date   "" '          => self::transformaTipo("date", ""),
			// ]);

			set_time_limit(60 * 60 * 4);
			$timeIni = microtime(true);
			$req = (object)$req;
			$file               = $req->file('archivo');
			$numHoja            = intval($req->num_hoja) > 0 ? intval($req->num_hoja) : -1;
			$numFilaStart       = intval($req->num_fila_start) > 0 ? intval($req->num_fila_start) : -1;
			$numFilaEnd         = intval($req->num_fila_end) > 0 ? intval($req->num_fila_end) : -1;
			$nombreArchivo      = $file->getClientOriginalName();
			$extension          = $file->getClientOriginalExtension();        
			
			if (!in_array($extension, ['xls', 'xlsx', 'csv']))
					return response(['status' => 'error', 'msg' => 'El archivo no es un archivo de tipo Excel.',]);

			$existesNombreArchivo = \DB::select("SELECT * FROM st_felcv_consolidado WHERE nombre_archivo = '{$nombreArchivo}' ");
			if (count($existesNombreArchivo) > 0 && !isset($req->continuar_carga))
					return response(['status' => 'alert', 'msg' => 'El nombre del Archivo y sus datos ya fueron cargados.',]);

			$documento = IOFactory::load($file);
			$totalHojas = $documento->getSheetCount();      

			if($totalHojas < $numHoja || $numHoja <= 0)
					return response(['status' => 'error', 'msg' => 'El número de hoja no es válido para el archivo.',]);

			$hojaActual = $documento->getSheet($numHoja - 1);

			$letraMayorColumna  = $hojaActual->getHighestColumn(); //valor en Letra A B C
			$numColumnaMayor    = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($letraMayorColumna);
			$numFilaEnd         = $numFilaEnd < 0 ? $hojaActual->getHighestRow() : $numFilaEnd; 

			$numFilaEncabezados     = $numFilaStart - 1;
			$allColumnsExcel        = [];
			
			for($colCabecera = 1; $colCabecera <= $numColumnaMayor; $colCabecera++) {
					$celdaCabecera      = $hojaActual->getCellByColumnAndRow($colCabecera, $numFilaEncabezados)->getValue();
					$allColumnsExcel[]  = (object)['columna_titulo' => strtolower(trim($celdaCabecera)), 'columna_coord' => $colCabecera];
			}
			
			// $allColumnsExcel = collect($allColumnsExcel);
			$columnsBd = self::columnasBdRelacionConExcel();
			$colsBdConCoordExcel    = [];
			
			/** Mapea para conseguir la coordenada excel de la columna COlumna A es coord 1*/
			foreach ($columnsBd as $colBd){
					$colBd = (object)$colBd;
					$columnLike_ocurrencia = explode('@', $colBd->columna_like); /** quedara ['edad', 2] es edad 2da instancia o [gestion] si no tiene @*/

					/** SI no tenia @ entonces  count(array) es 1 , se asume entonces que deberia ser la primera instancia. Se agrega la ocurrencia 1*/
					if (count($columnLike_ocurrencia) == 1) 
							$columnLike_ocurrencia[1] = 1; 
					
					$colsExcelParecidos = [];
					foreach($allColumnsExcel as $columnaExcel){
							if(str_contains($columnaExcel->columna_titulo,  $columnLike_ocurrencia[0])){
									$colsExcelParecidos[] = $columnaExcel;
									if($columnLike_ocurrencia[1] == 1) break;
							}
					}

					if (count($colsExcelParecidos) > 0) {
								$colBd->col_coord     = $colsExcelParecidos[$columnLike_ocurrencia[1] - 1]->columna_coord; /** el indice 1 es la ocurencia si es primera o segunda, se resta 1 para coincidir con los indices del array parecidos */
								$colBd->col_titulo    = $colsExcelParecidos[$columnLike_ocurrencia[1] - 1]->columna_titulo;
								$colsBdConCoordExcel[] = $colBd;                
					}                                
			}

			$arrayRegistros = [];
			$errores = [];

			## Setea la tabla de Stage y luego Recorre el archivo e Introduce cada fila a la tabla temporal de stage
			// \DB::statement("TRUNCATE st_felcv_consolidado_stage");
			// \DB::statement("ALTER SEQUENCE st_felcv_consolidado_stage_id_seq RESTART WITH 1");
			for ($indexRow = $numFilaStart; $indexRow <= $numFilaEnd; $indexRow++) {

					try {
							$newRow = [];
							# se itera los campos de la tabla en BD
							foreach ($colsBdConCoordExcel as $colBd) {
									$colBd = (object)$colBd;
									$cell = $hojaActual->getCellByColumnAndRow($colBd->col_coord, $indexRow);
									$celdaValor = ($colBd->tipo == 'date') ? $cell->getFormattedValue() : $cell->getValue();

									$newValor = (object)self::transformaTipo($colBd->tipo, $celdaValor);
									# Si Hay error, lo guarda en el Array
									if($newValor->error)
											$errores[] = "Fila: {$indexRow}; En Columna: {$colBd->col_titulo}; El valor: {$celdaValor}, {$newValor->error}";

									$newRow[$colBd->campo] = $newValor->value;
							}
							# Datos propios o modificados
							$newRow['nombre_archivo'] = $nombreArchivo;
							$newRow['fecha_de_cargado'] = $this->now();
							$geo = explode(',' , $newRow['latitud'] );

							if (count($geo) == 2 && is_numeric(trim($geo[0])) && is_numeric(trim($geo[1]))){
									$newRow['latitud']  = trim($geo[0]);
									$newRow['longitud'] = trim($geo[1]);
							}
							else
									$errores[] = "Fila: " . $indexRow . "= No contiene Latitud y Longitud Validos.";

							## INSERT    
							\DB::table('st_felcv_consolidado_stage')->insert(($newRow));
							$arrayRegistros[] = $newRow;
					} 
					catch (\Exception $e) {
							return response([
									'status' => 'error',
									'msg'    => 'Error , algo inesperado ha sucedido.' . $e->getMessage(),
							]);
					}
					
			}
			
			$timeFin = microtime(true);
			$timeExecutionSeg = round($timeFin - $timeIni);
			
			return response()->json([
					'status' => 'ok',    
					'msg'    => "Se cargaron temporalmente los registros del archivo: {$nombreArchivo} ",        
					'data'   => [
							'num_registros_insertados'  => count($arrayRegistros),
							'num_errores'               => count($errores),
							'tiempo_ejecucion_seg'      => $timeExecutionSeg,
							'regs'                      => $arrayRegistros,
							'errores'                    => $errores,
					],            
			]);
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
