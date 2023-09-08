<?php
function get_view_dd_control_panel($dataDash) {
// wp_enqueue_style('select2_sty'       , $dataDash->core_url . 'assets/libs-ext/select2-4.1.0/dist/css/select2.min.css');    
// wp_enqueue_style('datatable_sty'     , $dataDash->core_url . 'assets/libs-ext/datatables-1.10.25/css/jquery.dataTables.min.css');    
// wp_enqueue_style('admin_form_sty'    , $dataDash->core_url . 'assets/libs-ext/sty-02/assets/admin-tools/admin-forms/css/admin-forms.css');
// wp_enqueue_style('theme_'            , $dataDash->core_url . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme.css', array(), '1.0.4', 'all');
wp_enqueue_style('theme_lite'           , $dataDash->core_url . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme_lite.css', array(), '1.0.3', 'all'); 

wp_enqueue_script('xcod_lodash'         , $dataDash->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
// wp_enqueue_script('select2_js'       , $dataDash->core_url . 'assets/libs-ext/select2-4.1.0/dist/js/select2.min.js', array(), null, true);
// wp_enqueue_script('datatables'       , $dataDash->core_url . 'assets/libs-ext/datatables-1.10.25/js/jquery.dataTables.min.js', array(), null, true);

ob_start(); ?>


<!-- ------------- Control Panel-------------------------------------------->
<div id="xp_controlpanel" class="frctl">
	<!-- ------------- AGENTE CHATBOT -------------------------------------------->
	<div id=xp_agente __dd_section=acciones class="xp-cp-agente" style="width: 60%; margin: 60px auto; min-height: 70vh;">

		<h2 class="text-center">Cargar archivo Excel de Feminicidios</h2>
		<div class="bg-light darker_ mb10 p10 br-a br-greyer br8 fs14 ">
			<div>
				<h3>Carga de información de Feminicidios</h3>
				<h4>Se debe subir un archivo excel con el formato suministrado, para poder llenar la base de datos con la información.</h4>
				<span>
					<input type="file" name="" id="" __rg_field="archivo">
				</span>
			</div>
			<div class="flex justify-center"><button __dd_accion="cargarBD" class="btn btn-success br6 mt20"> Cargar la información a la Base de Datos <i class="fab fa-think-peaks"></i></button></div>
		</div>
	</div>
	</div>
</div>


<script>
	jQuery(function ($) {

		let ctxG = {
			urlApi: xyzFuns.urlRestApiWP + 'st/v1/',
			content: '[__dd_section=acciones]',
		}

		/* dd: expert control pannel*/
		let dd = {		

			cargarBD: () => {
				if (!($('[__rg_field=archivo]')[0].files.length > 0)) {
					return;
				}
				funs.spinner();
				funs.alertMsgClose();
				let archivo = $('[__rg_field=archivo]')[0].files[0]
				let formData = new FormData();
				formData.append('archivo', archivo);
				$.ajax({
					url: ctxG.urlApi + 'cargar-archivo',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (resp) {
						if (resp.status == 'ok') {
							funs.alertMsgClose();
							funs.alertMsg(ctxG.content, "alert-success pastel ", "fa fa-check fa-3x", resp.msg);
						}
					},
					error: function () {
						console.log('Error al subir la image_n.');
					},
					complete: function (res) {
						// resp = res.responseJSON;
						funs.spinner(false);
					}
				})
			}

		}

		let funs = {

			alertMsg: (contenedor, classAlert, classIcon, msg) => {
				$alertHtml = /*html*/`<div __alert_msg class="text-center">
                                        <div class="alert ${classAlert} alert-dismissable text-center pw70 center-block mt10 " style="vertical-align: middle">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class=" ${classIcon} va-m mr10"></i> 
                                            ${msg}
                                        </div>
                                        </div> `;
				$(contenedor).append($alertHtml);
			},

			alertMsgClose: () => {
				$(`${ctxG.content}  [__alert_msg]`).remove();
			},
			spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },

		}

		let ddListeners = () => {
			// $("#xp_controlpanel").off('click', "[__dd_accion]");
			$("#xp_controlpanel").on('click', "[__dd_accion]", function (e) {
				let accion = $(e.currentTarget).attr('__dd_accion');
				if (accion == 'entrenar')
					dd.entrenar();
				if (accion == 'cargarBD'){
					dd.cargarBD();
				}
			})
				/** Cuando se hace click en la x de los mensajes de alertas*/
				.on('click', '.close, .cancel', function (e) {
					$(e.currentTarget).closest("[__alert_msg]").remove();
				})
		}

		let ddInit = (() => {
			ddListeners();
		})();
	})

</script>

<?php
    return ob_get_clean();
}
?>