<?php
function get_view_contenidos_create($xfrContenidos) {

  #CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
  
  wp_enqueue_style('mgnific_popup'        , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/plugins/magnific/magnific-popup.css', array(), '1.0.4', 'all');
  wp_enqueue_style('adminmodal.css'       , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css', array(), '1.0.4', 'all');
  wp_enqueue_style('datatables.min.css'   , $xfrContenidos->core_url . 'assets/libs-ext/DataTables/datatables.min.css', array(), '1.0.4', 'all');
  wp_enqueue_style('summernote.min.css'   , $xfrContenidos->url      . 'src/assets/libs-ext/summernote/summernote.min.css', array(), '1.0.4', 'all');

  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
  // wp_enqueue_script('jquery-3.1.1.min.js'    , $xfrContenidos->core_url . 'assets/libs-ext/min/jquery-3.1.1.min.js', array(), null, false);
  wp_enqueue_script('pnotify.custom.js'         , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/utility/pnofify/pnotify.custom.js', array(), null, true);
  wp_enqueue_script('jquery-ui.min.js'          , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/jquery/jquery_ui/jquery-ui.min.js', array(), null, true);
  wp_enqueue_script('bootstrap.min.js'          , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/bootstrap/bootstrap.min.js', array(), null, true);
  wp_enqueue_script('jquery.magnific-popup.js'  , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/vendor/plugins/magnific/jquery.magnific-popup.js', array(), null, true);
  wp_enqueue_script('utility.js'             , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/utility/utility.js', array(), null, true);
  wp_enqueue_script('lodash.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
  wp_enqueue_script('moment.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/moment/min/moment.min.js', array(), null, true);
  wp_enqueue_script('datatables.min.js'         , $xfrContenidos->core_url . 'assets/libs-ext/DataTables/datatables.min.js', array(), null, true);
  wp_enqueue_script('summernote.min.js'         , $xfrContenidos->url      . 'src/assets/libs-ext/summernote/summernote.min.js', array(), null, true);
  wp_enqueue_script('summernote.es.js'          , $xfrContenidos->url      . 'src/assets/libs-ext/summernote/summernote.es.js', array(), null, true);



ob_start(); 
?>


<style>
	.popup-basic {
		position: relative;
		background: #FFF;
		width: auto !important;
		max-width: 900px !important;
		margin: 80px auto;
	}

	.tabla-head thead>th {
		background-color: rgb(147, 72, 72);
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
			<div class="pv20 ph5">
				<div class=" bg-white col-sm-10 col-sm-offset-1 col-lg-8 col-lg-offset-2 col-xs-12" style="    box-shadow: 1px 2px 9px 1px;">
					<div class="p20 pb10">
						<h3 class="fw600 text-999 ">Gestión de Contenidos</h3>
					</div>
					
					<div class="panel">
						<div class="panel-heading  bg-dark ">
							<div class="panel-title text-white-dark">
								<i class="fa fa-paperclip fa-lg"></i> <span class="ml10 " __cabecera_dt>Contenidos</span>
							</div>
							<!-- <div class="panel-title text-light">
						<i class="fa fa-file-alt fa-2x"></i> Información de <span __cabecera_dt>Usuarios</span> <span
							__cabecera_dt_est></span>
					</div> -->
						</div>
						<div class="panel-body pn">
							<div class="row">
								<div class="col-md-12">
									<button __accion="nuevo" class="btn btn-sm bg-success--20  m5 br4 ph30 br-a br-dark">
										<i class="fa fa-plus "></i> Agregar </button>
								</div>
							</div>
							<div class="">
								<table __data_list id="dataT" class=" hover row-border tabla-head bg-table" style="margin: 0 0 40px 0; width:100%" >
								</table>
							</div>
						</div>
					</div>
          
				</div>
			</div>




			<!-- -----------------------------------------          Modal  --------------------------------------------------- -->
			<div id="modal" class="frctl white-popup-block popup-basic mfp-with-anim mfp-hide">
				<div class="panel">
					<!-- panel heading -->
					<div class="bg-system darker text-333 flex align-center p15 ">
						<i class="fa fa-paperclip fa-lg ml10"></i>
						<h2 class="panel-title ml10" __cabecera_modal><span>__</span></h2>
            <span class="close btnCloseModal"><i class="glyphicons glyphicons-remove_2"></i></span>
					</div>
					<!-- end .panel-heading section -->
					<!-- panel body -->
					<div class="panel-body of-a">
						<div class="row">
							<div class=" ph40 ">
								<!-- datos de usuario -->
								<div __fields></div>
                <textarea name="" id="" __rg_field="contenido"  cols="30" rows="10"></textarea>
								<hr>
              </div>
						</div>
					</div>
					<!-- panelfooter -->
					<div class="panel-footer flex flex-wrap justify-evenly">
						<div __error class="wp100"></div>
						<!-- CON ICONOS -->
						<button __cerrar class="btn bg-danger lighter br-a br-dark-light br6 btn-md w150 fs14">Cancelar</button>
						<button __save type="submit" class="btn btn-primary br-a br-dark-light btn-md br6 w200 fs14">Guardar</button>
					</div>
				</div>
				<!-- end: .panel -->
			</div>

		</div>

<script>
  jQuery(function ($) {     
    let ctxG = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      rutagral: xyzFuns.urlRestApiWP + 'gral/v1', /* ruta para parametros */
      content: '#wrap_contenidos',
      modal: "#wrap_contenidos #modal",
      dataTableTarget: "#dataT",
      dataList: [],
    }

    let regmodel = {
      model: {
        title: "_",
        desc: "",
        new: true,
        update: true,
        delete: true,
        classError: 'error-validacion',
        sections: [
          {
            html_parent: '[__fields]',
            title_section: 'Datos contenido',
            text_section: '',
            class: { text: "mb10", section: "mb20", title: "" },
            attr_field: '__rg_field',
            fields: [
              { field: 'id_contenido', type: 'hidden', },
              {
                field: 'titulo', type: 'text', label: 'Título', placeholder: '', title: '', help: '',
                required: true, columns_4: 4, class: { bloque: '', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'imagen', type: 'img', label: 'Imagen', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'resumen', type: 'textarea', label: 'Resumen', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'tipo_contenido', type: 'select', label: 'Tipo de contenido', placeholder: '', title: '', help: '',
                required: true, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'fecha_publicacion', type: 'text', label: 'Fecha de Publicación', placeholder: '', title: '', help: '',
                required: false, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'sub_tipo', type: 'select', label: 'Categoría', placeholder: '', title: '', help: '',
                required: false, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'estado_contenido', type: 'select', label: 'Estado', placeholder: '', title: '', help: '',
                required: false, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
            ]
          },
        ],
      },
      listasPredefinidas: {
        estados: ['ACTIVO', 'INACTIVO'],
      },
      /** Crea toda todas las secciones y sus filds */
      create_fields: (sections) => {
        _.forEach(sections, (sec) => {
          let contenedor = $(sec.html_parent);
          /* Cabecera de cada Seccion Titulo y Texto*/
          let sectionContainer = $(/*html*/`<div __section_container class="${sec.class.section}"></div>`);
          sectionContainer.append(/*html*/ `<h2  __title_section class="${sec.class.title}" style="">${sec.title_section}</h2>`);
          sectionContainer.append(/*html*/ `<div __text_section class="fs13 ${sec.class.text}">${sec.text_section}</div>`);

          let htmlFields = '';
          _.forEach(sec.fields, (field) => {

            field.class = (field.class) ? field.class : {};
            /* Todos los fields estan para reaccionar al Grid , para volver al estilo clasico descomentar form-horizontal y form-group. etc*/

            if (field.type == 'hidden') {
              htmlFields += /*html*/`
                                    <div class="section hidden">
                                            <input class="hidden" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}" >
                                    </div>`
            }

            if (field.type == 'text' || field.type == 'number' || field.type == 'date' || field.type == 'password' || field.type == 'email') {
              htmlFields +=
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} ${field.class.bloque || ''} " >
                    <div class="form-group  ${field.class.group || ''}  mbn">
                        <label  class="control-label ${field.class.label || ''}" for="${field.field}"
                        style="">${field.label}</label>
                        <div    class="">
                            <span class="append-icon_ left_"><i class="${field.class.icon}"></i>
                            </span>
                            <input type="${field.type}" class="${field.class.input || ''} form-control pl10 " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            placeholder="${field.placeholder}" title="${field.title}" ${field.required ? 'required' : ''} autocomplete="off"  >
                            <em class="fs12 text-dark block col-xs-12 ${field.class.em || ''} ">${field.help}</em>
                        </div>
                    </div>
                  </div>`
            }
            if (field.type == 'select') {
              htmlFields +=
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''}  ">
                    <div class="form-group  ${field.class.group || ''} mbn ">
                        <label class="control-label ${field.class.label}" for="${field.field}"
                        style="">${field.label}</label>
                        <div class="">
                            <span class="append-icon_ left_"><i class=""></i>
                            </span>
                            <select class="${field.class.input || ''} form-control pl10 col-xs-9  " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            title="${field.title}"  ${field.required ? 'required' : ''} ></select>
                            <em class="fs12 text-dark block col-xs-12">${field.help}</em>
                        </div>
                    </div>
                  </div>`
            }

            if (field.type == 'textarea') {
              htmlFields +=
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''}  ">
                    <div class="form-group  ${field.class.group || ''} mbn ">
                        <label class="control-label ${field.class.label}" for="${field.field}"
                        style="">${field.label}</label>
                        <div class="">
                            <span class="append-icon_ left_"><i class=""></i>
                            </span>
                            <textarea class="${field.class.input || ''} form-control pl10 col-xs-9  " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            title="${field.title}"  ${field.required ? 'required' : ''} ></textarea>
                            <em class="fs12 text-dark block col-xs-12">${field.help}</em>
                        </div>
                    </div>
                  </div>`
            }
            if (field.type == 'checkbox') {
              htmlFields +=
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} ${field.class.bloque || ''} " >
                    <div class="form-group  ${field.class.group || ''}  mbn">
                        <label  class="control-label ${field.class.label || ''}" for="${field.field}"
                        style="">${field.label}</label>
                        <div    class="">
                            <input type="${field.type}" class="${field.class.input || ''} form-control pl10 " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            placeholder="${field.placeholder}" title="${field.title}" ${field.required ? 'required' : ''} autocomplete="off"  >
                            <em class="fs12 text-dark block col-xs-12 ${field.class.em || ''} ">${field.help}</em>
                        </div>
                    </div>
                  </div>`
            }
            if (field.type == 'img') {
              htmlFields +=
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} ${field.class.bloque || ''} ">
                    <div class="form-group  has-primary mbn ">
                        <label class="col-xs-11 control-label form-label" for="imagen" style="">Imagen</label>
                        <input type="file" __archivo_up __imagen_guardada __imagen_nueva class="form-input p5 wp100 form-control pl10 col-xs-9" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}" title="" required="" accept="image/*">
                        <div class="col-xs-11 wp100 h-150 bg-light br-a  col-sm-9">
                          <div style=" width:150px; height: 147px; background-color:#000; margin: auto; ">
                          <img __imagen_img="" src="" >
                        </div></div>
                    </div>
                  </div>`
            }

          });
          sectionContainer.append(/*html*/`<div __rg_fields_container class="rg-flex-content">${htmlFields}</div>`)
          $(contenedor).append(sectionContainer);
        })

      },
      /**Iniciañizalos Selects */
      inicializaControles: () => {
        let optsEstados = xyzFuns.generaOpcionesArray(regmodel.listasPredefinidas.estados);
        $("[__rg_field=estado_contenido]").html(optsEstados);
        $("[__rg_field=estado_contenido] option")[0].selected = true; /** inicializa en la primera opcion que es Activo */

        $.post(ctxG.rutagral + '/get-parametros-from', {dominio: 'tipo_contenido'}, res =>{
          let optsTipoContenido = xyzFuns.generaOpciones(res.data, 'nombre', 'descripcion');
          $("[__rg_field=tipo_contenido]").html(optsTipoContenido);
        });



        // let tipo_contenido = xyzFuns.generaOpcionesArray(regmodel.listasPredefinidas.tipo_contenido);
        // $("[__rg_field=tipo_contenido]").html(tipo_contenido);
        // $("[__rg_field=tipo_contenido] option")[0].selected = true;



      },
      /** Crea el formulario e inicializa los selects*/
      renderForm: () => {
        let sections = regmodel.model.sections;
        regmodel.create_fields(sections);
        regmodel.inicializaControles();
      },
      /* verifica los campos requeridos devuelve array con campos que no cumplen, o si all cumple array vacio*/
      noCumplenValidacion: (container, selectorFieds) => {
        let noCumplen = [];
        $(`${container} ${selectorFieds}[required]`).removeClass(regmodel.model.classError);

        // verifica los inputs y textareas
        _.forEach($(`${container} ${selectorFieds}[required]`), function (elemInput) {
          let tagHlml = $(elemInput).prop("tagName").toLowerCase();
          if (tagHlml == 'input' || tagHlml == 'textarea' || tagHlml == 'select')
            if ($(elemInput).val() == null || $(elemInput).val().trim() == '') {
              noCumplen.push($(elemInput).attr('id'));
              $(elemInput).addClass(regmodel.model.classError);
            }
        })

        /* personalizado para tabla nims*/
        if (selectorFieds == '[__nims_operador]') {
          if ($(`${container} ${selectorFieds}[required] table tbody tr:visible`).length == 0) {
            noCumplen.push('numero_NIM');
            $(selectorFieds).addClass(regmodel.model.classError);
          }
        }

        return noCumplen;
      }


    }

    let conT = {
      dt: {},
      selectedRow: {},

      cargarDatos: function () {
        funs.spinner();
        let objSend = {}
        $.post(`${ctxG.rutabase}/get-contents`, { estado: 'ALL' }, (resp) => {
          ctxG.dataList = resp.data;
          if(ctxG.dataList.length > 0)
            conT.fillDataT();
          funs.spinner(false);
        });
      },
      fillDataT: function () {
        /* Aqui se configura el DT y se le asigna al Contenedor*/
        conT.dt = $(ctxG.dataTableTarget).DataTable({
          destroy: true,
          data: ctxG.dataList,
          autoWidth: true,
          // info:true,
          scrollX: true,
          className: 'fs-10',
          columns: [
            // {title: 'Ejemplo', data: 'ejemplo', width: '50% | 600', className: 'dt-right dt-head-center dt-body-left', type:'num',},
            {
              title: '_', width: '100', className: 'dt-head-center',
              render: function (data, type, row, meta) {
                return /*html*/`<span __accion="editar"  __id_contenido=${row.id_contenido} 
                                style="display:block; "class="p5 cursor ${row.estado_contenido == 'ACTIVO' ? 'text-success-20' : 'text-danger-20'}  " title="Editar">
                                <i class="glyphicon glyphicon-paperclip fa-lg "></i> ${row.estado_contenido}</span>`
              }
            },
            {
              title: 'Fecha Registro', data: 'fecha_registro',
              render: function (data, type, row, meta) {
                return (row.fecha_registro != null && row.fecha_registro != "") ? moment(row.fecha_registro).format('DD/MM/YYYY') : "";
              }
            },
            {
              title: 'Titulo', data: 'titulo', width: '200', className: 'dt-head-center'
            },
            {
              title: 'Imagen', data: 'imagen', className: 'dt-head-center',
              render: function (data, type, row, meta) {
                return row.imagen && row.imagen.length > 0 ? `<img src='${row.imagen}' alt="NO" style="width:100px" >` : '';
              }
            },
            // { title: 'Estado ', data: 'estado_contenido', },
            { title: 'Prioridad', data: 'prioridad', },
            { title: 'Contenido', data: 'texto_cortado', width: '250', className: 'dt-head-center' },

          ],
          language: xyzFuns.dataTablesEspanol(),
        });
      },
      refreshDataT: () => {
        // conT.dt.clear().destroy();
        // conT.cargarDatos();
      },
      refreshRow: (rowData) => {
        conT.dt.row(conT.selectedRow).data(rowData).invalidate()
      }

    }

    let funs = {
      crearFormulario: () => {
        regmodel.create_fields(regmodel.model.sections);
        regmodel.inicializaControles();

        $('[__rg_field=contenido]').summernote({
          height: 350, //set editable area's height
          minHeight: 350,
          focus: false, //set focus editable area after Initialize summernote
          toolbar: [
            ['style', [/*'style',*/ 'bold', 'italic', 'underline', /*'clear' */ ] ],
            /* ['font', ['strikethrough']], */
            /* ['fontsize', ['fontsize']],*/
            /* ['color', ['color']], */
            ['para', ['ul', 'ol', 'paragraph']],
            /* ['height', ['height']], */
            ['insert', ['link', 'picture', 'video']],
            ['table', ['table']], 
            ['view', ['codeview']] 
          ],
          lang: 'es-ES',
          oninit: function () { },
          onChange: function (contents, $editable) { },

        });

      },

      /** Obtiene toda la info de un contenido,  */
      getData: () => {
        let data = xyzFuns.getData__fields('__rg_field');
        data.contenido = $("[__rg_field=contenido]").summernote('code');
        return data;
      },

      setData: function (obj) {
        xyzFuns.setData__fields(obj, '__rg_field');
        $('[__rg_field=contenido]').summernote('code', obj.contenido);

      },
      /** Para  nuevo muestramodal vacio */
      nuevo: () => {
        $("#modal [__cabecera_modal] span").html(`Crear Contenido`);
        xyzFuns.showModal(ctxG.modal);
      },
      /** MuestraModal con datos del contenido  */
      editar: (id) => {
        let id_contenido = id;
        funs.spinner();
        $.post(ctxG.rutabase + '/get-content', { id_contenido: id_contenido }, (resp) => {
          let data = resp.data;
          funs.setData(data);
          $("#modal [__cabecera_modal] span").html(`Modificar Contenido`);
          xyzFuns.showModal(ctxG.modal);
          funs.spinner(false)
        })
      },
      /** Guarda al contenido */
      saveData: () => {
        let obj = funs.getData();
        let cumpleRequireds = true;
        cumpleRequireds = regmodel.noCumplenValidacion('[__fields]', '[__rg_field]').length == 0;
        xyzFuns.alertMsgClose('[__error]');

        /* Envia los datos */ 
        if (!cumpleRequireds) {
          xyzFuns.alertMsg("[__error]", `Se deben llenar los campos requeridos`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
          return;
        }
        // funs.spinner();
        $.post(ctxG.rutabase + '/save-content', obj , function (resp) {
          if (resp.status == 'error') {
            xyzFuns.alertMsg("[__error]", `Error: ${resp.msg}`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
          }
          // obj.id_contenido ? conT.refreshRow(resp.data) : conT.refreshDataT();
          conT.refreshDataT();

          /* Se envia los archivos */
          let formData = new FormData();
          formData.append('file', $('[__rg_field=imagen]')[0].files[0]);
          formData.append('tipo', 'imagen');
          formData.append('tipo_contenido', obj.tipo_contenido);

          /* Envio de la imagen principal */
          $.ajax({
            url: ctxG.rutabase + '/file-upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
              if (resp.status == 'error') {
                xyzFuns.alertMsg("[__error]", `Error: ${resp.msg}`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
              }
              funs.spinner(false);
            },
            error: function () {
              console.log('Error al subir la image__n.');
              funs.spinner(false);
            }
          })

          new PNotify({
            title: resp.status == 'ok' ? 'Guardado' : 'Error',
            text: resp.msg,
            shadow: true,
            opacity: 0.9,
            type: (resp.status == 'ok') ? "success" : "danger",
            delay: 1500
          });
          // xyzFuns.closeModal();
        });



      },
      limpiarModal: () => {
        $(`${ctxG.modal} [__rg_field]`).val('').removeClass('br-a br-danger');
        $(`${ctxG.modal} [__op_field]`).val('').removeClass('br-a br-danger');

        /* Quita las clases de error en todos los campos requeridos  */
        $("[required]").removeClass(regmodel.model.classError);
        xyzFuns.alertMsgClose('[__error]');

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

        .on('click', '[__accion]', (e) => {
          let accion = $(e.currentTarget).attr('__accion');
          funs.limpiarModal();
          if (accion == 'nuevo')
            funs.nuevo();
          if (accion == 'editar') {
            let id = $(e.currentTarget).attr('__id_contenido');

            funs.editar(id)
          }
        })


      /** DEL MODAL */
      $(ctxG.modal)
        .on('change', '[__rg_field=tipo_contenido]', (e) => {
          let tipo_contenido = $(e.currentTarget).val();
          $.post(ctxG.rutagral + '/get-parametros-from', 
          { hijos_de: 'hijos_de', dominio: 'tipo_contenido', nombre: tipo_contenido }, (res) => {
            let optsSubTipo = xyzFuns.generaOpciones(res.data, 'nombre', 'descripcion');
            $("[__rg_field=sub_tipo]").html(optsSubTipo);
          })
        })

        /* Cancel Modal*/
        .on('click', "[__cerrar]", () => {
          xyzFuns.closeModal();
        })

        .on('click', "[__save]", () => {
          funs.saveData();
        })
        /* del alert */
        .on('click', "[__alert_msg] .close", (e) => {
          $(e.currentTarget).closest('[__alert_msg]').remove();
        })
        .on('change', "[__archivo_up]", function(){
            let inputFile = $(this);

            let archivo = inputFile[0].files[0];
            // inputFile.attr("title","Archivo SELECCIONADO " + archivo.name);
            // $("[__imagen_label]").html("Imagen: " +  archivo.name );
            // $("#imagen_nueva").val(archivo.name );

            var reader = new FileReader();
            reader.onload = function(event) {
                $("[__imagen_img]").attr('src', event.target.result );
            }
            reader.readAsDataURL(archivo);
        });
    }

    /**
     * Inicializa 
     */
    let init = () => {
      // conT.cargarDatos();
      funs.crearFormulario();
    }
    listen();
    init();
  })
</script>

<?php
    return ob_get_clean();
}
?>