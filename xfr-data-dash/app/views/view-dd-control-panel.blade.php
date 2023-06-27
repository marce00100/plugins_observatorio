<?php
function get_view_dd_control_panel($vars) {
// wp_enqueue_style('select2_sty'       , $vars['core_url'] . 'assets/libs-ext/select2-4.1.0/dist/css/select2.min.css');    
// wp_enqueue_style('datatable_sty'     , $vars['core_url'] . 'assets/libs-ext/datatables-1.10.25/css/jquery.dataTables.min.css');    
// wp_enqueue_style('admin_form_sty'    , $vars['core_url'] . 'assets/libs-ext/sty-02/assets/admin-tools/admin-forms/css/admin-forms.css');
// wp_enqueue_style('theme_'            , $vars['core_url'] . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme.css', array(), '1.0.4', 'all');
wp_enqueue_style('theme_lite'           , $vars['core_url'] . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme_lite.css', array(), '1.0.3', 'all'); 

wp_enqueue_script('xcod_lodash'         , $vars['core_url'] . 'assets/libs-ext/lodash.min.js', array(), null, true);
// wp_enqueue_script('select2_js'       , $vars['core_url'] . 'assets/libs-ext/select2-4.1.0/dist/js/select2.min.js', array(), null, true);
// wp_enqueue_script('datatables'       , $vars['core_url'] . 'assets/libs-ext/datatables-1.10.25/js/jquery.dataTables.min.js', array(), null, true);

ob_start(); ?>

<style>
    .frctl .field{
        font-size: 15px !important;
        margin-top: 10px;

    }

    .frctl .field input, 
    .frctl .field label{ 
        padding: 5px !important;
        
    }

    .frctl .menu-contenedor{
        padding: 15px;
        font-size: 14px;
        color: #d5d5d5;
        background-color: #526c00;
    }

    .frctl .menu-item{
        padding: 5px 25px;
        cursor: pointer;
        margin: 5px 1px;
        border-bottom: 4px solid transparent;
    }
    .frctl .menu-item:hover{
        color : white;
    }
    .frctl .menu-activo{
        color: white;
        border-bottom: 4px solid #a9df00;
    }

</style>

<!-- ------------- Control Panel-------------------------------------------->
<div id="dd_controlpanel" __dd_control_panel class="frctl" style="display: none;"> 
    <div class="menu-contenedor">
        <div style="width: 470px; margin: 0 auto;">
            <span class="menu-item" __dd_menu_item="cargar">Cargar Archivo</span>
            <span class="menu-item" __dd_menu_item="eliminar">Eliminar datos</span>
            <span class="menu-item" __dd_menu_item="exportar">Exportar</span>
        </div>

    </div> 
    <div  __dd_section_=panel_upload_excel  class="mt50 ph30 col-lg-9 col-lg-offset-1 col-xs-12"  >       
            
        <div class="" __importar __dd_section=cargar style="display: none;">
            <h2>Subir Archivo Excel Consolidado de Estadisticas</h2>
            <div class="" style="height: 20px;"></div>
            <div class="mv20 text-center">
                <input type=file id="up_excel"  __file_to_upload  style="width: 100%">
            </div>

            <div class="form-horizontal row field">
                <div class="form-group ">
                    <label class="col-xs-4 control-label " for="num_hoja">Número de Hoja</label>
                    <div class="col-xs-4 col-md-2">
                        <span class="append-icon left"><i class=""></i>
                        </span>
                        <input type="number" class="form-control pl30 " id="num_hoja"
                            __field_import="num_hoja" name="num_hoja" placeholder="" title="Colocar el Numero de Hoja o Libro en el que están los datos para importar del archivo excel."
                            required style="height:auto;">
                    </div>
                </div>
            </div>
            <div class="form-horizontal row field">
                <div class="form-group ">
                    <label class="col-xs-4 control-label " for="num_fila">Fila donde empiezan los datos</label>
                    <div class="col-xs-4 col-md-2">
                        <span class="append-icon left"><i class=""></i>
                        </span>
                        <input type="number" class="form-control pl30 " id="num_fila_start"
                            __field_import="num_fila_start" name="num_fila_start" placeholder="" title="Colocar el Numero de fila donde empiezan los datos."
                            required style="height:auto;">
                    </div>
                </div>
            </div>
            <div class="form-horizontal row field">
                <div class="form-group ">
                    <label class="col-xs-4 control-label " for="num_fila_end">Fila donde terminan los datos</label>
                    <div class="col-xs-4 col-md-2">
                        <span class="append-icon left"><i class=""></i>
                        </span>
                        <input type="number" class="form-control pl30 " id="num_fila_end"
                            __field_import="num_fila_end" name="num_fila" placeholder="" title="Colocar el Numero de fila donde terminan los datos."
                            required style="height:auto;">
                    </div>
                    <em style="font-size: 13px;">Si no coloca, recorrerá las filas hasta el final </em>
                </div>
            </div>
        </div>
        <div class="" __importar __dd_section=eliminar style="display: none;">            
            <h2>Eliminar regitros de la Base de Datos</h2>
            <div class="" style="height: 20px;"></div>

            <em style="font-size: 14px;">Colocar el nombre del archivo excel completo, desde donde se realizó la importación de datos.
            <br> Todos los registros vinculados con ese archivo serán eliminados. </em>
            <div class="form-horizontal row field">
                <div class="form-group ">
                    <label class="col-xs-3 control-label " for="num_hoja">Nombre del archivo</label>
                    <div class="col-xs-8 ">
                        <span class="append-icon left"><i class=""></i>
                        </span>
                        <input type="text" class="form-control pl30 " id="num_hoja"
                            __field_eliminar="nombre_archivo" name="num_hoja" placeholder="" title="Colocar el Numero de Hoja o Libro en el que están los datos para importar del archivo excel."
                            required style="height:auto;">
                    </div>
                </div>
            </div>
        </div>
        <div class="" __importar __dd_section="exportar" style="display: none;">            
            <h2>Exportar informacion a archivo excel</h2>

            <div class="" style="height: 20px;"></div>
            <em style="font-size: 14px;">Se Exportará la informacion de la base de datos en un archivo tabular de Excel </em>
            <div class="form-horizontal row field">
                <div class="form-group ">
                    <label class="col-xs-3 control-label " for="num_hoja">Parámetros</label>
                    <div class="col-xs-8 ">
                        <span class="append-icon left"><i class=""></i>
                        </span>
                        <input type="text" class="form-control pl30 " id="num_hoja"
                            __field_import="nombre_archivo" name="num_hoja" placeholder="" title="Colocar el Numero de Hoja o Libro en el que están los datos para importar del archivo excel."
                            required style="height:auto;">
                    </div>
                </div>
            </div>
        </div>

        <div __notificaciones></div>
        <hr>
        <div class="mt10">
            <div class="text-center mb10 p10">
                <button __dd_accion="upload_file" __dd_section="cargar"  style="display:none" class="btn btn-success"> Cargar y validar excel <i class="fa fa-cloud-upload"></i></button>

                <button __dd_accion="delete_file" __dd_section="eliminar" style="display:none" class="btn btn-danger" title="Si este archivo ya fue cargado, se eliminaran todos los registros con el nombre de este archivo"> 
                    Eliminar Registros <i class="fa fa-trash-o"></i>
                </button>

                <button __dd_accion="export" __dd_section="exportar" style="display:none" class="btn btn-primary"> Exportar Excel <i class="fa fa-file-excel-o"></i></button>
            </div>
        </div>
        
        

    </div>
</div>

<!-- 
<div class="" __importar>
    <h3>Eliminar regitros de la Base de Datos</h3>

    <em style="font-size: 14px;">Colocar el nombre del archivo excel completo, desde donde se realizó la importación de datos.
    <br> Todos los registros vinculados con ese archivo serán eliminados. </em>
    <div class="form-horizontal row field">
        <div class="form-group ">
            <label class="col-xs-4 control-label " for="num_hoja">Nombre del archivo</label>
            <div class="col-xs-8 ">
                <span class="append-icon left"><i class=""></i>
                </span>
                <input type="text" class="form-control pl30 " id="num_hoja"
                    __field_eliminar="nombre_archivo" name="num_hoja" placeholder="" title="Colocar el Numero de Hoja o Libro en el que están los datos para importar del archivo excel."
                    required style="height:auto;">
            </div>
        </div>
    </div>
   
</div>
.on('click', "[__dd_menu_item]", function(e){
    /* Quita todas clases activo y lo adiciona sobre el que se hizo click*/
    $(".frctl .menu-contenedor .menu-item").removeClass('menu-activo') 
    $(e.currentTarget).addClass('menu-activo');

    /*oculta todas las secciones y muestra la del menu*/
    $("[__dd_section]").css({display:'none'});
    let menu = $(e.currentTarget).attr('__dd_menu_item');
    $(`[__dd_section=${menu}]`).show(300).fadeIn(300);

}); -->

<script>
    jQuery(function ($) {

        let ddMain = {
            urlApi: xyzFuns.urlRestApi + 'dd/',
            errores: [],

            /** Realiza la carga del excel, verifica, e inserta los datos a la tabla stage*/
            cargar_archivo: () => {
                if ($("[__file_to_upload]").val().length > 0) {
                    let obj = xyzFuns.getData__fields('__field_import');                    
                    console.log(obj);

                    let archivo = ddFuns.obtenerArchivo("[__file_to_upload]");
                    let formData = new FormData();
                    formData.append('archivo', archivo);
                    formData.append('num_hoja', obj.num_hoja); 
                    formData.append('num_fila_start', obj.num_fila_start); 
                    formData.append('num_fila_end', obj.num_fila_end); 
                    formData.append('nombre_archivo', obj.nombre_archivo); 

                    /* Comprueba si se esta mandando del alert --el archivo ya fue cargado  field_import=continuar_carga */
                    if(obj.continuar_carga)
                        formData.append('continuar_carga', obj.continuar_carga );

                    xyzFuns.alertMsgClose('[__notificaciones]');
                    xyzFuns.alertMsg('[__notificaciones]', 'Cargando Archivo ...', 'mt40', 'fa fa-pulse fa-3x fa-spinner text-system', 'colocar_span_class', false);
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: ddMain.urlApi + 'cargar-archivo',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 6000000,
                        success: function (res) {
                            xyzFuns.alertMsgClose('[__notificaciones]');
                            if(res.status == 'error' )
                                xyzFuns.alertMsg('[__notificaciones]', res.msg, 'pw80 alert alert-danger', 'fas fa-exclamation-triangle fa-2x', '', true);
                            if(res.status == 'alert')
                                xyzFuns.alertMsg('[__notificaciones]',  
                                    /*html*/
                                    `<div class="">
                                        <h3 class="text-warning-darker">${res.msg}</h3>
                                        <div class="ph30">
                                            <br><p>El archivo ya fue cargado anteriormente, es posible que usted requiera actualizar la información en la base de datos. </p>
                                            <br>Si desea actualizar, presione en continuar para validar la información del archivo. No se modificarán ni borrarán los datos todavia.
                                        </div>
                                        <h4>Desea Continuar con la Validación?</h4>
                                        <br><br>
                                        <input type=text class="hide" __field_import="continuar_carga" value="continuar_carga"> 
                                        <button __dd_accion="upload_file" class="btn btn-sm btn-primary darker mh10 br6">Continuar</button>
                                        <button __cerrar_alert class="btn btn-sm btn-warning darker mh10 br6">Cancelar</button>
                                    <div>
                                    ` 
                                , 'alert  pw80  bg-light  br-warning br6'
                                , 'fas fa-info-circle fa-3x text-warning-darker', '', false);
                            if (res.status == 'ok'){                            
                                let data = res.data;
                                ddMain.errores = data.errores;
                                xyzFuns.alertMsg('[__notificaciones]', 
                                    /*html*/
                                    `<div class="text-system-darker">
                                        <h3>Se ha revisado el archivo excel.</h3>
                                        <div class="ml20 mt5 text-left ph15 text-dark-darker" style="display:block">
                                            <br><b>Tiempo de ejecucion: </b> ${data.tiempo_ejecucion_seg < 60 ? data.tiempo_ejecucion_seg + ' segundos' : (data.tiempo_ejecucion_seg / 60).toFixed(1) + ' minutos' }
                                            <br><b>Total Registros: </b> ${data.num_registros_insertados}
                                            <br><b>Posibles errores: </b> ${data.num_errores}  <span __ver_errores class="ph10 bg-info darker cursor ml15 br6 ${data.errores.length > 0 ? '' : 'hidden'}"> ver</span>
                                            <br><br>
                                        </div>
                                        <h3>Desea guardar la información previamente cargada ?</h3> 
                                        <div class="text-dark">
                                            <em style="display:block; font-size:13px">Si el archivo ya fue cargado, y continua. La información vinculada con el nombre de este archivo será eliminada y estos nuevos datos serán insertados.</em>
                                            <em style="display:block; font-size:13px">Si cancela la operación, la BD no sufrirá cambios. </em>
                                            <br><br><br>
                                            <button __dd_accion="guardar_datos" class="btn btn-sm btn-primary darker mh10 br6">Guardar datos</button>
                                            <button __cerrar_alert class="btn btn-sm btn-warning darker mh10 br6">Cancelar</button>
                                        </div>
                                    </div>
                                    <div __panel_errores><div> `
                                , 'alert  bg-light col-sm-8 col-xs-12 br-system br6', 'fa fa-list-alt fa-3x text-system-darker', '', false);
                            }
                        },
                    });
                }
                else{
                    xyzFuns.alertMsg('[__notificaciones]', 'No se ha seleccionado ningún archivo.', 'alert alert-danger', 'fas fa-exclamation-triangle fa-2x br6', '', true);
                };
            },

            /** Guarda los datos pasa de stage a consolidado*/
            guardar_datos: ()=>{
                let nombre_archivo = $("[__file_to_upload]").val().split('\\').pop(); /* obtiene el nombre del archivo sin c:\\fake\\ */

                xyzFuns.alertMsgClose('[__notificaciones]');
                xyzFuns.alertMsg     ('[__notificaciones]', 'Guardando en la Base de Datos...', 'mt40', 'fa fa-pulse fa-3x fa-spinner text-system', 'colocar_span_class', false);

                $.post(ddMain.urlApi + 'guardar-datos', { nombre_archivo: nombre_archivo }, function (res) {
                    if(res.status=='ok'){
                        xyzFuns.alertMsgClose('[__notificaciones]');
                        xyzFuns.alertMsg     ('[__notificaciones]', 
                            /*html*/`
                            <h3>Se ha realizado el guardado de datos correctamente.</h3>                            
                            <div class="center-block w400 text-left">
                                <br><b>Tiempo de ejecucion: </b> ${res.data.tiempo_ejecucion_seg < 60 ? res.data.tiempo_ejecucion_seg + ' segundos' : (res.data.tiempo_ejecucion_seg / 60).toFixed(1) + ' minutos' }
                                <br><b>Registros anteriores eliminados:</b> ${res.data.filas_antiguas}  
                                <br><b>Registros nuevos insertados:</b> ${res.data.filas_nuevas}  
                            </div>
                            `
                            , 'alert alert-success pastel br6', 'fa fa-check fa-3x', '', true);
                    }
                    else{
                        xyzFuns.alertMsgClose('[__notificaciones]');
                        xyzFuns.alertMsg     ('[__notificaciones]', res.msg, 'alert alert-danger dark', 'fa fa-times-circle fa-3x', '', true);
                    }
                })
            },

            export: ()=> {
                $.get(ddMain.urlApi + 'exportar-excel?titulo=ArchivoExportacion', function (resp) {

                });
            }

        }

        let ddFuns = {

            /* obtiene el archivo cargado de un input:file */
            obtenerArchivo: selector => {
                let archivo = $(selector)[0];
                // return (archivo.files.length>0) ? archivo.files[0] : "no_archivo";
                return archivo.files[0];
            },
            colocaValoresIniciales: ()=>{
                $("[__field_import=num_hoja]").val("1");
                $("[__field_import=num_fila_start]").val("8");
            }
        }

        let ddListeners = () => {

            
            $("#dd_controlpanel")

            /** Cuando se carga un archivo evento Change*/
            // .on('change', '#up_excel', function (e) {
            //     let inputFile = $(this);
            //     let archivo = inputFile[0].files[0];
            //     $("[__field=nombre_archivo]").val(archivo.name);
            // })

            /** Acciones de los Botones*/
            .on('click', "[__dd_accion]", function (e) {
                let accion = $(e.currentTarget).attr('__dd_accion');
                /* al presionar el boton cargar y validar */
                if(accion == 'upload_file')
                    ddMain.cargar_archivo();
                /* al presionar el boton guardar  */
                if(accion == 'guardar_datos')
                    ddMain.guardar_datos();
                
                /* exportar info*/
                if(accion == 'export')
                    window.location = ddMain.urlApi + 'exportar-excel?titulo=ArchivoExportacion';
            })

            /** Al presionar en ver errores*/
            .on('click', "[__ver_errores]", function(e){
                xyzFuns.alertMsg('[__panel_errores]', 
                    /*html*/
                    `<div>
                        <h3 class="text-danger-darker"><i class="fa fa-exclamation-circle"></i> Lista de posibles errores en el archivo</h3>
                        <div __lista_errores style="max-height: 210px; min-height: 210px; text-align:left;  border: 1px solid grey; padding: 10px; margin: 15px 0 ;  overflow-y: scroll;">
                        </div>
                        <button class="cancel btn btn-sm btn-primary dark br6">cerrar</button>
                    </div>`
                    , 'alert bg-white col-md-9 col-xs-10 br-danger br6', '', '', true);

                let listaErrores = _.reduce(ddMain.errores, function(carry, error, index){
                    return carry + /*html*/`<span style="display:block; margin-top:5px">${index + 1} - ${error}</span>`
                }, '');

                $("[__panel_errores] [__lista_errores]").html(listaErrores);
                $("[__panel_errores] [__alert_msg]").css({position: 'absolute', 'top': '30px', 'left': '30px', width: '70%', 'z-indez': 9999999999});
            })

            /** Cuando se hace click en la x de los mensajes de alertas*/
            .on('click', '.close, .cancel, [__cerrar_alert]', function (e) {
                $(e.currentTarget).closest("[__alert_msg]").remove();
            })   
            
            // .on('click', '.menu-item', function(e){
            //     $(".frctl .menu-contenedor .menu-item").removeClass('menu-activo') 
            //     $(e.currentTarget).addClass('menu-activo');
            // })

            .on('click', "[__dd_menu_item]", function (e) {
                /* Quita todas clases activo y lo adiciona sobre el que se hizo click*/
                $(".frctl .menu-contenedor .menu-item").removeClass('menu-activo')
                $(e.currentTarget).addClass('menu-activo');

                /*oculta todas las secciones y muestra la del menu*/
                $("[__dd_section]").css({ display: 'none' });
                let menu = $(e.currentTarget).attr('__dd_menu_item');
                console.log(menu)
                $(`[__dd_section=${menu}]`).show(400).fadeIn(400);

                xyzFuns.alertMsgClose('[__notificaciones]'); // TODO individualizar los mensaje s por section  o ver otra manera opcion

            });
        }

        let ddInit = (() => {
            ddListeners();
            ddFuns.colocaValoresIniciales();
            setTimeout(function(){
                $("[__dd_control_panel]").show(200);
            }, 4000)
        })();
    })

</script>

<?php
    return ob_get_clean();
}
?>