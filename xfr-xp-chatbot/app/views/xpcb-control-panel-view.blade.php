<?php
function get_view_xp_control_panel($chatbot) {
	#CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
    
  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
	wp_enqueue_script('lodash.min.js'    , $chatbot->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);

ob_start(); ?>


<div id="xp_controlpanel" class="frctl">
	<!-- ------------- AGENTE CHATBOT -------------------------------------------->
	<div id=xp_agente __xpcp_section=acciones class="xp-cp-agente" style="width: 60%; margin: 60px auto; min-height: 70vh;">

		<h2 class="text-center">Configuración del Chatbot</h2>
		<div class="bg-light darker_ mb10 p10 br-a br-greyer br8 fs14 ">
			<div>
				<h3>Carga de información</h3>
				<h4>Se debe subir un archivo excel con el formato suministrado, para poder llenar la base de datos con la información.</h4>
				<span>
					<input type="file" name="" id="" __rg_field="archivo">
				</span>
			</div>
			<div class="flex justify-center"><button __xpcp_accion="cargarBD" class="btn btn-success br6 mt20"> Cargar la información a la Base de Datos <i class="fab fa-think-peaks"></i></button></div>
		</div>
		<div class="bg-light darker_ mb10 p10 br-a br-greyer br8 fs14 ">
			<div  >
				<h3>Entrenamiento del chatbot</h3>
				<h4>Se realizará el tratamiento de la información (este proceso puede tardar varios minutos)</h4>
				<span>
					Aseguresé de haber cargado la base de datos, con el archivo excel que contiene la información para el chatbot.
				</span>
			</div>
			<div class="flex justify-center"><button __xpcp_accion="entrenar" class="btn btn-info br6 mt20"> Entrenar <i class="fab fa-think-peaks"></i></button></div>
		</div>
	</div>
	</div>
</div>




<script>
	jQuery(function ($) {

		let ctxG = {
			urlApi: xyzFuns.urlRestApiWP + 'xpcb/v1/',
			content: '[__xpcp_section=acciones]',
		}

		/* xpcp: expert control pannel*/
		let xpcp = {		

			entrenar: () => {
				xpcpFuns.spinner();
				xpcpFuns.alertMsgClose();
				xpcpFuns.alertMsg(ctxG.content, "alert-default pastel ", "fas fa-spinner fa-spin fa-3x", "Entrenando... No realice ninguna acción hasta que finalice el proceso");
				$.post(ctxG.urlApi + 'training', {}, function (resp) {
					xpcpFuns.spinner(false);
					xpcpFuns.alertMsgClose();
					xpcpFuns.alertMsg(ctxG.content, "alert-success pastel ", "fa fa-check fa-3x", resp.msg);
				});
			},
			cargarBD: () => {
				if (!($('[__rg_field=archivo]')[0].files.length > 0)) {
					return;
				}
				xpcpFuns.spinner();
				xpcpFuns.alertMsgClose();
				let archivo = $('[__rg_field=archivo]')[0].files[0]
				let formData = new FormData();
				formData.append('archivo', archivo);
				$.ajax({
            url: ctxG.urlApi + 'cargar-archivo-cb',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (resp) {
              if (resp.status == 'ok') {
                xpcpFuns.alertMsgClose();
								xpcpFuns.alertMsg(ctxG.content, "alert-success pastel ", "fa fa-check fa-3x", resp.msg);
              }
            },
            error: function () {
              console.log('Error al subir la image_n.');
            },
            complete: function (res) {
              // resp = res.responseJSON;
              xpcpFuns.spinner(false);
            } 
          })
			}

		}

		let xpcpFuns = {

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

		let xpcpListeners = () => {
			// $("#xp_controlpanel").off('click', "[__xpcp_accion]");
			$("#xp_controlpanel").on('click', "[__xpcp_accion]", function (e) {
				let accion = $(e.currentTarget).attr('__xpcp_accion');
				if (accion == 'entrenar')
					xpcp.entrenar();
				if (accion == 'cargarBD'){
					xpcp.cargarBD();
				}
			})
				/** Cuando se hace click en la x de los mensajes de alertas*/
				.on('click', '.close, .cancel', function (e) {
					$(e.currentTarget).closest("[__alert_msg]").remove();
				})
		}

		let xpcpInit = (() => {
			xpcpListeners();
		})();
	})

</script>

<?php
    return ob_get_clean();
}
?>