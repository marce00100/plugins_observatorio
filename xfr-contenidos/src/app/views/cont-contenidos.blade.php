<?php
function get_view_contenidos($xfrContenidos,$atts) {   
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
    margin: 80px auto;
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

  .frctl .panel-body {
    border: none;
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

</style>
		<div id="wrap_contenidos" class="bg-content frctl">

			<div class="pv20 ph5 font-roboto">
				<div class=" bg-white col-xs-12 col-sm-offset-1_ col-sm-12  col-md-offset-1_ col-md-11 col-lg-offset-1 col-lg-10 col-xl-offset-2 col-xl-8  " >					
					<div class="panel wp97">
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

			<!-- ------------------------          Modal          ---------------------------------- -->
      <div id="modalContenidos" class="frctl white-popup-block popup-basic mfp-with-anim mfp-hide hide" style="transform: none;">
      
        <div class="panel font-roboto ">
          <!-- panel heading -->
          <div class="bg-theme1--30_ text-ccc flex align-center p15 "
            style="background-color: #1d2f4e; position: sticky; top:0px; z-index: 1;">
            <i class="glyphicons glyphicons-tag fa-lg ml10"></i>
            <h2 class="panel-title ml10 fs15" __cabecera_modal><span></span></h2>
            <span class="close btnCloseModal"><i class="glyphicons glyphicons-remove_2"></i></span>
          </div>
          <!-- end .panel-heading section -->
          <div class="panel-body of-a wp100_" style="padding-bottom: 0px;">
            <div class=" ph30 ">
              <span __nav="prev" class="text-ccc glyphicons glyphicons-chevron-left cursor"
                style="display: inline-block; position:fixed; top: 55vh;z-index: 1; padding: 60px 2px;  opacity: 0.9; background-color: #000000; border-radius: 0 15px 15px 0; font-size: 30px;  margin:0 -45px;"
                title="Atrás">
              </span>
              <!-- datos de usuario -->
              <div __contenido class="" style="display: inline-block; width: 100%; min-height: 75vh; overflow-y: auto;"></div>
      
              <span __nav="next" class="text-ccc glyphicons glyphicons-chevron-right cursor"
                style="display: inline-block; position:fixed; top: 55vh;z-index: 1; padding: 60px 2px; opacity: 0.9; background-color: #000000; border-radius: 15px 0 0 15px; font-size: 30px; margin: 0 0 0 12px"
                title="Siguiente">
              </span>
            </div>
          </div>
          
          <!-- panelfooter -->
          <div class="text-center p10" style="border-top: 1px #80808042 solid;">
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
			tipo_contenido: "<?php echo $atts['tipo_contenido'] ?>",

    }

    let conT = {
      dt: {},
      selectedRow: {},

      fillDataT: function () {
        /* Aqui se configura el DT y se le asigna al Contenedor*/
        conT.dt = $(ctxG.dataTableTarget).DataTable({
          processing: true,
					serverSide: true,
          autoWidth: true,
          // info:true,
          scrollX: true,
          className: 'fs-10',
          lengthMenu: [ 10, 20, 50 ],
					ajax: {
						url: `${ctxG.rutabase}/get-contents`,
						type: "POST",
            data: function (data) {
              data.tipo_contenido = ctxG.tipo_contenido;
						},
            complete: (res) => {
              ctxG.dataList = conT.dt.rows().data();
              /* para obtener la data , en la seccion complete se encuentra en la propiedad de responseJSON*/
              let respuesta = res.responseJSON;              
              $('[__cabecera_dt]').html(`${respuesta.dataTipoContenido.paramTipoContenido.descripcion.toUpperCase()} `);
              $(`${ctxG.modal} [__cabecera_modal]`).html(`${respuesta.dataTipoContenido.paramTipoContenido.descripcion.toUpperCase()}`);
            }
					},
          searchDelay: 800, // Retardo de búsqueda en milisegundos
          preXhr: function (xhr) {
            var valorBusqueda = $(`${ctxG.dataTableTarget} input`).val();
            if (valorBusqueda.length < 4) { // Requisito de longitud mínima de búsqueda
              xhr.abort(); // Abortar la solicitud Ajax si el valor de búsqueda es menor a 5 caracteres
            }
          },
          // drawCallback: function (callback = null) {
          //   if(callback != null)
          //     callback();
          // },
          columns: [
						// {title: 'Ejemplo', data: 'ejemplo', width: '50% | 600', className: 'dt-right dt-head-center dt-body-left', type:'num',},
            {
              title: 'contenido', data: 'fecha_publicacion', orderable:false,
              render: function (data, type, row, meta) {
                // console.log(meta.row)
                let imagen =  (row.imagen_sm && row.imagen_sm.length > 0) ? `<img src='${row.imagen_sm}' alt="" style="width:100%; max-width: 260px" >` : '__';
                  // ((row.url_primera_imagen && row.url_primera_imagen.length > 0) ?  `<img src='${row.url_primera_imagen}' alt="" style="width:100%; max-width: 320px" >`: '__');
                let html = /*html*/`  
                      <div class="pv10 row fs15 flex wrap ph20" style="" __cont_id_contenido="${row.id_contenido}" __index="${meta.row}">

                        <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl mb20">
                          <h3 __ver_contenido="${row.id_contenido}" class="cursor">${row.titulo}</h3>
                          <span class="text-666 fs13">Publicado en: ${row.fecha_publicacion ? moment(row.fecha_publicacion).format('DD/MM/YYYY') : ''}</span>
                          <span class="text-666 fs13 ml20"><i class="glyphicons glyphicons-eye_open mr5"></i> <span __cont_numero_vistas="${row.numero_vistas}">${row.numero_vistas}</span></span> 
                        </div>   

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 flex justify-center align-center">${imagen}</div>

                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 flex flex-y justify-between ml20 text-justify" style="height:100%">
                          <div clasS=" hidden-sm hidden-xs">
                            <h3 __ver_contenido="${row.id_contenido}" class="cursor">${row.titulo}</h3>
                            <span class="text-666 fs13">Publicado en: ${row.fecha_publicacion ? moment(row.fecha_publicacion).format('DD/MM/YYYY') : ''}</span>
                            <span class="text-666 fs13 ml20"><i class="glyphicons glyphicons-eye_open mr5"></i> <span  __cont_numero_vistas="${row.numero_vistas}">${row.numero_vistas}</span></span> 
                          </div>
                          <div class="mt20">${row.resumen}</div>
                          <div class="text-center mt20">
                            <button __ver_contenido="${row.id_contenido}" class="btn btn-sm bg-primary-80 br-a br-greyer br8 font-family "> Ver mas </button> 
                          </div>
                        </div>
                      </div>`
                return html;
              }
            }

          ],
          language: xyzFuns.dataTablesEspanol(),
        });
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
      /* Obtener Cnetnidos del id De labase de datos*/
      obtenerContenido(id_contenido, callback){
        funs.spinner();
        $.post(`${ctxG.rutabase}/get-content`, { id_contenido: id_contenido }, (resp) => {
          callback(resp);
          xyzFuns.showModal(ctxG.modal);
          setTimeout(() => {
            funs.incrementaNumeroVistas(id_contenido);
          }, 800);
          funs.spinner(0);
        });
      },
      /* Para abrir modal, cuando se aprime ver contenido*/
      verContenido: (id_contenido) => {
        funs.spinner();
        /* Coloca en una variableglobalel indice deldataTable*/
        ctxG.indiceActual = parseInt($(`[__cont_id_contenido=${id_contenido}]`).attr("__index"));
        funs.obtenerContenido(id_contenido, (resp) => {
          $("[__contenido]").html(funs.creaHtmlContenido(resp.data, resp.config));
        });
      },
      /* Siguiente Atras */
      navegar: (nav) => {
        /* realiza la animacion de transicion */
        function transicionCards(nav, cardOld, cardNew){
          $("[__contenido]").append(cardNew);
          $(cardOld).hide('slide', { direction: nav == 'next' ? 'left' : 'right' }, 450);  
          _.delay(function () {
            $(cardOld).remove();
            $(cardNew).fadeIn(700);
          }, 455);
        }
        /* obtiene el nuevo card y llama a la transicion */
        function newCard(nav, indiceNew){
          let contentNew = ctxG.dataList[indiceNew];
          funs.obtenerContenido(contentNew.id_contenido, (resp) => {
            let newContentHtml = funs.creaHtmlContenido(resp.data, resp.config);
            cardNew = $(newContentHtml).css('display', 'none');
            cardOld = $("[__contenido] [__card]");
            transicionCards(nav, cardOld, cardNew);
            ctxG.indiceActual = indiceNew;
          })
        }

        if (nav == 'next' && ctxG.indiceActual < ctxG.dataList.length - 1){
          let indiceNew = ctxG.indiceActual + 1;
          newCard('next', indiceNew);
        }
        if (nav == 'prev' && ctxG.indiceActual > 0) {
          let indiceNew = ctxG.indiceActual - 1;
          newCard('prev', indiceNew);
        }
        if ((nav == 'next') && ctxG.indiceActual == ctxG.dataList.length - 1) {
          if($('.paginate_button.current').next('.paginate_button').length <= 0)
            return;
          funs.spinner();
          $('.paginate_button.current').next('.paginate_button').trigger('click');
          ctxG.dataList = []; 
          let ciclos = 0;
          let hastaCargar = setInterval(
            () => {
              ciclos ++;
              if (ctxG.dataList.length > 0){
                let indiceNew = 0;
                newCard('next', indiceNew);
                clearInterval(hastaCargar);
              }
              if (ciclos > 50){
                clearInterval(hastaCargar);
                funs.spinner(0);
              }
            }, 100);           
        }
        if ((nav == 'prev') && ctxG.indiceActual == 0) {
          if ($('.paginate_button.current').prev('.paginate_button').length <= 0)
            return;

          funs.spinner();
          $('.paginate_button.current').prev('.paginate_button').trigger('click');
          ctxG.dataList = []; 
          let ciclos = 0;
          let hastaCargar = setInterval(
            () => {
              ciclos ++;
              if (ctxG.dataList.length > 0){
                let indiceNew = ctxG.dataList.length - 1;
                newCard('prev', indiceNew);
                clearInterval(hastaCargar);
              }
              if (ciclos > 50){
                clearInterval(hastaCargar);
                funs.spinner(0);
              }
            }, 100); 
        }
        
      },
      /* Crea htmlContenido*/
      creaHtmlContenido: (obj, objConfig)=>{
        let imagen = !obj.imagen ? '' :
                          /*html*/`
                          <div class="mt30">
														<img src='${obj.imagen}' alt=""   >
													</div>`;
        let resumen = !obj.resumen ? '' :
                          /*html*/`
                          <div class="mt30 br-a br-light br8 p20">                            
														${obj.resumen}
													</div>`;
        let campos_extra = '';
        if (obj.campos_extra && objConfig.campos_extra) {
          _.forEach(objConfig.campos_extra, (itemConfig) => {
            campos_extra += !obj.campos_extra[itemConfig.nombre] ? '' :
              /*html*/`
              <div>
                <i class="${itemConfig.icono_class} mr15"></i> 
                ${itemConfig.etiqueta}: ${itemConfig.tipo == 'link' ?  
                /*html*/`<a href="${obj.campos_extra[itemConfig.nombre]}" target="_blank">${obj.campos_extra[itemConfig.nombre]}</a>` :
                /*html*/`<span>${obj.campos_extra[itemConfig.nombre]}</span>` 
              }
              </div>`
          })
        }

        let archivos = (!obj.archivos || obj.archivos.length <= 0 ) ? '' 
            : (function () {
                let htmlArchivosItem =
                  _.reduce(obj.archivos, function (carry, elem, k) {
                    return carry + /*html*/
                        `<div>
                          <a href="${elem.archivoUrl}" target="_blank" class="text-primary-20 mt5"><i class="glyphicon glyphicon-cloud-download"></i> ${elem.nombre}</a>
                        </div>`}, '');
                return /*html*/`
                    <div class="mt10"><div>Anexos</div>
                      <div class="pl20">${htmlArchivosItem}</div>
                    </div>`;
              })();

        let htmlContenido = /*html*/`
												<div class="fs15" __card __id_contenido="${obj.id_contenido}">
													<h2 class="ph20">${obj.titulo}</h2>
													<div clasS=" ph20 flex justify-end">
                            <span class="text-666 fs13">Publicado en: ${moment(obj.fecha_publicacion).format('DD/MM/YYYY')}</span>
                            <span class="text-666 fs13 ml20"><i class="glyphicons glyphicons-eye_open mr5"></i> ${obj.numero_vistas}</span> 
													</div>
													${imagen}
													${resumen}
													<div class="mt10 p10 pl20 pr40">                            
														${obj.contenido}
													</div class="mt10 p10 pl20 pr40">
                          ${campos_extra}
                          ${archivos}
												</div>`;
        return htmlContenido;
      },
      verificaUrlParam: ()=>{
        let url = window.location.href;
        let partsUrl = url.split('?');
        if (partsUrl.length <= 1) 
          return;
        
        let idParam = partsUrl[1].split('=');
        if(idParam.length <= 1 )
          return;
        let id_contenido = idParam[1];
        funs.verContenido(id_contenido);


        
      },
      /* Incrementa el numero de vistas en el DOM*/
      incrementaNumeroVistas: (id_contenido) =>{
        let elemContenido = $(`[__cont_id_contenido=${id_contenido}]`);
        let numVistasInc = 1 + parseInt(elemContenido.find(`[__cont_numero_vistas]`).attr("__cont_numero_vistas"));
        elemContenido.find(`[__cont_numero_vistas]`).attr("__cont_numero_vistas", numVistasInc).html(numVistasInc);
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

        .on('click', '[__ver_contenido]', (e) => {
          funs.verContenido($(e.currentTarget).attr('__ver_contenido'));
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
			conT.fillDataT();
      funs.verificaUrlParam();
    })();
  })
</script>

<?php
  return ob_get_clean();
}
?>