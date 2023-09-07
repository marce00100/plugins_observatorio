<?php
function get_view_sentencias($xfrContenidos) {   
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
    margin: 70px auto!important;
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
				<div class=" bg-white col-xs-12 col-sm-offset-1 col-sm-10  col-md-offset-2 col-md-9 col-lg-offset-3 col-lg-6  " >
					<!-- <div class="p20 pb10">
						<h3 class="fw600 text-999 ">Gestión de Contenidos</h3>
					</div> -->
					
					<div class="panel">
						<div class="panel-heading bg-theme1--20_ mb20" style="background-color: #1d2f4e;">
							<div class="panel-title text-white-dark">
								<i class="glyphicons glyphicons-tags fa-lg"></i> <span class="ml10 " __cabecera_dt></span>
							</div>
						</div>
						<div class="panel-body pn fs16 text-333" style="min-height: 70vh;">

							<div class="">
								<table __data_list id="dataT" class=" hover" style="width:100%;" >
								</table>
							</div>
						</div>
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
              <!-- datos de usuario -->
              <div __contenido class="" style="    display: inline-block;
              width: 100%;
              min-height: 75vh;
              overflow-y: auto;
              text-align: justify;"></div>
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
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      content: '#wrap_contenidos',
      modal: "#modalContenidos",
      dataTableTarget: "#dataT",
      dataList: [],
      indiceActual: -1,

    }

    let conT = {
      dt: {},
      selectedRow: {},     
      fillDataT: function () {
        $('[__cabecera_dt]').html( `Sentencias con Perspectiva de Género Premiadas`);
				/* Aqui se configura el DT y se le asigna al Contenedor*/
        conT.dt = $(ctxG.dataTableTarget).DataTable({
          destroy: true,
          data: ctxG.dataList,
          autoWidth: true,
          // processing: true,
					// serverSide: true,
          // // autoWidth: true,
          // // info:true,
          scrollX: true,
          className: 'fs-10',
          lengthMenu: [ 10, 20, 50 ],
          columns: [
						// {title: 'Ejemplo', data: 'ejemplo', width: '50% | 600', className: 'dt-right dt-head-center dt-body-left', type:'num',},
            { title: 'vacio', orderable: false, visible: false, render: () => { return '' } },
            {
              title: 'sentencias premiadas', data: 'anio', orderable:false,
              render: function (data, type, row, meta) {
                // console.log(meta.row)
                let imagen =  (row.imagen_sm && row.imagen_sm.length > 0) ? `<img src='${row.imagen_sm}' alt="" style="width:100%; max-width: 260px" >` : '__';
                  // ((row.url_primera_imagen && row.url_primera_imagen.length > 0) ?  `<img src='${row.url_primera_imagen}' alt="" style="width:100%; max-width: 320px" >`: '__');
                let html = /*html*/`  
                      <div class="pv10 row fs15 flex wrap ph20" style="" __cont_id_sentencia="${row.id_sentencia}" __index="${meta.row}">

                        <div class="col-xs-11 mb20">
                          <h3>${row.titulo}</h3>
                          <span class="text-666 fs13"><b>Año: </b>${row.anio}</span>
                          <span class="text-666 fs13 ml20"><b>Materia: </b><span >${row.materia}</span></span> 
                          <span class="text-666 fs13 ml20"><b>Tipo: </b><span >${row.tipo}</span></span> 
                        </div>   

                        <!-- <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 flex justify-center align-center">${imagen}</div>
                            -->
                        <div class="col-xs-11  flex flex-y justify-between ml20 text-justify" style="height:100%">
                          
                          <div class="mt20">${row.resumen} ... </div>
                          <div class="text-center mt20">
                            <button __ver_contenido __id_sentencia="${row.id_sentencia}" class="btn btn-sm bg-primary-80 br-a br-greyer br8 font-family "> Ver mas </button> 
                          </div>
                        </div>
                      </div>`
                return html;
              }
            }

          ],
          language: xyzFuns.dataTablesEspanol(),
        });
				$('#modal').removeClass('hide')
      },
      refreshDataT: () => {
        conT.dt.clear().destroy();
        conT.cargarDatos();
      },
      refreshRow: (rowData) => {
        conT.dt.row(conT.selectedRow).data(rowData).invalidate()
      }

    }


    let funs = {
      cargarDatos: function () {
        xyzFuns.spinner();
        let obj = {}; 
        $.post(`${ctxG.rutabase}/contents-sentencias`, obj, (resp) => {
          ctxG.dataList = resp.data;
          conT.fillDataT();
          funs.spinner(0);
        });
      },
      
      /** PARA VER UN CONTENIDO */
      verContenidoIndividual: (id_sentencia) => {
        funs.spinner()
        console.log( { id_sentencia: id_sentencia})
        $.post(`${ctxG.rutabase}/get-content-sentencia`, { id_sentencia: id_sentencia}, (res) => {
          let contenido = res.data;
          // let categoriaConfig = ctxG.dataBJ[ctxG.categoria].categoria_config;
          // let urlRecursosCtx    = res.url_recursos_ctx;
          // let urlArchivosCtx    = res.url_archivos_ctx;
          /* Contiene toda la estructura de encabezados, body pie */
          // let estructura = categoriaConfig.estructura;
          $("[__titulo]").html(`Sentencias con Perspectiva de Género Premiadas`);

          let cabecera_titulo =/* html*/`<div class="fs22 flex align-center mt5 "><i class="glyphicons glyphicons-book mr10"></i> <span>${contenido.tema}</span></div>`;

          let cabecera_informacion = '';
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Año:</span><span class="ml5">${contenido.anio}</span></div>`;
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Materia:</span><span class="ml5">${contenido.materia}</span></div>`;
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Tipo de Resolución:</span><span class="ml5">${contenido.tipo}</span></div>`;
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Descriptores:</span><span class="ml5">${contenido.descriptores}</span></div>`;
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Dictada por:</span><span class="ml5">${contenido.dictada}</span></div>`;
              cabecera_informacion += /*html*/`<div class="fs14"><span class="fw600 w150" style="display:inline-block">Auroridades judiciales:</span><span class="ml5">${contenido.autoridades}</span></div>`;

          let hechos = '';
          hechos += contenido.hechos && contenido.hechos.length > 0 ? /*html*/`
            <h3 class="text-primary--20">HECHOS</h3>
            <div class="ml20">${contenido.hechos}</div>
          ` : '';

          let proceso_argumentativo = '';
          proceso_argumentativo += /*html*/`<h3 class="text-primary--20">PROCESO ARGUMENTATIVO</h3>`;
          proceso_argumentativo +=  contenido.proceso1 && contenido.proceso1.length > 0 ? /*html*/`
            <h3 class="text-primary--20">Identificación del problema jurídico y la definición de persona pertenecientes a población o grupos de atención prioritaria</h3>
            <div class="ml20">${contenido.proceso1}</div>
          ` : '';
          proceso_argumentativo +=  contenido.proceso2 && contenido.proceso2.length > 0 ? /*html*/`
            <h3 class="text-primary--20">Determinación del derecho aplicable y problemas normativos existentes</h3>
            <div class="ml20">${contenido.proceso2}</div>
          ` : '';
          proceso_argumentativo +=  contenido.proceso3 && contenido.proceso3.length > 0 ? /*html*/`
            <h3 class="text-primary--20">Determinación de los hechos y valoración de la prueba</h3>
            <div class="ml20">${contenido.proceso3}</div>
          ` : '';
          proceso_argumentativo +=  contenido.proceso4 && contenido.proceso4.length > 0 ? /*html*/`
            <h3 class="text-primary--20">Parte resolutiva y reparación del daño</h3>
            <div class="ml20">${contenido.proceso4}</div>
          ` : '';
          
          let analisis = '';
          analisis +=  contenido.analisis && contenido.analisis.length > 0 ? /*html*/`
            <h3 class="text-primary--20">ANÁLISIS DE GÉNERO</h3>
            <div class="ml20">${contenido.analisis}</div>
          ` : '';


          let adjuntos = '';
          adjuntos += contenido.archivos && contenido.archivos.length > 0 ? 
            /*html*/`<h3 class="text-primary--20">ANÁLISIS DE GÉNERO</h3>` +
            _.reduce(contenido.archivos, function(carry, elem, k){
              return carry + /*html*/`<div><a href="${elem.archivoUrl}" target="_blank"><i class="glyphicon glyphicon-cloud-download mr5"></i> ${elem.nombre}</a></div>`;
            }, '') : '';

          // _.forEach(contenido.archivos, (item) => {   
          //   if(contenido[item.campo] && contenido[item.campo].length > 0){
          //     let etiqueta = !(item.etiqueta && item.etiqueta.length > 0) ? '' : 
          //     /*html*/`<h3>${item.etiqueta}</h3>`; 
          //     pie += /*html*/`
          //       <div >
          //           ${etiqueta}          
          //           <div class="ml20">${funs.htmlCamposEspeciales(item, contenido[item.campo], 'individual')}</div>
          //       </div>`;
          //   }
          // });


          let html = /*html*/
            `<div style="    margin: 15px 3px; padding: 20px; border: 1px solid lightgrey; border-radius: 8px; box-shadow: 1px 1px 15px -5px #00000055; ">
              <div class="mb20 text-theme1--40 fw600">${cabecera_titulo}</div>
              <div class="text-444">    ${cabecera_informacion} </div>
            </div>
            
            <div style="white-space: pre-line; word-wrap: break-word; margin-top:-15px; font-size:16px">
              ${hechos}
            </div>
            <div style="white-space: pre-line; word-wrap: break-word;  margin-top:-15px;  font-size:16px">
              ${proceso_argumentativo}
            </div>
            <div style="white-space: pre-line; word-wrap: break-word;  margin-top:-15px;  font-size:16px">
              ${analisis}
            </div>
            <div class="mt20" style="word-wrap: break-word; font-size: 16px">
              ${adjuntos}
            </div>
            `;

          $(`${ctxG.modal} [__contenido]`).html(html)
          xyzFuns.showModal(ctxG.modal);
          funs.spinner(false);
        })

      },
      /* Coloca la clase activo al boton de categoria activo */
      // activarBotoncategoria: (categoria) => {
      //   $("[__accion_categoria]").removeClass('activo');
      //   $(`[__accion_categoria=${categoria}]`).addClass('activo');
      // },
      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },

    }

    //-------------------- Listeners  --------------------------------
    let listen = () => {

      /* DEL CONTENEDOR */
      $(ctxG.content)
        /* Click en boton ver */
        .on('click', '[__ver_contenido]', function(e){
          let id_sentencia = $(e.currentTarget).attr("__id_sentencia");
          funs.verContenidoIndividual(id_sentencia);
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
			// conT.fillDataT();
      funs.cargarDatos();
    })();
  })
</script>

<?php
  return ob_get_clean();
}
?>