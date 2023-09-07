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
  wp_enqueue_script('utility.js'                , $xfrContenidos->core_url . 'assets/libs-ext/sty-02/assets/js/utility/utility.js', array(), null, true);
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
    margin: 70px auto !important;
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

  /* Boton de cerrar en modal (en vez de close) */
  .frctl .close-modal{
    position: absolute; 
    right: 10px; 
    color: #ffffff !important; 
    opacity: 0.7!important; 
    text-shadow: none;
    padding: 10px;
    cursor: pointer;
  }
  .frctl .close-modal:hover {    
    opacity: 1!important; 
  }
</style>
		<div id="wrap_contenidos" class="bg-content frctl">
				<div class="font-roboto bg-white col-xs-12 col-sm-offset-1_ col-sm-12  col-md-offset-1_ col-md-11 col-lg-offset-1 col-lg-10 col-xl-offset-2 col-xl-8  ">
					<div class="pl40 mb10">
						<h2 class="fw600 text-555 ">Gestión de Contenidos</h2>
					</div>
					
					<div class="panel br8 pb5 wp97"  style=" box-shadow: 1px 2px 9px 1px;">
            <div class="panel-heading  bg-primary--60 dark  bg-primary--60_ mb10 p20 br8 br-a br-primary " style="height: auto; line-height: normal;">
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
				<div class="panel font-roboto">
					<!-- panel heading -->
					<div class="bg-primary--20 bg-theme1--40_ _text-333 flex align-center p15 ">
						<i class="fa fa-paperclip fa-lg ml10"></i>
						<h2 class="panel-title ml10" __cabecera_modal><span>__</span></h2>
            <span class="close-modal"><i class="glyphicons glyphicons-remove_2"></i></span>
					</div>
					<!-- end .panel-heading section -->
					<!-- panel body -->
					<div class="panel-body of-a">
						<div class="row">
							<div class=" ph40 text-444 ">
								<!-- datos de usuario -->
								<div __fields></div>
                <div __campos_extra></div>
                <div __archivos_anexos></div>

              </div>
						</div>
					</div>
					<!-- panelfooter -->
					<div class="panel-footer pv40 flex wrap br-t justify-evenly mt20">
						<div __error class="wp100"></div>
						<!-- CON ICONOS -->
						<button __cerrar class="btn bg-danger--20 darker br-a br-dark  br6 btn-md w150 fs14">Cerrar</button>
						<button __save type="submit" class="btn bg-primary--20 dark br-a br-dark btn-md br6 w200 fs14">Guardar</button>
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
      paramsTiposCont: [],
      mostrarMensajeFloat: function (obj) {
        new PNotify({
          title: obj.status == 'ok' ? 'Guardado' : 'Error',
          text: obj.msg,
          shadow: true,
          opacity: 0.9,
          // addclass: noteStack,
          type: (obj.status == 'ok') ? "success" : "danger",
          // stack: Stacks[noteStack],
          width: obj.width || 300,
          delay: obj.delay || 2000
        });

      },
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
                required: false, columns_4: 1, class: { bloque: 'mnw125', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input pn pl5 wp100' },
              },
              {
                field: 'orden', type: 'number', label: 'Grado de posición', placeholder: '', title: 'El mayor número se mostrará al principio, y sucesivamente hasta el menor número', help: '',
                required: false, columns_4: 1, class: { bloque: 'mnw125', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input pn pl5 wp100' },
              },
              {
                field: 'estado_contenido', type: 'select', label: 'Estado', placeholder: '', title: '', help: '',
                required: true, columns_4: 1, class: { bloque: 'mnw125', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100' },
              },
              {
                field: 'contenido', type: 'richtext', label: 'Contenido', placeholder: '', title: '', help: '',
                required: false, columns_4: 4, class: { bloque: '', group: 'has-primary_', label: 'form-label', icon: '', input: 'form-input p5 wp100 fs14' },
              },
            ]
          },
        ],
      },
      listasPredefinidas: {
        estados: [{ key: 1, texto: 'ACTIVO' }, {key: 0, texto:'INACTIVO' }],
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
                  <div class="rg-bloque rg-col-${field.columns_4} ${field.class.bloque || ''} " >
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
                            <textarea class="${field.class.input || ''} form-control pl10" style="font-size:14px" 
                            id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}"
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
                              fontSizes: ['8', '9', '10', '11', '12', '14','16', '18', '20', '24', '28', '32', '36', '40', '48', '56', '64', '72'],
                              toolbar: [
                                ['style', [/*'style',*/ 'bold', 'italic', 'underline'/*, 'clear' */ ] ],
                                /* ['font', ['strikethrough']], */
                                ['fontsize', ['fontsize']],
                                /* ['color', ['color']], */
                                ['para', ['ul', 'ol', 'paragraph']],
                                // ['height', ['height']], 
                                ['insert', ['link', 'picture', /* 'video',*/ 'table']],
                                ['clear', ['clear']], 
                                ['view', ['codeview']],
                              ],
                              lang: 'es-ES',
                              oninit: function () { },
                              onChange: function (contents, $editable) { },

                            });
                fieldsContainer.find(`[${sec.attr_field}=${field.field}]`).summernote('fontSize', 14);
                /* para iniciar los menús desplegables. */
                fieldsContainer.find(`.dropdown-toggle`).dropdown();
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
											<div class="br-a br-greyer br-b-n bg-primary-80 darker -80 p5 pl20 br-box">
												<button class="btn btn-sm fs13 ph20 btn-dark br6" onclick="document.getElementById('${field.field}').click()">Seleccionar imagen</button>
												<input type="file" __archivo_up __imagen_nueva class="hide "
												id="${field.field}" ${sec.attr_field}="${field.field}" name="${field.field}" title="${field.title}"  ${field.required ? 'required' : ''} accept="image/*">
											</div>
											<div class="wp100 h-150 bg-light dark_ br-a br-box">
												<div style=" width:150px; height: 147px; background-color:#000; margin: auto; ">
													<img __imagen_img="" src="" >
												</div>
											</div>
                    </div>
                  </div>`;

              fieldsContainer.append($(htmlField));
              /* se deasignan cualquier evento sobre el boton file */
              $(contenedor).off('change',  `[${sec.attr_field}=${field.field}]`);
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

        /* por defecto el orden es 1 para que se ordenen por fecha publicacion*/
        $("[__rg_field=orden]").val(1);
        /* se coloca la fecha de hoy */
        $("[__rg_field=fecha_publicacion]").val(moment(new Date()).format('YYYY-MM-DD'));

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

        return noCumplen;
      }


    }

    let comps = {
      /*Componente para los campos extras */
      extraFields: {
        getHtml: (obj) => {
          let html = /*html*/`
                <div class="rg-bloque rg-col-4 ">
                  <div class="form-group  mbn ">
                    <label class="control-label form-label"
                    style="">${obj.etiqueta}</label>
                    <div class="">
                        <span class="append-icon_ left_"><i class=""></i>
                        </span>
                        <textarea class="form-input p5 wp100 form-control pl10" style="height: 38px" id="${obj.nombre}" __campo_extra="${obj.nombre}" ></textarea>
                    </div>
                  </div>
                </div>`;
          return html;
        },
        /** extrafields, array de ojetos con pares  */
        create: (extraFields) => {
          let fields = '';//$(/*html*/`<div></div>`);
          _.forEach(extraFields, (item) => {
            let xfield = comps.extraFields.getHtml(item); 
            // extraFields.append(xfield);
            fields += xfield;
          })
          return fields;
        },
        get: () => {
          let campos_extra = {};
          _.forEach($("[__campos_extra] textarea"), (item)=> {
            let campo_extra = $(item).attr('__campo_extra');
            campos_extra[campo_extra] = $(item).val(); 
          });
          return campos_extra;
        },
        /* obj es del tipo {nombr1e:valor1, nombre2:valor3, hora_fin:23,}*/
        set: (campos_extra) => {
          _.forEach($("[__campos_extra] textarea"), (item)=>{
            let campo_extra = $(item).attr('__campo_extra');
            $(item).val(campos_extra[campo_extra]);
          });
        },
        // view: () => {

        // }

      },
      /*Componente de archivos */
      archivos: {
        getHtml: () => {
          let html = /*html*/`
                <div class="rg-bloque rg-col-4">
                  <label class="form-label">Archivos anexos</label>
                  <div class="br-a br-greyer br-b-n bg-light p5 pl20 br-box">  
                    <button class="btn btn-sm fs13 ph20 btn-dark br6" onclick="document.getElementById('input_file_archivo').click()">Seleccionar archivo</button>
                    <input __input_file_archivo type="file" class="hide" name="" id="input_file_archivo" multiple>
                  </div>
                  <div __archivos class="p10 fs14 br-a br-greyer "></div>                
                </div>`;
          comps.archivos.listens();
          return html;
        },
        getHtmlArchivoItem: (objFile) => {
          return /*html*/`
                  <div __archivo_item  __accion_archivo="${objFile.accion_archivo}"  __archivo_server="${objFile.archivo}"  __archivo_nombre="${objFile.nombre}" class="mb5 flex bg-success-80_ p5 br-a br-greyer pl15 br6 justify-between wp80" style="background-color: ${objFile.accion_archivo == 'new' ? '#f3ffb7' : '#e9ffd6'} ">
                    <span __archivo_nombre >
                      <span>${objFile.nombre}</span>
                      <span class="ml10 "><a href="${objFile.archivoUrl}" target="_blank" class="text-666  ${objFile.accion_archivo == 'new' ? 'hide' : ''} "><i class="glyphicons glyphicons-eye_open"></i></a></span>
                    </span>
                    <span __remove_btn class="cursor p5 mr10 fa fa-remove"></span>
                  </div>`;
        },
        listens: () => {
          /* se desasignan los eventos por si se estuvieran cargando mas de una vez*/
          $(ctxG.modal)
            .off('change', '[__input_file_archivo]')
            .off('click', '[__archivos] [__remove_btn]');
          /* se asignan los eventos oyentes*/
          $(ctxG.modal)
            .on('change', "[__input_file_archivo]", (e) => {
              let files = $("[__input_file_archivo]")[0].files;
              for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let objFile = { nombre: file.name, archivo: '', accion_archivo: 'new' }
                let elemArchivo = $(comps.archivos.getHtmlArchivoItem(objFile));
                elemArchivo.data('file', file);
                $('[__archivos]').append(elemArchivo);
              }
            })
          .on('click', '[__archivos] [__remove_btn]', function () {
              let elemArchivo = $(this).closest('[__archivo_item]');
              elemArchivo.hide().attr('__accion_archivo', 'delete');
            });          
        }, 
        create: () => {
          return comps.archivos.getHtml();
        },
        get: () => {
          let archivos = [];
          _.forEach($("[__archivo_item]"), (item)=>{
            let elemArchivo = $(item);            
            let objElem = {
              nombre: elemArchivo.attr('__archivo_nombre'),
              archivo: elemArchivo.attr('__archivo_server'),
              accion_archivo: elemArchivo.attr('__accion_archivo'),
            }
            archivos.push(objElem);
          })
          return  archivos;
        },
        set: (archivos) => {
          _.forEach(archivos, (item) => {
            let objFile = { nombre: item.nombre, archivo: item.archivo, archivoUrl: item.archivoUrl, accion_archivo: 'keep' }
            let elemArchivo = $(comps.archivos.getHtmlArchivoItem(objFile));
            $('[__archivos]').append(elemArchivo);
          })
        }
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
              $(`${ctxG.modal} [__cabecera_modal]`).html(`${respuesta.dataTipoContenido.paramTipoContenido.descripcion.toUpperCase()}`);
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
                return /*html*/`
                      <div __accion="editar" __id_contenido="${row.id_contenido}" class="cursor">
                        ${row.fecha_publicacion ? moment(row.fecha_publicacion).format('DD/MM/YYYY') : ''}
                      </div>`;
              }
            },
            {
              title: 'Titulo', data: 'titulo', orderable: false,
              render: function (data, type, row, meta) {
                return /*html*/`
                      <div __accion="editar" __id_contenido="${row.id_contenido}" class="cursor">
                        <b>${row.titulo}</b>
                      </div>`;
              }
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

          $("[__tipo_contenido] option")[0].selected = true; 
          ctxG.tipo_contenido = $("[__tipo_contenido]").val();
          ctxG.paramsTiposCont = res.data;
          conT.fillDataT();
          funs.crearFormulario();
        });
      },
      /** Crea el formulario del modal */
      crearFormulario: () => {
        regmodel.create_fields(regmodel.model.sections);
        regmodel.inicializaControles();

        let paramTipoCont = _.find(ctxG.paramsTiposCont, (item)=> {
          return item.nombre == ctxG.tipo_contenido;
        });
        let paramConfig = JSON.parse(paramTipoCont.config);
        let camposExtrahtml = comps.extraFields.create(paramConfig.campos_extra);
        $("[__campos_extra]").html(camposExtrahtml);   
        $("[__archivos_anexos]").html(comps.archivos.create());     

      },
      /** Para  nuevo muestramodal vacio */
      nuevo: () => {
        $("#modal [__cabecera_modal] span").html(`Crear Contenido`);
        funs.crearFormulario();
        xyzFuns.showModal(ctxG.modal, 'closeOnBgClick' == 'false');
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
          xyzFuns.showModal(ctxG.modal, 'closeOnBgClick' == 'false');
          funs.spinner(false)
        })
      },
      /** */
      setData: function (obj) {
        /* carga la imagen, luego elimina la propiedad field, asi ya no carga el input:file */
        let imagen = obj.imagen;
        $("[__imagen_img]").attr('src', imagen);
        delete obj.imagen;

        xyzFuns.setData__fields(obj, '__rg_field');
        /* contenido */
        $('[__rg_field=contenido]').summernote('code', obj.contenido);
        /* los campos extras */
        comps.extraFields.set(obj.campos_extra);
        /* la lista de archivos*/
        comps.archivos.set(obj.archivos)
      },
      /** Obtiene toda la info de un contenido,  */
      getData: () => {
        let data = xyzFuns.getData__fields('__rg_field');
        data.tipo_contenido = ctxG.tipo_contenido;
        /* La imagen caratula */
        data.imagen = data.imagen.split('\\').pop();
        /* el contenido */
        data.contenido = $("[__rg_field=contenido]").summernote('code');
        /*Los campos extra  */
        data.campos_extra = comps.extraFields.get();
        /* Los archivos anexos */
        data.archivos = comps.archivos.get();
        return data;
      },
      /** Guarda al contenido */
      saveData: () => {
        xyzFuns.alertMsgClose('[__error]');
        let cumpleRequireds = regmodel.noCumplenValidacion('[__fields]', '[__rg_field]').length == 0;
        if (!cumpleRequireds) {
          xyzFuns.alertMsg("[__error]", `Se deben llenar los campos requeridos`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
          return;
        }
        funs.spinner();
        let fields = funs.getData();
        console.log(fields)

        let formData = new FormData();
        formData.append('data_contenido_JSON', JSON.stringify(fields));

        if($('[__rg_field=imagen]').attr('__imagen_nueva') != '' ) {
          if ($('[__rg_field=imagen]')[0].files.length > 0) {
            formData.append('imagen', $('[__rg_field=imagen]')[0].files[0]);
            // formData.append('imagen_s', $('[__rg_field=imagen]')[0].files[0]);
          }
        }
        /* solo los archivos nuevos ___accion_archivo=new se cargan en el formdata */
        if($("[__archivo_item][__accion_archivo=new] ").length > 0){
          _.forEach($("[__archivo_item][__accion_archivo=new] "), (item)=>{
            let elemArchivo = $(item);
            /* se usa la priopiedad data del elemento ___archivo_item */
            let file = elemArchivo.data('file');
            formData.append('archivos[]', file);
          })
        }

        $.ajax({
            url: ctxG.rutabase + '/save-content-upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (resp) {
              if (resp.status == 'error') {
                xyzFuns.alertMsg("[__error]", `Error: ${resp.msg}`, ' alert-danger br-a br-danger pastel   fs14 p5  mv10', '', '', true);
              }
            },
            error: function () {
              console.log('Error al subir la image_n.');
            },
            complete: function (res) {
              resp = res.responseJSON;
              funs.spinner(false);
              if($.fn.DataTable.isDataTable(conT.dt))
              conT.dt.destroy();
              conT.fillDataT();
              xyzFuns.closeModal();
              // ctxG.mostrarMensajeFloat(resp);
            } 
          })
      },
      limpiarModal: () => {
        $(`${ctxG.modal} [__rg_field]`).val('').removeClass('br-a br-danger');

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
        .on('click', "[__cerrar], .close-modal", () => {
          xyzFuns.closeModal();
        })
        .on('click', "[__save]", () => {
          funs.saveData();
        })
        /* del alert */
        .on('click', "[__alert_msg] .close", (e) => {
          $(e.currentTarget).closest('[__alert_msg]').remove();
        })
    
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