<?php
function get_view_contenidos_biblioteca_juridica($xfrContenidos, $atts) {   
  #CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
  
  wp_enqueue_style('mgnific_popup'        , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/plugins/magnific/magnific-popup.css', array(), '1.0.4', 'all');
  wp_enqueue_style('adminmodal.css'       , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css', array(), '1.0.4', 'all');
  wp_enqueue_style('datatables.min.css'   , $xfrContenidos->core_url . 'assets/libs-ext/DataTables/datatables.min.css', array(), '1.0.4', 'all');
  
  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
  wp_enqueue_script('jquery-ui.min.js'          , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/jquery/jquery_ui/jquery-ui.min.js', array(), null, true);
  wp_enqueue_script('bootstrap.min.js'          , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/bootstrap/bootstrap.min.js', array(), null, true);
  wp_enqueue_script('jquery.magnific-popup.js'  , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/plugins/magnific/jquery.magnific-popup.js', array(), null, true);
  // wp_enqueue_script('utility.js'             , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/utility/utility.js', array(), null, true);
  wp_enqueue_script('lodash.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
  wp_enqueue_script('moment.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/moment/min/moment.min.js', array(), null, true);
  wp_enqueue_script('datatables.min.js'         , $xfrContenidos->core_url . 'assets/libs-ext/DataTables/datatables.min.js', array(), null, true);
 
ob_start(); ?>

<style>
.popup-basic {
    position: relative;
    background: #FFF;
		max-width: 860px!important;
    margin: 40px auto;
    width: 90%!important;
    min-height: 92vh!important;
  }

  /* Oculta la cabecera de la tabla el TH */
  .frctl .dataTables_wrapper .dataTables_scrollHead {
    display: none;
  }

  /* Ocultan los bordes laterales de las celdas y de la tabbla,
   solo viusaliza las lineas de abajo */
  .frctl .dataTables_wrapper .dataTables_scrollBody td {
    border-left: none !important;
    border-right: none !important;
    border-top: none !important;
    border-bottom: 1px solid rgb(0, 0, 0, 0.1);
  }



  .frctl table.dataTable {
    border-left: none !important;
    border-right: none !important;
    border-top: 1.3px solid rgb(0, 0, 0, 0.3) !important;
    margin-top: 10px !important;
  }

  .frctl .btnCloseModal{
    position: absolute; 
    right: 10px; 
    color: #ffffff; 
    opacity: 0.7; 
    text-shadow: none;
    padding: 10px;
    cursor: pointer;
  }

  .frctl .panel-body {
    border: none;
  }
  /* BOTONES */
  .frctl .categoria{
    display: inline-block;
    padding: 10px;
    border-bottom: 3px solid transparent;
    margin-bottom: -3px;
    cursor:pointer;
  }

  .frctl .categoria.activo{
    border-color: #124db2;
  }

  .frctl .categoria:hover{
    border-color: #c0c0c0;
  }

  .frctl .lineacategoria{
    border-bottom: 1px solid lightgray; 
    margin-top: 3px
  }
  /* ACORDEON */
  .frctl .acordeon_box{
    min-height: 45px;
  }
  .frctl .acordeon_box .box_cabecera{
    display: flex;
    align-items: center;
    gap: 30px;
    position: relative;
  }
  .frctl .box_cabecera .acordeon_arrow{
    position: absolute;
    right: 30px;
  }
  /* Titulos NIVELES */
  .frctl .letra_4{
    font-size: 22px;
    padding: 15px;
    color: #aa3b07;
  }
  .frctl .letra_3{
    font-size: 20px;
    padding: 10px;
    color: #1d2f4e;
  }
  .frctl .letra_2{
    color: #9fb022;
    font-size: 18px;
    padding: 8px 20px;
    margin-top: 20px;
    font-weight: bold;
  }
  .frctl .letra_1{
    font-size: 16px;
    padding: 8px 0 8px 40px;
    color: #476795;
    font-weight: 600;
  }
  .frctl .resumen{
    font-size: 14px;
    padding: 10px 30px 10px 49px;
    background-color: #fafcff;
    /* margin-top: 10px; */
    color: #333;
    font-weight: 400;
    margin: 10px 0 0 146px;
    border: 1px solid lightgray;
    border-radius: 12px;
    width: 50%;
    text-align: justify;
  }
  .frctl .letra_titulo{
    font-size: 16px;
  }
  .frctl .letra_0{
    font-size: 13px;
  }
</style>
		<div id="wrap_contenidos" class="bg-content frctl">

			<div class="pv20 ph5 font-roboto">
				<div class=" bg-white col-xs-12 col-sm-offset-1 col-sm-11  col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8 col-xl-offset-2 col-xl-7 " >
					<!-- <div class="p20 pb10">
						<h3 class="fw600 text-999 ">Gestión de Contenidos</h3>
					</div> -->
					<div class="text-333"  style="background-color: #f9f9f9; padding: 21px; border: 1px solid #ccc; border-radius: 8px;">
            <label for=""><h3>Biblioteca Jurídica</h3></label>
            <select __bj_select class="form-control fs18 h-50 ph40" name="" id="">
            </select>
          </div>
					<div __bj_biblioteca>
            <div __bj_botones_categoria class="fs16 mb20"></div>
            <div __bj_contenido class="mt20 " style="min-height: 70vh;"></div>
          </div>
				</div>
			</div>

			<!-- -----------------------------------------          Modal  --------------------------------------------------- -->
      <div id="modalContenidos" class="frctl white-popup-block popup-basic mfp-with-anim mfp-hide hide" style="transform: none;">
      
        <div class="panel font-roboto ">
          <!-- panel heading -->
          <div class="bg-primary--60 flex align-center p15"
            style=" top:0px; z-index: 1; color:#ffffffbd!important">
            <i class="glyphicons glyphicons-tag fa-lg ml10"></i>
            <h2 class="panel-title ml10" __titulo><span></span></h2>
            <span class="close btnCloseModal"><i class="glyphicons glyphicons-remove_2"></i></span>
          </div>
          <!-- end .panel-heading section -->
          <div class="panel-body of-a fs16" style="padding-bottom: 0px;">
            <div class=" ph30 ">
              <!-- <span __nav="prev" class="text-ccc glyphicons glyphicons-chevron-left cursor"
                style="display: inline-block; position:fixed; top: 55vh;z-index: 1; padding: 60px 2px;  opacity: 0.9; background-color: #000000; border-radius: 0 15px 15px 0; font-size: 30px;  margin:0 -45px;"
                title="Atrás">
              </span> -->
              <!-- datos de usuario -->
              <div __contenido class="" style="    display: inline-block;
              width: 100%;
              min-height: 75vh;
              overflow-y: auto;
              text-align: justify;"></div>
      
              <!-- <span __nav="next" class="text-ccc glyphicons glyphicons-chevron-right cursor"
                style="display: inline-block; position:fixed; top: 55vh;z-index: 1; padding: 60px 2px; opacity: 0.9; background-color: #000000; border-radius: 15px 0 0 15px; font-size: 30px; margin: 0 0 0 -19px"
                title="Siguiente">
              </span> -->
            </div>
          </div>
          
          <!-- panelfooter -->
          <div class="text-center p10 mt20" style="border-top: 1px #80808042 solid;">
            <div __error class="wp100"></div>
            <!-- CON ICONOS -->
            <button __cerrar class="bg-theme1--60_ btn btn-md br6 br-a br-dark w150 fs14" style="background-color: #1d2f4e;">Cerrar</button>
          </div>
        </div>
      </div>
      <!-- end: .panel -->

		</div>

<script>
  jQuery(function ($) {     
    let ctxG = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1', /* ruta de contenidos bibliotecaJudicial */
      rutagral: xyzFuns.urlRestApiWP + 'gral/v1', /* ruta para parametros */
      content: '#wrap_contenidos',
      modal: "#modalContenidos",
      dataBJ: [],
      // dataTableTarget: "#dataT",
      // dataList: [],
      // indiceActual: -1,
      biblioteca: "<?php echo $atts['biblioteca'] ?>",
			categoria: "<?php echo $atts['categoria'] ?>",
      carpetaImg: {
        paises      : 'banderas/',
        sistemasDDHH: 'sistemas/',
        tribunales  : 'tribunales/'
      }
    }



    let funs = {
      /* Carga primera vez el combo de bibliotecas y si hay atributos del shortcode, carga esos datos */
      inicializaControles: () => {
        $.post(`${ctxG.rutagral}/get-parametros-from`, { dominio: 'biblioteca_juridica' }, function (res) {
          let optsBJ = xyzFuns.generaOpciones(res.data, 'nombre', 'descripcion');
          $("[__bj_select]").html(optsBJ).val(ctxG.biblioteca); /* selecciona la opcion del shortcode inicial*/
        })
        /* Carga la opcion del shortcode inicial*/
        funs.cargarBiblioteca(ctxG.biblioteca, true);
      },

      /** Carga una biblioteca juridica: normas, juriprudencia, recomendaciones*/
      cargarBiblioteca: (biblioteca, cargaPrimeraVez = false) => {     
        ctxG.biblioteca = biblioteca;  
        funs.spinner();
        $("[__bj_botones_categoria]").html('');
        $("[__bj_contenido]").html('');

        $.post(`${ctxG.rutagral}/get-parametros-from`, { dominio: 'categoria_' + biblioteca.toLowerCase() }, function (res) {
          let categoriasBiblioteca = res.data;
          /* Crea los botones de categorias si las tiene*/
          let botonesCategorias = 
            _.reduce(categoriasBiblioteca, function (carry, item) {
                  let html = '';
                  if (item.nombre != 'vacio')
                    html = /*html*/`<div __accion_categoria="${item.nombre}" class="categoria ml30 ">${item.descripcion}</div>`
                  return carry + html;
            }, '');

          if (botonesCategorias.length > 0)
            botonesCategorias += /*html*/`<div class="lineacategoria"></div>`
          $("[__bj_botones_categoria]").html(botonesCategorias);
          

          $.post(`${ctxG.rutabase}/contents-bj`, { biblioteca: biblioteca /*, categoria: '' */ }, (res) => {
            /* Carga en variable global */
            ctxG.dataBJ = res.data_complete;
            
            /* si es primera vez y se tiene el atributo del shortcode, si no inicia en la primera categoria */
            let categoria = (cargaPrimeraVez && ctxG.categoria != '') ? ctxG.categoria 
              : categoriasBiblioteca[0].nombre;

            funs.mostrarContenidos(categoria);
            funs.spinner(false);
          })

        })
      },
      
      /** Cargar los contenidos jerarquicamente segun la categoria */
      mostrarContenidos: (categoria = '') => {
          ctxG.categoria = categoria;
          funs.activarBotoncategoria(categoria);
          let urlBibliotecaArchivos = ctxG.dataBJ[categoria].url_archivos_ctx;
          let categoriaConfig = ctxG.dataBJ[categoria].categoria_config;
          let nivel = categoriaConfig.niveles.length;
          let html = '';

          /* NIVEL PRINCIPAL 3 o 2 acordeon */
          _.forEach(ctxG.dataBJ[categoria].data, function (val, key) {
            key = funs.htmlCamposEspeciales(categoriaConfig.niveles[0], key);
            let box = /* html*/
            `<div class="letra_${nivel} acordeon_box br-b" >
              <div class="box_cabecera cursor" >${key}<i class="acordeon_arrow fa fa-chevron-right"></i></div>
              <div class="box_contenido " style="display:none">`;
            if (nivel - 1 > 0){

              /** NIVEL  */
              _.forEach(val, function (val, key) {
                key = funs.htmlCamposEspeciales(categoriaConfig.niveles[1], key);
                box += /*html*/`<div class="letra_${nivel - 1}">${key}</div>`;
                if (nivel - 1 - 1 > 0) {

                  /** NIVEL */
                  _.forEach(val, function (val, key) {
                    box += /*html*/`<div class="letra_${nivel - 1 - 1}">${key}</div>`;
                    if(nivel -1 -1 -1 > 0){

                      /** NIVEL */
                      _.forEach(val, function (val, key) {
                        box += /*html*/`<div class="letra_${nivel - 1 - 1 -1}">${key}</div>`;
                        
                        if (nivel - 1 - 1 - 1 -1 <= 0) {
                          box += funs.htmlCamposBiblioteca(val, urlBibliotecaArchivos);
                        }
                      })
                    }

                    else { //if (nivel - 1 - 1 - 1 <= 0) {
                      box += funs.htmlCamposBiblioteca(val, urlBibliotecaArchivos);
                    }
                  })

                }
                else { // if (nivel - 1 - 1 == 0) {
                  box += funs.htmlCamposBiblioteca(val, urlBibliotecaArchivos);
                }
              })
            }
            else {
              box += funs.htmlCamposBiblioteca(val, urlBibliotecaArchivos);
            }
            box += /*html*/`</div></div>`;
            html += box;
          })

          $("[__bj_contenido]").html(html);
      },

      /* Acomodacion de campos especiales */
      htmlCamposEspeciales: (elemCfg, text, modo_vista = 'lista_jerarquia') => {
        /* de los archivos propios de cada biblioteca como archivos e imagenes */
        let urlArchivosCtx = ctxG.dataBJ[ctxG.categoria].url_archivos_ctx;
        /* de los directorios de la imagen logo de cada recurso */
        let dirPaises     = ctxG.dataBJ[ctxG.categoria].url_recursos_ctx + ctxG.carpetaImg.paises;
        let dirTribunales = ctxG.dataBJ[ctxG.categoria].url_recursos_ctx + ctxG.carpetaImg.tribunales;
        let dirSistemas   = ctxG.dataBJ[ctxG.categoria].url_recursos_ctx + ctxG.carpetaImg.sistemasDDHH;

        let html = '';
        /* Si es tema  */
        if (elemCfg.campo == 'tema' && text && text.length > 0) {
          html = (modo_vista == 'lista_jerarquia') ? 
          /*html*/`<i class="glyphicons glyphicons-book"></i><span class="ml10 wp80">${text}</span>` :
          /*html*/`<i class="glyphicons glyphicons-book"></i> <span class="ml20 wp80">${text}</span>`;
        }
        /* Si es subtema  */
        if (elemCfg.campo == 'subtema' && text && text.length > 0) {
          html = (modo_vista == 'lista_jerarquia') ? 
          /*html*/`<span class="ml20 wp80">${text}</span>` :
          /*html*/`<span class="ml20">${text}</span>`;
        }
        /* Si es comite */
        if (elemCfg.campo == 'comite' && text && text.length > 0) {
          html = /*html*/`<i class="fa fa-comments mr5"></i><span class="wp80">${text}</span>`;
        }
        /* Si es de un campo para mostrar bandera con el nombre del pais, sigla.png dentro de carpeta paises */
        if (elemCfg.campo == 'imagen_nombre_pais' && text && text.length > 0) {
          let arr = text.split('-');
          let img = dirPaises + arr[0] + '.png';
          let pais = arr[1];
          html = (modo_vista == 'lista_jerarquia') ? 
          /*html*/`<img src="${img}"><span  class="wp80">${pais}</span>` :
          /*html*/`<img src="${img}"><span class="ml20">${pais}</span>`;
        }
        /* Si es del campode Sistema de DDDHH, su imagen es el mismo nombre desistema con ext png, dentro de carpeta sistemas*/
        if (elemCfg.campo == 'imagen_nombre_sistema' && text && text.length > 0) {
          let sistemas = ctxG.dataBJ[ctxG.categoria].sistemas;
          let sistema = _.find(sistemas, (item) => item.nombre == text);
          let img = dirSistemas + sistema.nombre + '.png';
          html = (modo_vista == 'lista_jerarquia') ? 
          /*html*/`<img src="${img}" class="w100"><span  class="wp80">${sistema.descripcion}</span>` :
          /*html*/`<img src="${img}" class="w100"><span class="ml20">${sistema.descripcion}</span>`;
        }
        /* Si es del campo de Organo o Tribunal (jurisprudencia nacional) se genera un cuadro de color con el nombre, */
        if (elemCfg.campo == 'imagen_nombre_tribunal' && text && text.length > 0) {
          let arr = text.split('-');
          let img = dirTribunales + arr[0] + '.png';
          let tribunal = arr[1];
          html = (modo_vista == 'lista_jerarquia') ?
          /*html*/`<img src="${img}" class="w100"><span  class="wp80">${tribunal}</span>` :
          /*html*/`<img src="${img}" class="w100"><span class="ml20">${tribunal}</span>`;
        }

        /* si es archivos concatenados*/
        if (elemCfg.campo == 'archivos' && text && text.length > 0) {
          html = _.reduce(text.split(','), function (carry, elem, k) {
            return carry + /*html*/`  <a href="${urlArchivosCtx}/${elem}" target="_blank"><i class="glyphicon glyphicon-cloud-download"></i> Descarga_${k + 1}</a>`
          }, '');
        }

          
          


        return html != '' ? html : text;
      },

      /** Realiza el html del titulo, y campos menores, entre ellos archivos*/
      htmlCamposBiblioteca: (objArray, urlBibliotecaArchivos) => {
        let box = '';
        _.forEach(objArray, function (val) {
          // let archivos = val.archivos || '';
          // let archivoshtml = _.reduce(archivos.split(','), function (carry, elem) {
          //   return carry + /*html*/`  <a href="${urlBibliotecaArchivos}/${elem}" target="_blank"><i class="glyphicon glyphicon-cloud-download"></i> Descargar</a>`
          // }, '');

          let archivoshtml = !(val.archivos && val.archivos.length > 0) ? '' :
            _.reduce(val.archivos.split(','), function (carry, elem, k) {
              return carry + /*html*/`  <a href="${urlBibliotecaArchivos}/${elem}" target="_blank"><i class="glyphicon glyphicon-cloud-download"></i> Descarga_${k + 1}</a>`
              }, '');
          if(ctxG.biblioteca == 'normas'){
            box += /*html*/
            `<div class="resumen">
              <div class="letra_titulo">${val.titulo}</div>
              <div class="letra_0">${val.tipo}</div>
              <div class="letra_0">${archivoshtml}</div>
              <div class="flex justify-center mt5">
                <button __btn_ver __biblioteca="${ctxG.biblioteca}" __id_biblioteca="${val.id_biblioteca}" 
                class="btn btn-sm ph20 br8 br-a br-greyer bg-primary-80">Ver</button>
              </div>
            </div>`;
          }
          if(ctxG.biblioteca == 'jurisprudencia'){
            box += /*html*/
            `<div class="resumen">
              <div class="letra_titulo">${val.titulo}</div>
              <div class="letra_0">${(val.nombre_tribunal && val.nombre_tribunal.length > 0) ? val.nombre_tribunal :
                                      (val.organo && val.organo.length > 0) ? val.organo : '' }</div>
              <div class="letra_0">${archivoshtml}</div>
              <div class="flex justify-center mt5">
                <button __btn_ver __biblioteca="${ctxG.biblioteca}" __id_biblioteca="${val.id_biblioteca}" 
                class="btn btn-sm ph20 br8 br-a br-greyer bg-primary-80">Ver</button>
              </div>
            </div>`;
          }
          if (ctxG.biblioteca == 'jurisprudencia_relevante') {
            let imagen =  (val.imagen_sm && val.imagen_sm.length > 0) ? `<img src='${val.imagen_sm}' alt="" style="width:100%; max-width: 260px" >` : '__';
            box += /*html*/
            `<div class="resumen ph5 ml30 wp90">
              <div class="pv10 row fs15 flex wrap ph20" style="">

                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl mb20">
                  <h3>${val.titulo}</h3>
                  <span class="text-666 fs13">Fecha: ${moment(val.fecha).format('DD/MM/YYYY')}</span>
                  
                </div>   

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 flex justify-center align-center">${imagen}</div>

                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 flex flex-y justify-between ml5 text-justify" style="height:100%">
                  <div clasS=" hidden-sm hidden-xs">
                    <h3>${val.titulo}</h3>
                    <span class="text-666 fs13">Fecha: ${moment(val.fecha).format('DD/MM/YYYY')}</span>                    
                  </div>
                  <div class="mt20">${val.resumen} ... </div>
                  <!--<div class="text-center mt20">
                    <button __ver_contenido="${val.id_contenido}" class="btn btn-sm bg-primary-80 br-a br-greyer br8 font-family "> Ver mas </button> 
                  </div>-->
                </div>
              </div>
              <!--<div class="letra_titulo">${val.titulo}</div>
              <div class="letra_0">${(val.nombre_tribunal && val.nombre_tribunal.length > 0) ? val.nombre_tribunal :
                                      (val.organo && val.organo.length > 0) ? val.organo : '' }</div>
              <div class="letra_0">${archivoshtml}</div>-->
              <div class="flex justify-center mt5">
                <button __btn_ver __biblioteca="${ctxG.biblioteca}" __id_biblioteca="${val.id_biblioteca}" 
                class="btn btn-sm ph20 br8 br-a br-greyer bg-primary-80">Ver</button>
              </div>
            </div>`;
          }
          if(ctxG.biblioteca == 'recomendaciones'){
            box += /*html*/
            `<div class="resumen">
              <div class="letra_titulo fw600">[${val.anio}]</div>
              <div class="letra_titulo ">${val.texto}</div>
              <div class="letra_0 mt10">${archivoshtml}</div>
            </div>`;
          }
        })
        return box;
      },
      
      /** PARA VER UN CONTENIDO */
      verContenidoIndividual: (id_biblioteca, biblioteca) => {
        funs.spinner()
        $.post(`${ctxG.rutabase}/get-content-bj`, { id_biblioteca: id_biblioteca, biblioteca: biblioteca }, (res) => {
          let contenido = res.data;
          let categoriaConfig = ctxG.dataBJ[ctxG.categoria].categoria_config;
          let urlRecursosCtx    = res.url_recursos_ctx;
          let urlArchivosCtx    = res.url_archivos_ctx;
          /* Contiene toda la estructura de encabezados, body pie */
          let estructura = categoriaConfig.estructura;
          $("[__titulo]").html(estructura.encabezado);

          /** titulos */
          let cabecera_titulos = '';
          _.forEach(estructura.cabecera_titulos, (item, k) => {
            if(contenido[item.campo] && contenido[item.campo].length > 0){
              /* reduce el tamaño de font gradualmente en progresion*/
              cabecera_titulos += /* html*/`<div class="fs${22 - 2 * k} flex align-center mt5 ">${funs.htmlCamposEspeciales(item, contenido[item.campo], 'individual')}</div>`
            }
          });

          let cabecera_informacion = '';
          _.forEach(estructura.cabecera_informacion, (item) => {  
            if(contenido[item.campo] && contenido[item.campo].length > 0){
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">${item.etiqueta}:</span><span class="ml5">${contenido[item.campo]}</span></div>`;
            }          
          });

          let titulo = '';
          _.forEach(estructura.titulo, (item) => {   
            if(contenido[item.campo] && contenido[item.campo].length > 0) {
              let etiqueta = !(item.etiqueta && item.etiqueta.length > 0) ? '' : /*html*/`<span class="mr10">${item.etiqueta}</span>`; 
              titulo += /*html*/`<div class="mt20 pv10 text-primary--40 br-b"><h2 class="ml20 mt20 fs22" style="    line-height: 1.5;">${etiqueta} ${contenido[item.campo]}</h2></div>`;
            }
          });
          
          let cuerpo = '';
          _.forEach(estructura.cuerpo, (item) => {   
            if(contenido[item.campo] && contenido[item.campo].length > 0){
              let etiqueta = !(item.etiqueta && item.etiqueta.length > 0) ? '' : /*html*/`<h3 class="text-primary--20">${item.etiqueta}</h3>`; 
              cuerpo += /*html*/`<div style="margin-top: -40px">
                                    ${etiqueta}          
                                    <div class="ml20">${contenido[item.campo]}</div>
                              </div>`;
              }
          });

          let pie = '';
          _.forEach(estructura.pie, (item) => {   
            if(contenido[item.campo] && contenido[item.campo].length > 0){
              let etiqueta = !(item.etiqueta && item.etiqueta.length > 0) ? '' : 
              /*html*/`<h3>${item.etiqueta}</h3>`; 
              pie += /*html*/`
                <div >
                    ${etiqueta}          
                    <div class="ml20">${funs.htmlCamposEspeciales(item, contenido[item.campo], 'individual')}</div>
                </div>`;
            }
          });


          let html = /*html*/
            `<div style="    margin: 15px 3px; padding: 20px; border: 1px solid lightgrey; border-radius: 8px; box-shadow: 1px 1px 15px -5px #00000055; ">
              <div class="mb20 text-theme1--40 fw600">${cabecera_titulos}</div>
              <div class="text-444">    ${cabecera_informacion} </div>
            </div>
            <div class="mt20 fs20">
              ${titulo}
            </div>
            <div style="white-space: pre-line; word-wrap: break-word; font-size:16px">
              ${cuerpo}
            </div>
            <div class="mt20" style="word-wrap: break-word; font-size: 16px">
              ${pie}
            </div>
            `;

          $(`${ctxG.modal} [__contenido]`).html(html)
          xyzFuns.showModal(ctxG.modal);
          funs.spinner(false);
        })

      },
      /* Coloca la clase activo al boton de categoria activo */
      activarBotoncategoria: (categoria) => {
        $("[__accion_categoria]").removeClass('activo');
        $(`[__accion_categoria=${categoria}]`).addClass('activo');
      },
      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },

    }

    //-------------------- Listeners  --------------------------------
    let listen = () => {

      /* DEL CONTENEDOR */
      $(ctxG.content)
        /** Click en botones de accion como editar nuevo */

        .on('change', '[__bj_select]', (e) => {
          funs.cargarBiblioteca($(e.currentTarget).val());
        })
        .on('click', '[__accion_categoria]', (e)=>{
          let categoria = $(e.currentTarget).attr("__accion_categoria");
          funs.activarBotoncategoria(categoria);
          funs.mostrarContenidos(categoria);
          // funs.mostrarContenidos($("[__bj_select]").val(), categoria);
        })
        /* Expandir o acortar el acordeon*/
        .on('click', '.box_cabecera', function(e){
          $(e.currentTarget).find('i.acordeon_arrow').toggleClass('fa-chevron-right fa-chevron-down')
          $(e.currentTarget).closest('.acordeon_box').find('.box_contenido').slideToggle(700);
        })
        /* Click en boton ver */
        .on('click', '[__btn_ver]', function(e){
          let biblioteca = $(e.currentTarget).attr("__biblioteca");
          let id_biblioteca = $(e.currentTarget).attr("__id_biblioteca");
          funs.verContenidoIndividual(id_biblioteca, biblioteca);
        })

      /** DEL MODAL */
      $(ctxG.modal)
        /* Cancel Modal*/
        .on('click', "[__cerrar], .close", () => {
          xyzFuns.closeModal();
        })
        /* del alert */
        .on('click', "[__alert_msg] .close", (e) => {
          $(e.currentTarget).closest('[__alert_msg]').remove();
        })
        /* clock en navegasion siguiente atras*/
        .on('click', "[__nav]", (e) => { 
          let nav = $(e.currentTarget).attr('__nav');
          funs.navegar(nav);
        })
    }

    /**
     * Inicializa 
     */
    let init = (() => {
      listen();
      funs.inicializaControles();
    })();
  })
</script>

<?php
  return ob_get_clean();
}
?>