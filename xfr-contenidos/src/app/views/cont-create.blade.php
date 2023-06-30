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
		margin: 100px auto;
	}

  /* Oculta la cabecera de la tabla el TH */
  /* .frctl .dataTables_wrapper .dataTables_scrollHead {
    display: none;
  } */

  /* Ocultan los bordes laterales de las celdas y de la tabbla,
   solo viusaliza las lineas de abajo */
  .frctl .dataTables_wrapper .dataTables_scrollBody td {
    border-left: none !important;
    border-right: none !important;
    border-top: none !important;
    border-bottom: 1px solid rgb(0, 0, 0, 0.1);
  }
  .frctl .dataTables_wrapper .dataTables_scrollHeadInner{
    margin-top: 10px !important;
  }

  .frctl .panel-body {
    border: none;
  }

  .frctl table.dataTable {
    border-left: none !important;
    border-right: none !important;
    /* border-top: 1.3px solid rgb(0, 0, 0, 0.3) !important; */
    /* margin-top: 10px !important; */
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
				<div class=" bg-white col-xs-12 col-sm-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-9 col-xl-offset-2 col-xl-8  ">
					<div class="pl40 mb10">
						<h2 class="fw600 text-555 ">Gestión de Contenidos</h2>
					</div>
					
					<div class="panel br8 pb5"  style="width: 97%;    box-shadow: 1px 2px 9px 1px;">
            <div class="panel-heading  bg-dark dark  bg-primary--60_ mb10 p20 br8 br-a br-primary " style="height: auto; line-height: normal;">
							<div class="text-white-dark mtn mbn fs16 flex align-center justify-evenly wrap gap-10">
								<i class="fa fa-paperclip fa-lg grow-1"></i> 
                <span class="ml10 grow-1" __cabecera_dt>Tipo Contenido </span>
                <select class="grow-10 br6 fs18 fw600 text-444" __tipo_contenido  style="background-color: rgb(255 255 255 / 80%);" ></select>
							</div>
						</div>

						<div class="panel-body ph5 fs16 text-333">
              <div class="mb10 br-b ">
                <button __accion="nuevo" class="btn btn-sm bg-success--20  m5 br4 ph30 br-a br-dark">
                  <i class="fa fa-plus mr10"></i><span>Agregar</span> 
                </button>
              </div>
              <div class="fs14">
								<table __data_list id="dataT" class=" hover" style="width:100%; min-width: 600px;" >
								</table>
							</div>
						</div>
					</div>
          
				</div>

			<!-- -----------------------------------------          Modal  --------------------------------------------------- -->
			<div id="modal" class="frctl white-popup-block popup-basic mfp-with-anim mfp-hide">
				<div class="panel">
					<!-- panel heading -->
					<div class="bg-primary--20 _text-333 flex align-center p15 ">
						<i class="fa fa-paperclip fa-lg ml10"></i>
						<h2 class="panel-title ml10" __cabecera_modal><span>__</span></h2>
            <span class="close btnCloseModal"><i class="glyphicons glyphicons-remove_2"></i></span>
					</div>
					<!-- end .panel-heading section -->
					<!-- panel body -->
					<div class="panel-body of-a">
						<div class="row">
							<div class=" ph40 text-555 ">
								<!-- datos de usuario -->
								<div __fields></div>
                <div __campos_extra></div>

                <div class="rg-bloque rg-col-4">
                  <label class="form-label">Archivos anexos</label>
                  <div class="br-a br-greyer br-b-n bg-light p5 pl20 br-box">  
                    <button class="btn btn-sm btn-dark br6" onclick="document.getElementById('input_file_archivo').click()">Seleccionar archivo</button>
                    <input __input_file_archivo type="file" class="hide" name="" id="input_file_archivo">
                  </div>
                  <div __archivos_anexos class="p10 fs14 br-a br-greyer "></div>                
                </div>
                <!-- <textarea name="" id="" __rg_field="contenido"  cols="30" rows="10"></textarea> -->
								<hr>
              </div>
						</div>
					</div>
					<!-- panelfooter -->
					<div class="panel-footer flex wrap justify-evenly">
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
      tipo_contenido: '',
      paramsTipoCont: [],
      fileList: []
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
            title_section: 'Datos del contenido',
            text_section: '',
            class: { text: "mb10", section: "mb20", title: "" },
            attr_field: '__rg_field',
            fields: [
              { field: 'id_contenido', type: 'hidden', },
              {
                field: 'titulo', type: 'text', label: 'Título', placeholder: '', title: '', help: '',
                required: true, columns_4: 4, class: { bloque: '', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'imagen', type: 'img', label: 'Imagen', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'resumen', type: 'textarea', label: 'Resumen', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'fecha_publicacion', type: 'date', label: 'Fecha de Publicación', placeholder: '', title: '', help: '',
                required: false, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input pn pl5 wp100' },
              },
              {
                field: 'orden', type: 'number', label: 'Grado de posición', placeholder: '', title: 'El mayor número se mostrará al principio, y sucesivamente hasta el menor número', help: '',
                required: false, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input pn pl5 wp100' },
              },
              {
                field: 'estado_contenido', type: 'select', label: 'Estado', placeholder: '', title: '', help: '',
                required: true, columns_4: 2, class: { bloque: 'mnw300', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'contenido', type: 'richtext', label: 'Contenido', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
            ]
          },
        ],
      },
      listasPredefinidas: {
        estados: [{ key: 1, texto: 'ACTIVO' }, { key: 0, texto: 'INACTIVO' }],
      },
      /** Crea toda todas las secciones y sus filds */
      create_fields: (sections) => {
        _.forEach(sections, (sec) => {
          let contenedor = $(sec.html_parent);
          /* se vacia el contenedor por si tuviera elementos*/
          contenedor.html('');
          /* Cabecera de cada Seccion Titulo y Texto*/
          let sectionContainer = $(/*html*/`<div __section_container class="${sec.class.section}"></div>`);
          sectionContainer.append($(/*html*/`<h2  __title_section class="${sec.class.title}" style="">${sec.title_section}</h2>`));
          sectionContainer.append($(/*html*/`<div __text_section class="fs13 ${sec.class.text}">${sec.text_section}</div>`));

          let fieldsContainer = $(/*html*/`<div __rg_fields_container class="rg-flex-content"></div>`);
          _.forEach(sec.fields, (field) => {

            field.class = (field.class) ? field.class : {};
            /* Todos los fields estan para reaccionar al Grid , para volver al estilo clasico descomentar form-horizontal y form-group. etc*/

            if (field.type == 'hidden') {
              let htmlField = /*html*/`
                                    <div class="section hidden">
                                            <input class="hidden" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}" >
                                    </div>`
              fieldsContainer.append($(htmlField)); 
            }
            if (field.type == 'text' || field.type == 'number' || field.type == 'date' || field.type == 'password' || field.type == 'email') {
              let htmlField =
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} ${field.class.bloque || ''} " >
                    <div class="form-group  ${field.class.group || ''}  mbn">
                        <label  class="control-label ${field.class.label || ''}" for="${field.field}"
                        style="">${field.label}</label>
                        <div    class="">
                            <span class="append-icon_ left_"><i class="${field.class.icon}"></i>
                            </span>
                            <input type="${field.type}" class="${field.class.input || ''} form-control pl10 br-box" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            placeholder="${field.placeholder}" title="${field.title}" ${field.required ? 'required' : ''} autocomplete="off"  >
                            <em class="fs12 text-dark block ${field.class.em || ''} ">${field.help}</em>
                        </div>
                    </div>
                  </div>`;
                fieldsContainer.append($(htmlField));
            }
            if (field.type == 'select') {
              let htmlField =
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''}  ">
                    <div class="form-group  ${field.class.group || ''} mbn ">
                        <label class="control-label ${field.class.label}" for="${field.field}"
                        style="">${field.label}</label>
                        <div class="">
                            <span class="append-icon_ left_"><i class=""></i>
                            </span>
                            <select class="${field.class.input || ''} form-control pl10 br-box" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            title="${field.title}"  ${field.required ? 'required' : ''} ></select>
                            <em class="fs12 text-dark block">${field.help}</em>
                        </div>
                    </div>
                  </div>`;
              fieldsContainer.append($(htmlField));
            }
            if (field.type == 'textarea' || field.type == 'richtext') {
              let htmlField =
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''}  ">
                    <div class="form-group  ${field.class.group || ''} mbn ">
                        <label class="control-label ${field.class.label}" for="${field.field}"
                        style="">${field.label}</label>
                        <div class="">
                            <span class="append-icon_ left_"><i class=""></i>
                            </span>
                            <textarea class="${field.class.input || ''} form-control pl10" id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            title="${field.title}"  ${field.required ? 'required' : ''} ></textarea>
                            <em class="fs12 text-dark block">${field.help}</em>
                        </div>
                    </div>
                  </div>`;

              fieldsContainer.append($(htmlField));

              if (field.type == 'richtext') {
                fieldsContainer.find(`[${sec.attr_field}=${field.field}]`).summernote({
                              height: 100, //set editable area's height
                              minHeight: 100,
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
              }
            }
            if (field.type == 'checkbox') {
              let htmlField =
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} " >
                    <div class="form-group  ${field.class.group || ''}  mbn">
                        <label  class="control-label ${field.class.label || ''}" for="${field.field}"
                        style="">${field.label}</label>
                        <div    class="">
                            <input type="${field.type}" class="${field.class.input || ''} form-control pl10 " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
                            placeholder="${field.placeholder}" title="${field.title}" ${field.required ? 'required' : ''} autocomplete="off"  >
                            <em class="fs12 text-dark block ${field.class.em || ''} ">${field.help}</em>
                        </div>
                    </div>
                  </div>`;
              fieldsContainer.append($(htmlField));
            }
            if (field.type == 'img') {
              let htmlField =
                  /*html*/`
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} ">
                    <div class="form-group ${field.class.group || ''} mbn ">
                        <label class="control-label ${field.class.label}" for="${field.field}" style="">${field.label}</label>
                        <input type="file" __archivo_up __imagen_guardada __imagen_nueva class="p5 form-control pl10 " id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}" title="${field.title}"  ${field.required ? 'required' : ''} accept="image/*">
                        <div class="wp100 h-150 bg-light br-a br-box">
                          <div style=" width:150px; height: 147px; background-color:#000; margin: auto; ">
                          <img __imagen_img="" src="" >
                        </div></div>
                    </div>
                  </div>`;

              fieldsContainer.append($(htmlField));

              /* define el evento listener change al cambiar imagen previzualiza la imagen en el cuadro, tambien coloca el nombre de la imagen en al atributo imagen_nueva */
              $(contenedor).on('change', `[${sec.attr_field}=${field.field}]`, function () {
                let inputFile = $(this);
                let archivo = inputFile[0].files[0];
                // inputFile.attr("title","Archivo SELECCIONADO " + archivo.name);
                // $("[__imagen_label]").html("Imagen: " +  archivo.name );
                $(`[${sec.attr_field}=${field.field}]`).attr('__imagen_nueva', archivo.name);

                let reader = new FileReader();
                reader.onload = function (event) {
                  $(`[${sec.attr_field}=${field.field}]`).closest('.rg-bloque').find("[__imagen_img]").attr('src', event.target.result);
                }
                reader.readAsDataURL(archivo);
              });
            }

          });
          sectionContainer.append(fieldsContainer);
          $(contenedor).append(sectionContainer);
        })

      },
      /**Iniciañizalos Selects */
      inicializaControles: () => {
        let optsEstados = xyzFuns.generaOpciones(regmodel.listasPredefinidas.estados, 'key', 'texto');
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

      fillDataT: function () {
        funs.spinner();
        /* Aqui se configura el DT y se le asigna al Contenedor*/
        conT.dt = $(ctxG.dataTableTarget).DataTable({
          processing: true,
					serverSide: true,
          // autoWidth: true,
          // info:true,
          scrollX: true,
          className: 'fs-10',
          lengthMenu: [ 10, 20, 50 ],
					ajax: {
						url: `${ctxG.rutabase}/get-contents`,
						type: "POST",
            data: function (data) {
              data.tipo_contenido = ctxG.tipo_contenido;
              data.estado_contenido = "todos";
						},
            complete: (res) => {
              ctxG.dataList = conT.dt.rows().data();
              /* para obtener la data , en la seccion complete se encuentra en la propiedad de responseJSON*/
              let respuesta = res.responseJSON;              
              // $('[__cabecera_dt]').html(`${respuesta.data_tipo_contenido.param_tipo_contenido.descripcion.toUpperCase()} `);
              $(`${ctxG.modal} [__cabecera_modal]`).html(`${respuesta.data_tipo_contenido.param_tipo_contenido.descripcion.toUpperCase()}`);
              funs.spinner(0);
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
            { title: 'vacio', orderable: false, visible: false, render: () => { return '' } },
            {
              title: '', data: 'estado_contenido', orderable: true,
              render: function (data, type, row, meta) {
                let html = /*html*/`
                      <div __accion="editar" __id_contenido="${row.id_contenido}" class="cursor">
                        <img src='${row.imagen_sm}' alt="" style="width:100%; max-width: 200px; min-width: 130px" >
                        <span class="badge ${row.estado_contenido == 1 ? 'bg-success--20' : 'bg-danger--20'}">${row.estado_contenido == 1 ? '' : 'Inactivo'}</span>
                      </div>`;
                return html;
              }
            },
            {
              title: 'Publicación', data: 'fecha_publicacion', orderable: true,
              render: function (data, type, row, meta) {
                return /*html*/`${moment(row.fecha_publicacion).format('DD/MM/YYYY')}`;
              }
            },
            {
              title: 'Titulo', data: 'titulo', orderable: false,
            },
            {
              title: 'Resumen', data: 'resumen', orderable: false,
            },
            {
              title: 'Orden', data: 'orden', className: 'text-center', orderable: true,
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
      /** Inicia el combo con tipos de contenidos  obtiene el dataset del primero*/
      iniciaTiposContenidos: () => {
        $.post(ctxG.rutagral + '/get-parametros-from', {dominio: 'tipo_contenido'}, res =>{
          let optsTipoContenido = xyzFuns.generaOpciones(res.data, 'nombre', 'descripcion');
          $("[__tipo_contenido]").html(optsTipoContenido);

          $("[__tipo_contenido] option")[4].selected = true; 
          ctxG.tipo_contenido = $("[__tipo_contenido]").val();
          ctxG.paramsTipoCont = res.data;
          conT.fillDataT();
          funs.crearFormulario();
        });
      },
      /** Crea el formulario del modal */
      crearFormulario: () => {
        regmodel.create_fields(regmodel.model.sections);
        regmodel.inicializaControles();

        $("[__campos_extra]").html('');
        let paramTipoCont = _.find(ctxG.paramsTipoCont, (item)=> {
          return item.nombre == ctxG.tipo_contenido;
        });
        let paramConfig = JSON.parse(paramTipoCont.config);
        _.forEach(paramConfig.campos_extra, (item) => {
          let html = /*html*/`
                  <div class="rg-bloque rg-col-4 ">
                    <div class="form-group  mbn ">
                        <label class="control-label form-label"
                        style="">${item.etiqueta}</label>
                        <div class="">
                            <span class="append-icon_ left_"><i class=""></i>
                            </span>
                            <textarea class="form-input p5 wp100 form-control pl10" style="height: 38px" id="${item.nombre}" __campo_extra="${item.nombre}" ></textarea>
                        </div>
                    </div>
                  </div>`;
          $("[__campos_extra]").append(html);
        })
        

      },
      /** Para  nuevo muestramodal vacio */
      nuevo: () => {
        $("#modal [__cabecera_modal] span").html(`Crear Contenido`);
        funs.crearFormulario();
        xyzFuns.showModal(ctxG.modal);
      },
      /** MuestraModal con datos del contenido  */
      editar: (id) => {
        let id_contenido = id;
        funs.spinner();
        funs.crearFormulario();
        $.post(ctxG.rutabase + '/get-content', { id_contenido: id_contenido }, (resp) => {
          let data = resp.data;
          funs.setData(data);
          $("#modal [__cabecera_modal] span").html(`Modificar Contenido`);
          xyzFuns.showModal(ctxG.modal);
          funs.spinner(false)
        })
      },
      /** Obtiene toda la info de un contenido,  */
      getData: () => {
        let data = xyzFuns.getData__fields('__rg_field');
        data.imagen = data.imagen.split('\\').pop();
        data.contenido = $("[__rg_field=contenido]").summernote('code');
        data.tipo_contenido = ctxG.tipo_contenido;

        let campos_extra = {};
        _.forEach($("[__campos_extra] textarea"), (item)=> {
          let campo_extra = $(item).attr('__campo_extra');
          campos_extra[campo_extra] = $(item).val(); 
        })
        data.campos_extra = campos_extra;

        data.archivos = [];
        _.forEach(ctxG.fileList, (file)=>{
          data.archivos.push(file.name);
        })
        return data;
      },
      /** */
      setData: function (obj) {
        let imagen = obj.imagen;
        $("[__imagen_img]").attr('src', imagen);
        delete obj.imagen;

        xyzFuns.setData__fields(obj, '__rg_field');

        $('[__rg_field=contenido]').summernote('code', obj.contenido);

        _.forEach($("[__campos_extra] textarea"), (item)=>{
          let campo_extra = $(item).attr('__campo_extra');
          $(item).val(obj.campos_extra[campo_extra]);
        });


      },
      /** Guarda al contenido */
      saveData: () => {
        xyzFuns.alertMsgClose('[__error]');
        let cumpleRequireds = regmodel.noCumplenValidacion('[__fields]', '[__rg_field]').length == 0;
        if (!cumpleRequireds) {
          // xyzFuns.alertMsg("[__error]", `Se deben llenar los campos requeridos`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
          // return;
        }

        funs.spinner();
        let obj = funs.getData();
        console.log(obj)
        $.post(ctxG.rutabase + '/save-content', obj , function (resp) {
          if (resp.status == 'error') {
            xyzFuns.alertMsg("[__error]", `Error: ${resp.msg}`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
          }
          // obj.id_contenido ? conT.refreshRow(resp.data) : conT.refreshDataT();
          // conT.refreshDataT();

          /* Se verifica si estan cargados y se envian los archivos */
          let formData = new FormData();
          formData.append('tipo_contenido', obj.tipo_contenido);
          if($('[__rg_field=imagen]').attr('__imagen_guardada') != $('[__rg_field=imagen]').attr('__imagen_nueva') ){

            if ($('[__rg_field=imagen]')[0].files.length > 0) {
              formData.append('imagen', $('[__rg_field=imagen]')[0].files[0]);
              formData.append('imagen_s', $('[__rg_field=imagen]')[0].files[0]);
            }
          }
          
          if(ctxG.fileList.length > 0){
            _.forEach(ctxG.fileList, (file) => {
              formData.append('archivos[]', file);
            })
          }
          
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
              // funs.spinner(false);
            },
            error: function () {
              console.log('Error al subir la image_n.');
              // funs.spinner(false);
            },
            complete: function () {
              funs.spinner(false);
              if($.fn.DataTable.isDataTable(conT.dt))
                conT.dt.destroy();
              conT.fillDataT();
              xyzFuns.closeModal();
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

        /* Quita las clases de error en todos los campos requeridos  */
        $("[required]").removeClass(regmodel.model.classError);
        xyzFuns.alertMsgClose('[__error]');

        ctxG.fileList = [];
        $("[__archivos_anexos]").html('');

      },
      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },
    }

    //-------------------- Listeners  --------------------------------

    let listen = () => {
      /* DEL CONTENEDOR */
      $(ctxG.content)
        /** Change sobre el combo tipo_contenido*/
        .on('change', '[__tipo_contenido]', (e) => {
          ctxG.tipo_contenido = $(e.currentTarget).val();
          if($.fn.DataTable.isDataTable(conT.dt))
            conT.dt.destroy();
          conT.fillDataT();
          funs.crearFormulario();
        })
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
        /* Cancel Modal*/
        .on('click', "[__cerrar], .close", () => {
          xyzFuns.closeModal();
        })
        .on('click', "[__save]", () => {
          funs.saveData();
        })
        /* del alert */
        .on('click', "[__alert_msg] .close", (e) => {
          $(e.currentTarget).closest('[__alert_msg]').remove();
        })

        .on('change', "[__input_file_archivo]", function(){
          var files = $(this)[0].files;
          for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileSize = file.size;
            var listItem = $(/*html*/`
                <div __archivo_item class="mb5 flex justify-between wp80">
                  <span>${fileName}</span>
                  <span __remove_btn class="cursor p5 mr10 fa fa-remove"></span>
                </div>`);

            listItem.data('file', file);
            ctxG.fileList.push(file);
            $('[__archivos_anexos]').append(listItem);
          }
        })
        .on('click', '[__archivos_anexos] [__remove_btn]', function(){
          var listItem = $(this).closest('[__archivo_item]');
          var file = listItem.data('file');
          ctxG.fileList.splice(ctxG.fileList.indexOf(file), 1);
          listItem.remove();
        });
    }

    let init = (() => {
      listen();
      funs.iniciaTiposContenidos();
    })();
  })
</script>

<?php
    return ob_get_clean();
}
?>