<?php
require_once $xfrContenidos->path  . "src/libs-php/lib-normaliza.php";
use frctl\MasterController;
use FuncionesContenidosController as FuncionesContenidos;


class ContMigrateController extends MasterController {
  /* Propiedad que es la instancia de Funciones Contenidos */
  public $funcionesContenidos;
  private $modulosVista;
  private $modulos;

  public function __construct() {
    $this->funcionesContenidos = new FuncionesContenidos();

    $this->modulosVista = [
      'magistratura' => [
        ['Todos los contenidos (noticias y comunicados)' => 'noticias']
      ],
      'observatorio' => [
        ['Todos los Contenidos (noticias y Activs)'       => 'contenidos'],
        ['Noticias'                   => 'noticias'],
        ['Actividades'                => 'actividades'],
        ['Toda la Biblioteca juridica'=> 'biblioteca_juridica'], 
        ['Normas'                     => 'normas'], 
        ['Jurisprudencia'             => 'jurisprudencia'],
        ['Recomendaciones'            => 'recomendaciones'],
        ['Jurisprudencias Relevantes' => 'jurisprudencia_relevante']
      ]

    ];
  } 

  public function obtenerModulosMigrar() {
    $site = $this->valorParametro((object)['dominio' => 'site', 'nombre' => 'site']);
    return [
      'sistema'      => $site,
      'migraciones'  => $this->modulosVista[$site],
    ];

  }

  /**
   * POST para migrar las tablas del Observatorio 
   */
  public  function migrarTablas(WP_REST_Request $req) {
    $tiempoInicio = microtime(true);
    set_time_limit(60 * 60 * 6);
    /* Quitar si no es WP */
		$req            = (object)$req->get_params();
    $sistema        = $req->sistema;
    $tipo_contenido = $req->tipo_contenido;
    // $tipo_migracion = $req->tipo_migracion ?? '';

    if (!$req->sistema || !$req->tipo_contenido) {
      return (object)['status' => 'error', 'msg' => 'Error, falta parametros'];
    }

    /** Modulos de  Biblioteca Juridica  o Contenidos */
    if ($sistema == 'observatorio' && ($tipo_contenido == 'biblioteca_juridica' || $tipo_contenido == 'contenidos')) {
      $modulos = $tipo_contenido == 'biblioteca_juridica' ? 
        ['normas', 'jurisprudencia', 'recomendaciones', 'jurisprudencia_relevante'] :
        ['noticias', 'actividades'];
      $errores = 0;
      foreach ($modulos as $key => $value) {
        $resp = $this->migra($sistema, $value);
        $errores = $errores + ($resp->status == 'error') ? 1 : 0;
      }

      return [
        'status' => $errores > 0 ? 'error' : 'ok',
        'msg'    => $errores > 0 ? 'Ocurrio un error': 'Se realizo la migracion',
        'time'   => microtime(true) - $tiempoInicio
      ];
    } 
    else {
      $resp = $this->migra($sistema, $tipo_contenido);
    }

    return [
      'status'  => $resp->status,
      'msg'     => $resp->msg,
      'time'    => microtime(true) - $tiempoInicio
    ];
  }

