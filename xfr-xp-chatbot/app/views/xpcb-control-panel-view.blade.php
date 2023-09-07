<?php
function get_view_xp_control_panel($chatbot) {
	#CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
    
  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
	wp_enqueue_script('lodash.min.js'    , $chatbot->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);

ob_start(); ?>


<div id="xp_controlpanel" class="frctl">
	<!-- ------------- AGENTE CHATBOT -------------------------------------------->
	<div id=xp_agente __xpcp_section=acciones class="xp-cp-agente" style="width: 60%; margin: 60px auto; min-height: 70vh;">

		<div class="bg-light darker mb10 p10 br-a br-greyer br8 fs14 ">
			<div>
				<h2 class="text-center">Carga de información y entrenamiento del Chatbot</h2>
			</div>
			<div>
				<h3>Carga de información</h3>
				<h4>Se debe subir un archivo excel con el formato suministrado, para poder llenar la base de datos con la información.</h4>
				<span>
					<input type="file" name="" id="">
				</span>
			</div>
			<div><button __xpcp_accion="cargarBD" class="btn btn-info br6 mt20"> Cargar la información a la Base de Datos <i class="fab fa-think-peaks"></i></button></div>
		</div>
		<div>
			<h3>Entrenamiento del chatbot</h3>
			<h4>Se realizará la tokenización de la información (este proceso puede tardar varios minutos)</h4>
			<span>
				Aseguresé de haber cargado la base de datos, en la tabla xfr_textos, el excel que contiene la información para el chatbot.
			</span>
		</div>
		<div><button __xpcp_accion="entrenar" class="btn btn-info br6 mt20"> Entrenar <i class="fab fa-think-peaks"></i></button></div>
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
			cargarBD: () => {
				console.log('carganfoooo...................');
				$.get(xpcp.urlApi + 'exportar', {}, function (resp) {
					xpcpFuns.alertMsgClose();
					xpcpFuns.alertMsg("[__xpcp_section='acciones'", "alert-success pastel ", "fa fa-check fa-3x", resp.msg);
				});
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
				$("#xp_controlpanel [__alert_msg]").remove();
			}
		}

		let xpcpListeners = () => {

			$("#xp_controlpanel").on('click', "[__xpcp_accion]", function (e) {
				let accion = $(e.currentTarget).attr('__xpcp_accion');
				if (accion == 'entrenar')
					xpcp.entrenar();
				if (accion == 'cargarBD')
					xpcp.cargarBD();
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