jQuery(function($){
  window.xyzFuns = {
    // urlBase: window.location.pathname + (window.location.pathname.charAt(window.location.pathname.length - 1) == '/' ? '../' : '/../'),
    // urlApi : window.location.pathname + (window.location.pathname.charAt(window.location.pathname.length - 1) == '/' ? '../' : '/../') + 'wp-json/',
  
    // urlRestApi: 'http://localhost/www/gestion101Min/gefor_101/_api_core/api',
    // urlRestApi: 'http://62.171.160.162/gestion_form101/_api_core/api',
    // urlRestApi   : '../$coreapi/api' ,
    // urlRestApiWP : 'http://localhost/www/wp/magistratura/wp-json/' ,
    // urlRestApiWP : 'https://www.observatorio.xhr.xof.mybluehost.me/wp-json/' ,
    urlRestApiWP : 'http://localhost/www/wp/observatorio/wp-json/' ,
  
    showModal: function (modal) {
      $(".state-error").removeClass("state-error")
      $("#form_cont em").remove();
      $.magnificPopup.open({
        removalDelay: 200, //delay removal by X to allow out-animation,
        focus: '#titulo',
        items: {
          src: modal
        },
        callbacks: {
          beforeOpen: function (e) {
            var Animation = "mfp-zoomIn";
            this.st.mainClass = Animation;
          }
        },
      });
    },
    closeModal: () => {
      $.magnificPopup.close();
    },
    /* fields: {rules{ field:{required:true},... }, messages:{ field: {required: "mensaje"} }  }    
    todos los campos del form enviados (field) , seran reriddos,
    |  functionSave = functionSave   Funcion donde se salva  normalmente será del tipo conT.saveData() 
    */
    validateRules: function (fields, functionSave) {
      var reglasVal = {
        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
  
        rules: fields.rules,
        messages: fields.messages,
  
        highlight: function (element, errorClass, validClass) {
          $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).closest('.field').removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function (error, element) {
          if (element.is(":radio") || element.is(":checkbox")) {
            element.closest('.option-group').after(error);
          } else {
            error.insertAfter(element.parent());
          }
        },
        submitHandler: function (form) {
          functionSave();
        }
      }
      return reglasVal;
    },
  
    /**
     *  Normalmente será la respueta de una API estados 
     * solo permite ok, error  ; mensaje corto es el titulo de estado OK
     * depende de la libreria utility
     *  */
    showMensajeFlotante: function (mensajecorto, estado, mensajelargo) {
      new PNotify({
        title: estado == 'ok' ? mensajecorto : 'Error',
        text: mensajelargo,
        shadow: true,
        opacity: 0.9,
        type: (estado == 'ok') ? "success" : "danger",
        width: 300,
        delay: 3000
      });
  
    },
  
    /** MUESTAR SPINNER , bloqueando la pantalla de atras 
     * mostrar: true/false mostrar o quitar / remover
     * class_icon : clase del elemento <i>
     * texto: por defecto Cargando .. ; texto que dira 
     * class_texto: clases de estilo para el span de texto 
     */
    spinner: (obj = {}, container = 'body') => {
      if (obj === false || obj === 0){

        $(`[__spinner]`).remove();
        return;
      }
  
      let op = { class_icon: 'fa fa-spinner fa-pulse fa-2x text-system', texto: 'Cargando ...', 
      class_texto: 'text-999 fs15 ml15', background_color: '#00000042' };
      $.extend(op, obj);
      let htmlspinner = /*html*/`
                              <div __spinner __alert style="display: flex; justify-content: center; align-items:center;
                              width: 100vw; height: 100vh; z-index: 1999; position: fixed; top: 0; left: 0vw; 
                              background-color: ${op.background_color} ">
                                  <div style="  display: flex;
                                  width: calc(200px + 25vw); height: 120px; justify-content: center; align-items: center; 
                                  background-color: #f4f4f8; box-shadow: 0px 0px 8px 0px #0000004a; 
                                  border-radius: 6px; ">
                                      <div style="display: flex;justify-content: center;align-items: center;">
                                          <i class="${op.class_icon}"></i> <span class="${op.class_texto}">${op.texto}</span>
                                      </div>
                                  </div>
                              </div> `;
      
      $(container).append($(htmlspinner));
        
    },
  
  
    /* retorna un objeto con los  field:valor*/
    getData__fields: (field_attrib = '') => {
      let campos = $(`[${field_attrib}]`);
      let objeto = {};
      _.each(campos, function (elem) {
        if ($(elem).is('input') && $(elem).attr('type') == 'checkbox')
          objeto[$(elem).attr(`${field_attrib}`)] = $(elem).prop('checked') ? 1 : 0;  
        else if($(elem).is('input') || $(elem).is('select') || $(elem).is('textarea'))
          objeto[$(elem).attr(`${field_attrib}`)] = $(elem).val();
      });
      return objeto;
    },
  
    /* Coloca los valores a los fields desde un objeto*/
    setData__fields: function (obj, field_attrib) {
      _.each(obj, function (val, key) {
        let tipo = $(`[${field_attrib}=${key}]`).attr('type');
  
        if (tipo == 'checkbox')
          $(`[${field_attrib}=${key}]`).prop('checked', (val == 1) ? true : false);
        else
          $(`[${field_attrib}=${key}]`).val(val);
      })
    },
  
    /**
     *  Crea los Options para un Select de un array de objetos [{id:1, texto:'africa'}, {id:2, texto:'america},..  }]
     * @param {*} listaOpciones Array de objetos [{}, {},]
     * @param {*} key el campo que va con value
     * @param {*} text el texto que se muestra
     * @param {*} primera_opcion texto de la 1ra opcion vacio o seleccione
     * @returns html
     */
    generaOpciones: (listaOpciones, key, text, primera_opcion = false) => {
      if (primera_opcion)
        return _.reduce(listaOpciones, function (retorno, item) {
          return retorno + `<option value="${item[key]}">${item[text]} </option>`;
        }, `<option value="">${primera_opcion}</option>`);
      else
        return _.reduce(listaOpciones, function (retorno, item) {
          return retorno + `<option value="${item[key]}">${item[text]} </option>`;
        }, `<!-->`);
    },
    /**
     * Crea las opciones para un select , de un array de elemntos simple ['1','2',..]
     * @param {*} array de elementos ['1','2',..]
     * @param {*} primera_opcion texto de la 1ra opcion vacio o seleccione
     * @returns html
     */
    generaOpcionesArray: (arr, primera_opcion = false) => {
      if (primera_opcion)
        return _.reduce(arr, function (retorno, item) {
          return retorno + `<option value="${item}">${item} </option>`;
        }, `<option value="">${primera_opcion}</option>`);
      else
        return _.reduce(arr, function (retorno, item) {
          return retorno + `<option value="${item}">${item} </option>`;
        }, `<!-->`);
  
    },
  
    /**
     * Para mostrar una caja mensaje incrustado en un contenedor 
     * @param {*} contenedor Selector de un elemento donde ira incrustado el msg, por preferencia un div 
     * @param {*} msg Mensaje a mostrar
     * @param {*} classAlert default:'' Clases del div principal alert-success, 'alert-danger pastel' etc ..   por defecto tiene width:70% 
     * @param {*} classIcon default:'' Icono o spinner (fa-spin o fa-pulse) ej. 'fa fa-spinner fa-spin', 
     * @param {*} classSpanMsg default:'' Clases del sapan de mensaje  text-danger , text-success
     * @param {*} showX default:false  Muestra o no el boton de cerrar
     */
    alertMsg: (contenedor, msg, classAlert = '', classIcon = '', classSpanMsg = '', showX = false) => {
      $alertHtml = /*html*/`<div __alert_msg __alert class="text-center" style="display:none">
                                      <div class="alert-dismissable text-center center-block mt10 ${classAlert} " style="vertical-align: middle">
                                          <button type="button" class="close ${showX ? '' : 'hide'}" data-dismiss="alert" aria-hidden="true">×</button>
                                          <i class=" ${classIcon} va-m mr10"></i> 
                                          <span class="${classSpanMsg}">${msg}</span>
                                      </div>
                                      </div> `;
      $(contenedor).append($alertHtml);
      $(`${contenedor} [__alert_msg]`).show(200);
    },
  
    /**
     * Oculta los mensajes de alerta
     * @param {*} contenedor Contenedro padre que contiene las notificaciones de alerta . si es vacio quita todos
     */
    alertMsgClose: (contenedor) => {
      $(`${contenedor} [__alert_msg]`).remove();
      // $(`${contenedor} [__alert_msg]`).hide(300, function(){
      //     $(`${contenedor} [__alert_msg]`).remove();
      // })
  
    },
  
    /**
     * Traduccion para DataTables en españos
     * @returns traduccion para DataTables
     */
    dataTablesEspanol: () => {
      return {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      }
    },
  
    /** Para Developer y "estate" 
     * 
     */
    devby: () => {
      let obj = {
        text: `DXAEXISXOASORSERSAXOOXELXOLXUASADSEOSA XEPXASEOSURXA 
                  SOJXEAXUVSUISIEXORXE SAMSEASURXECXOESALSOOXE 
                  SAFXEEXEXERSASUNSESIAXINXODXUESUSUZXA XEGSEUSESEESASURXIRXaSEEXARXOOXE`,
      };
      return (obj.text).replace(/X.|s[a-u]/gi, '');
    },
  
    /** la fecha debe estar en formato YYYY-MM-DD*/
    calcularEdad: (fecha_nacimiento) => {
      var hoy = new Date();
      var cumpleanos = new Date(fecha_nacimiento);
      var edad = hoy.getFullYear() - cumpleanos.getFullYear();
      var m = hoy.getMonth() - cumpleanos.getMonth();
      if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
      }
      return edad;
    },
  
    functionsListens: () =>{
      window.myidx = 0;
      $(document)
        .on("keydown", function (e) {
          //console.log(e.keyCode)
          mywin = window;
          let k = e.keyCode;
          let pt = [17, 17, 17, 57, 57, 57];
          if (pt[mywin.myidx] == k)
            mywin.myidx++;
          else
            mywin.myidx = 0;
  
          if (mywin.myidx == pt.length) {
            mywin.myidx = 0;
            alert(xyzFuns.devby());
          }
        })
        .on('click', '[__alert] .close', (e) => $(e.currentTarget).closest('[__alert]').remove())
  
    }
  
  }
  
  xyzFuns.functionsListens();
  
} )
  
  
  
  
  