  private function migra($sistema, $tipo_contenido){
    $DB = $this;

    /**
     * ------------------------------------------------------------------------
     * BD: MAGISTRATURA ; MODULO: NOTICIAS
     * ------------------------------------------------------------------------
     */
    if ($sistema == 'magistratura' && $tipo_contenido == 'noticias') {      
      $tabla_target = 'xfr_contenidos';
      /* se eliminann noticias y comunicados */
      $DB->statement(
        "DELETE FROM {$tabla_target} WHERE tipo_contenido like '%noticias%' or tipo_contenido like '%comunicados%' ");

      $DB->statement(
        /* Realiza el inser de loscampos principales */
        "INSERT INTO {$tabla_target} (tipo_contenido, fecha_publicacion, titulo, contenido, estado_contenido, numero_vistas, created_at ) 
        SELECT g.title as categoria, c.publish_up as fecha_publicacion, c.title as titulo, c.introtext as contenido, 1, c.hits, c.created
        FROM xat65_content c, xat65_categories g
        WHERE c.catid= g.id and g.title like '%noti%' and c.state = 1
        order by publish_up asc " );

      /* Quita una publicidad existente */
      $DB->statement("UPDATE {$tabla_target} set contenido = replace(contenido, '</p><span style=\"position: absolute;left: -43123px;\">Jackpot City casino <a href = \"https://slots-online-canada.ca/review/jackpot-city-casino/\">jackpot-city-casino</a> is one of the best online casinos out there.</span>', '') ");

      $list = collect($DB->select("SELECT * FROM {$tabla_target}"));
      foreach ($list as $item) {
        /* Quita los htmls y caracteres especiales convitirndo a UTF8 */
        $item->texto     = html_entity_decode($this->funcionesContenidos->quitarHtmlTags($item->contenido), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $item->tipo_contenido = strtolower(substr($item->titulo, 0, 8)) == 'comunica' ? 'comunicados' : 'noticias';
        $item->updated_at     = $this->now();

        try {
          $this->guardarObjetoTabla($item, $tabla_target);
        } catch (Exception $e) {
          return (object)['status' => 'error', 'msg' => 'Error' . $e->getMessage()];
        }
      }
      return (object)[
        'status'              => 'ok',
        'msg'                 => 'Se guardaron ' . count($list),
        'registros_afectados' =>  count($list)
      ];      
    }

    /**
     * ------------------------------------------------------------------------
     * BD: OBSERVATORIO ; MODULO: NOTICIAS o ACTIVIDADES
     * ------------------------------------------------------------------------
     */
    if ($sistema == 'observatorio' && ($tipo_contenido == 'noticias' || $tipo_contenido == 'actividades')) {

      $tabla_target = 'xfr_contenidos';
      $tablaFrom = $tipo_contenido;

      $DB->statement("DELETE FROM {$tabla_target} WHERE tipo_contenido like '%{$tipo_contenido}%'");
      $queryJoin = ($tipo_contenido == 'noticias') ? 
                      " i.codigo = t.cod_noticia AND i.modulo LIKE '%noticia%' ":
                      " i.codigo = t.cod_actividad AND i.modulo LIKE '%actividad%' ";

      $list = collect($DB->select(
        "SELECT t.*, i.nombre as imagen  
            FROM {$tablaFrom} t
            LEFT JOIN imagenes i on {$queryJoin} AND i.categoria LIKE '%grand%' /* segun la fuente de datos la categoria grande no tiene prefijos ni sufijos, asi que al cargar esta se puede agregar sufijo _s de small para las imagenes pequeñas */
            ORDER by t.fecha" ));

      foreach ($list as $item) {
        $cont                    = (object)[];
        $tipo_contenido_modif =  ($tipo_contenido == 'noticias') ? 
                                strtolower($tipo_contenido) . "_" . strtolower($item->categoria . 'es'):
                                strtolower($tipo_contenido);
        $cont->tipo_contenido    = $tipo_contenido_modif;
        $cont->fecha_publicacion = $item->fecha;
        $cont->titulo            = $item->titulo;
        $cont->resumen           = $item->resumen;

        /** con html_entity_decode se transforman los caracteres especiales como \u00e1 por á */
        $cont->contenido         = ($tipo_contenido == 'noticias') ? 
                                      html_entity_decode($item->noticia, ENT_QUOTES | ENT_HTML5, 'UTF-8') : 
                                      html_entity_decode($item->actividad, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $cont->texto             = html_entity_decode($this->funcionesContenidos->quitarHtmlTags($cont->contenido), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cont->imagen            = $item->imagen;
        $cont->orden             = 1;
        $cont->estado_contenido  = 1;

        /** Al codificar a formato json con la opcon JSON_ENESCAPED_UNICODE se escapan los caracteres especiales y no son transformados al tipo \u00e1 que es á */
        $cont->campos_extra      = ($tipo_contenido == 'noticias') ? 
                                    json_encode(([
                                      'pagina' => $item->pagina,
                                      'fuente' => $item->fuente
                                    ]), JSON_UNESCAPED_UNICODE) : 
                                    json_encode(([
                                      'hora_inicio' => $item->hora_inicio,
                                      'hora_fin'    => $item->hora_final
                                    ]), JSON_UNESCAPED_UNICODE);
        $cont->created_at        = $item->fecha;
        $cont->updated_at        = $this->now();
        $cont->numero_vistas     = random_int(50, 400);

        try {
          $this->guardarObjetoTabla($cont, $tabla_target);
        } catch (Exception $e) {
          return (object)['status' => 'error', 'msg' => 'Error' . $e->getMessage()];
        }
      }

      $completeList = $DB->select("SELECT * FROM xfr_contenidos ");
      foreach ($completeList as $val) {
        $val = (object)$val;
        $val->otrosfields = json_decode($val->campos_extra);
      }
      return (object)[
        'status' => 'ok',
        'msg' => 'Se guardaron ' . count($list),
        'list' => $completeList
      ];
    }

    /**
     * ------------------------------------------------------------------------
     * BD: OBSERVATORIO ; MODULO: NORMATIVAS, JURISPRUDENCIAS, RECOMENDACIONES , JURISPRUDENCIA_RELEVANTE
     * ------------------------------------------------------------------------
     */
    if ($sistema == 'observatorio' && 
      ($tipo_contenido == 'normas' || $tipo_contenido == 'jurisprudencia' 
      || $tipo_contenido == 'jurisprudencia_relevante'  || $tipo_contenido == 'recomendaciones')) {
        
      $param = collect($DB->select("SELECT * from xfr_parametros where dominio like 'biblioteca_juridica' and nombre like '{$tipo_contenido}' "))->first();
      $config = (object)json_decode($param->temp);

      // if ($config->migrate && $config->migrate == 1) {
      //   return (object)[
      //     'status' => 'ok', 'msg' => 'Esta migracion ya se ha realizado, Si desea Volver a realizar la migracion cambiar el valor de la configuracion de normas a migrate = 0'
      //   ];
      // }

      $paramCategoria = $config->categoria;
      $DB->statement("DROP TABLE IF EXISTS {$config->tabla} ");
      $DB->statement("CREATE TABLE {$config->tabla} as SELECT * FROM {$config->old_table}");

      ;
      // $DB->statement(" ALTER TABLE {$config->old_table} RENAME TO {$config->tabla} ");

      $DB->statement(" ALTER TABLE {$config->tabla} ADD COLUMN IF NOT EXISTS orden int "); /* este campo ya esta presente en Normativa, Jurisprudencia, recomendaciones*/
      $DB->statement(" ALTER TABLE {$config->tabla} ADD COLUMN IF NOT EXISTS estado tinyint(1) ");
      $DB->statement(" ALTER TABLE {$config->tabla} ADD COLUMN IF NOT EXISTS imagen text ");
      $DB->statement(" ALTER TABLE {$config->tabla} ADD COLUMN IF NOT EXISTS archivos text ");

      $DB->statement(" UPDATE {$config->tabla} t set t.estado = 1");
      
      if($tipo_contenido =='jurisprudencia_relevante'){
        $DB->statement(" UPDATE {$config->tabla} t set t.orden = t.cod_sentencia");
      }



      $list = collect($DB->select("SELECT * FROM {$config->tabla}"));

      /* para el join con la tabla  archivos */
      $nombre_cod_biblioteca = '';
      $nombre_modulo = '';
      if($tipo_contenido == 'normas') { $nombre_cod_biblioteca = 'cod_normativa'; $nombre_modulo = 'normativa'; } 
      if($tipo_contenido == 'jurisprudencia') { $nombre_cod_biblioteca = 'cod_jurisprudencia'; $nombre_modulo = 'jurisprudencia'; } 
      if($tipo_contenido == 'recomendaciones') { $nombre_cod_biblioteca = 'cod_recomendacion'; $nombre_modulo = 'recomendacion'; } 
      if($tipo_contenido == 'jurisprudencia_relevante') { $nombre_cod_biblioteca = 'cod_sentencia'; $nombre_modulo = 'sentenciar'; } 

       /* temas y suubtemas  */
      if($tipo_contenido == 'normas' || $tipo_contenido == 'jurisprudencia' || $tipo_contenido== 'recomendaciones' ){
        $DB->statement(" UPDATE {$config->tabla} t set t.cod_tema     = ( select p.id from xfr_parametros p where p.dominio = 'temas' and p.temp = t.cod_tema )");
        $DB->statement(" UPDATE {$config->tabla} t set t.cod_subtema  = ( select p.id from xfr_parametros p where p.dominio = 'subtemas' and p.temp = t.cod_subtema )");
      }
      
      /* categorias  */
      if($tipo_contenido == 'normas' || $tipo_contenido == 'jurisprudencia'){
        $DB->statement(" UPDATE {$config->tabla} t set t.categoria    = ( select p.id from xfr_parametros p where p.dominio = '{$paramCategoria}' and p.temp = t.categoria)");
        if($tipo_contenido == 'jurisprudencia'){
          // update jurisprudencias set categoria = 72 where categoria = ''
        }
      }

      /* imagenes y archivos para todos los casos */
      foreach ($list as $item) {
        $fieldsUpdate = (object)[];
        /* solo tienen una imagen a lo mucho en cualquiera de los casos */
        $imagen = collect($DB->select("SELECT * from imagenes where codigo = {$item->$nombre_cod_biblioteca} and modulo = '{$nombre_modulo}' AND categoria LIKE '%grand%'"))->first();
        $fieldsUpdate->imagen = isset($imagen) ? $imagen->nombre : '';

        // $archivos = collect($DB->select("SELECT * from archivos where cod_modulo = {$item->$nombre_cod_biblioteca} and modulo = '{$nombre_modulo}' "))->pluck('nombre_archivo')->implode(',');
        $archivos = collect($DB->select("SELECT * from archivos where cod_modulo = {$item->$nombre_cod_biblioteca} and modulo = '{$nombre_modulo}' "));
        $archivosObj = [];
        foreach($archivos as $arch){
          $archivosObj[] = (object)[ 'nombre' => $arch->nombre_original, 'archivo' => $arch->nombre_archivo];
        }
        $fieldsUpdate->archivos = json_encode($archivosObj, JSON_UNESCAPED_UNICODE);
        global $wpdb;
        $wpdb->update($config->tabla, get_object_vars($fieldsUpdate),  array($nombre_cod_biblioteca => $item->$nombre_cod_biblioteca));

      }

      /* se cambian de nombre las columnas y se actualizan los parametros*/
      if($tipo_contenido == 'normas'){
        $DB->statement(" ALTER TABLE  {$config->tabla} CHANGE COLUMN cod_normativa id INT AUTO_INCREMENT PRIMARY KEY");

        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN cod_tema idp_tema INT ");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN cod_subtema idp_subtema INT ");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN categoria idp_categoria INT ");

        $DB->statement(" UPDATE {$config->tabla} t set t.sistema = (SELECT p.id FROM xfr_parametros p WHERE p.dominio like '%sistema%' AND p.nombre = t.sistema)");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN sistema idp_sistema INT ");          

      }

      if($tipo_contenido == 'jurisprudencia'){
        $DB->statement(" ALTER TABLE  {$config->tabla} CHANGE COLUMN cod_jurisprudencia id INT AUTO_INCREMENT PRIMARY KEY");

        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN cod_tema idp_tema INT ");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN cod_subtema idp_subtema INT ");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN categoria idp_categoria INT ");

        $DB->statement(" UPDATE {$config->tabla} t set t.sistema = (SELECT p.id FROM xfr_parametros p WHERE p.dominio like '%sistemas%' AND p.nombre = t.sistema)");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN sistema idp_sistema INT ");          
        $DB->statement(" UPDATE {$config->tabla} t set t.tribunal = (SELECT p.id FROM xfr_parametros p WHERE p.dominio like '%tribunales%' AND p.nombre = t.tribunal)");
        $DB->statement(" ALTER TABLE {$config->tabla} CHANGE COLUMN tribunal idp_tribunal INT ");          

      }

      // $config->migrate = 1;
      // $param->config = json_encode($config);
      // $this->guardarObjetoTabla($param, 'xfr_parametros');


      return (object)[
        'status' => 'ok',
        'msg' => 'Se actualizaron los datos de ' . $tipo_contenido,

      ];
      
    }

    return (object)[
      'status' => 'error',
      'msg' => 'Ocurrio un error o No existe el modulo ' . $tipo_contenido,

    ];
  }



}
