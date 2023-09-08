<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use frctl\MasterController;

class DatadashController extends MasterController {

	/**
	 * API GET
	 * Obtiene los campos de la tabla o vista disponibles como variables
	 */
	public function obtenerCampos() {
		// error_reporting(E_ERROR | E_PARSE);
		$DB = $this;
		$tabla_vista_name = 'xfr_consolidado_feminicidios';
		# obtiene los campos de la tabla o vista
		$tablas = $DB->select("SELECT DISTINCT column_name as nombre_columna  FROM information_schema.columns WHERE 
                                    -- table_schema = 'public'  AND 
                                    table_name = '{$tabla_vista_name}' ");

		# Campos que se van a excluir
		$excluirCampos = ['id']; //, 'nombre_archivo', 'fecha_de_cargado', 'hora_del_hecho', 'zona', /*'latitud', 'longitud',*/ 'denunciante', 'victima', 'persona_con_discapacidad',  'agresor'];
		$data = collect($tablas)->filter(function ($item, $k) use ($excluirCampos) {
			if (!in_array($item->nombre_columna, $excluirCampos))
				return $item;
		});

		return [
			'data' => $data,
			'status' => 'ok'
		];
	}

	/**
	 * API POST
	 * Obtiene el dataset a partir de ciertos parametors enviados
	 */
	public function getDataSet(WP_REST_Request $request) {
		$DB = $this;
		$req = (object)$request;

		// $id_dash_config = $req->id_dash_config;
		// $configs = collect(\DB::select("SELECT  * FROM dash_config WHERE  id = {$id_dash_config} "));

		// if($configs->count() <= 0)
		//     return response()->json(['mensaje'=> "No existe una configuracion con  id_dash_config aosciado actualmente al menu "]);

		// $config = $configs->first();
		// $obj =  json_decode($config->configuracion);
		$campos_dataset = $request['campos_dataset'];

		$cnfDataset  = (object)[];
		$cnfDataset->tabla            = 'xfr_consolidado_feminicidios';
		// $cnfDataset->tabla            = 'xfr_consolidado_victimas';
		$cnfDataset->campo_agregacion = '*'; //'id';
		$cnfDataset->condicion_sql    = '';
		$cnfDataset->columnas = collect($campos_dataset)->map(function ($item) {
			return $item;
			// return '"' . $item . '"';
		});
		// $cnfDataset->columnas         = ['gestion', 'mes_registro', 'fecha_del_hecho',   'departamento', 'municipio',
		//  'delitos', 'tipos_de_violencia as "tipo de violencia"', 'agresion', 'donde_se_denuncio', 'sexo_victima', 'edad_victima', 'relacion_con_el_agresor'];
		//  'sexo_agresor', 'temperancia_del_agresor', 'nacionalidad_del_agresor', 'causas_de_la_agresion', 'instrumento_utilizado' ];
		$cnfDataset->columnas_select    = $cnfDataset->columnas->implode(', ');
		$cnfDataset->columnas_groupby   = collect($cnfDataset->columnas)->map(function ($item, $key) {
			return stripos($item, ' as ') ?  substr($item, 0, stripos($item, ' as ')) : $item;
		})->implode(', ');


		// // Obtiene los campos con sus alias
		// $campos_disponibles_select = implode(', ', $obj->campos_disponibles);
		// // Para el group by se le quitan los alias
		// $campos_originales_groupby = collect($obj->campos_disponibles)
		//                         ->map(function($item, $key){
		//                             return stripos($item, ' as ') ?  substr($item, 0, stripos($item, ' as ')) : $item;
		//                         })->implode(', ');

		$qrySelect = $qryCondicion = $qryGroupBy = '';

		$tabla = collect($DB->select("SELECT table_name FROM information_schema.tables 
                                WHERE 
                                -- table_schema='public' AND 
                                table_name like '%{$cnfDataset->tabla}%' "))->first();
		if (!$tabla)
			return
				[
					'status' => 'error',
					'mensaje' => "No existe ninguna tabla o vista que coincida con {$cnfDataset->tabla}"
				];

		$tablaNombre = $tabla->table_name;

		$qrySelect = "SELECT {$cnfDataset->columnas_select}, COUNT( {$cnfDataset->campo_agregacion} ) AS cantidad
                    FROM {$tablaNombre} 
                    WHERE 1 = 1 ";

		$qryCondicion = trim($cnfDataset->condicion_sql) == '' ? '' : " AND {$cnfDataset->condicion_sql} ";

		$qryGroupBy = " GROUP BY {$cnfDataset->columnas_groupby} ";
		// ORDER BY t_ano, {$campos_disponibles} " ;

		$query = $qrySelect . $qryCondicion . $qryGroupBy;


		try {
			$collection  =   collect($DB->select($query));
		} catch (\Exception $e) {
			$collection = array();
		}


		return [
			'mensaje'   => 'ok',
			'collection' => $collection,
			// 'unidad_medida' => $unidadesMedida,
			// 'indicador' => $indicador,
			// 'metas'     => $metasPeriodo,
			'query'     => $query,
			'configuracionObj' => $cnfDataset
		];
	}


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
			$DB->statement("DELETE FROM xfr_consolidado_feminicidios");
			$DB->statement("ALTER TABLE xfr_consolidado_feminicidios AUTO_INCREMENT = 1 ");

			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			$list = [];
			for ($i=1; $i < Count($data); $i++) { 
				$rowArray = $data[$i];
				if (!empty(trim($rowArray[0]))) {
					$rowData = (object)[
						'gestion'        => trim($rowArray[0]),
						'delito'        => trim($rowArray[1]),
						'sexo_victima'        => trim($rowArray[3]),
						'edad_victima'        => trim($rowArray[4]),
						'relacion_victima_agresor'  => trim($rowArray[5]),
						'numero_hijos' 	=> trim($rowArray[6]),
						'causa_muerte' 	=> trim($rowArray[7]),
						'departamento' 	=> trim($rowArray[8]),
						'municipio' 	=> trim($rowArray[9]),
						'estado_causa' 	=> trim($rowArray[18]),
					];
					$this->guardarObjetoTabla($rowData, 'xfr_consolidado_feminicidios');
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


	/** API REST GET PRUEBA */
	public function test() {

		return [
			'texto'           =>  'texto',
			'textoSinSC'      =>  'ok',
		];
	}
}
