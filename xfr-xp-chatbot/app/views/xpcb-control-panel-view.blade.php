<?php
function get_view_xp_control_panel($chatbot) {
	#CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
    
  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
	wp_enqueue_script('lodash.min.js'    , $chatbot->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);

ob_start(); ?>


<div id="xp_controlpanel" class="frctl">
	<!-- ------------- AGENTE CHATBOT -------------------------------------------->
	<div id=xp_agente __xpcp_section=acciones class="xp-cp-agente">
		<div class="bg-light darker mb10 p10">
			<button __xpcp_accion="entrenar" class="btn btn-info"> Entrenar <i class="fab fa-think-peaks"></i></button>
		</div>
	</div>
</div>




<script>
	jQuery(function ($) {
		/* xpcp: expert control pannel*/
		let xpcp = {
			// urlApi: xyzFuns.urlCoreApi + 'xpcb/',
			urlApi: xyzFuns.urlRestApiWP + 'xpcb/v1/',

			entrenar: () => {
				xpcpFuns.alertMsg("[__xpcp_section='acciones'", "alert-default pastel ", "fas fa-spinner fa-spin fa-3x", "Entrenando...");
				$.post(xpcp.urlApi + 'training', {}, function (resp) {
					xpcpFuns.alertMsgClose();
					xpcpFuns.alertMsg("[__xpcp_section='acciones'", "alert-success pastel ", "fa fa-check fa-3x", resp.msg);
				});
			},

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
				$("#xp_controlpanel [__alert_msg]").remove();
			}
		}

		let xpcpListeners = () => {

			$("#xp_controlpanel").on('click', "[__xpcp_accion]", function (e) {
				let accion = $(e.currentTarget).attr('__xpcp_accion');
				if (accion == 'entrenar')
					xpcp.entrenar();
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