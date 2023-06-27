<?php
function get_view_xp_chatbot($chatbot) {   
    # CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS  ---- ESTILOS  CSS  ----  CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
    wp_enqueue_style('animate.css'     , $chatbot->url      . 'app/assets/css/xpcb-animates.css', array(), '1.0.04', 'all');

    # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS  ---- SCRIPTS JS ---- JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
    wp_enqueue_script('xcod_lodash'    , $chatbot->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);

ob_start(); ?>

<style>
	/* Estilo de los scrolls */
	.frctl.xpcb .xp-agente-chat::-webkit-scrollbar {
		width: 5px;
		height: 5px;
	}

	.frctl.xpcb .xp-agente-chat::-webkit-scrollbar-thumb:hover {
		background-color: #56585b;
		border-radius: 5px;
	}

	.frctl.xpcb .xp-agente-chat::-webkit-scrollbar-thumb {
		background-color: #90969c;
		border-radius: 5px;
	}

	.frctl.xpcb .xp-agente {
		height: 440px;
		position: fixed;
		bottom: 30px;
		right: 20px;
		border: 1px solid #9c9ca7;
		border-bottom: 3px solid #5a5a5a;
		border-radius: 6px;
		font-family: 'Roboto';
		z-index: 9876543210;
		padding: 0px;
	}

	.frctl.xpcb .xp-agente-header {
		/* background-color: #973400; */
		background-color: #b62285;
		width: 100%;
		max-height: 80px;
		height: 100%;
		display: table;
	}

	.frctl.xpcb .xp-agente-header [__xp_btn_min]:hover {
		color: #aaa;
	}

	.frctl.xpcb .xp_agente_powered {
		/* width: 100%; */
		/* height: 30px;  */
		border-bottom: solid 1px #efefef;
		background-color: #f8f8ff;
		padding: 5px 15px;
		font-size: small;
	}

	.frctl.xpcb .xp_agente_input {
		height: 47px;
		border-top: 1px solid #d7d7d7;
		background: white;
		padding: 5px 10px;
		border-radius: 0px 0px 6px 6px;
	}

	.frctl.xpcb .xp-agente-mini {
		position: fixed;
		bottom: 21px;
		right: 20px;
		height: 100px;
		width: 100px;
		background-color: #3d84c5;
		border-radius: 50%;
		box-shadow: rgb(0 0 0 / 60%) 5px 1px 8px 0px;
		z-index: 9876543210;
		/* background-image: linear-gradient(36deg, #e41091 30%, #03ffff 100%);*/
	}

	.frctl.xpcb .xp-agente-mini [__xp_agente_min_bubble] {
		cursor: pointer;
	}

	.frctl.xpcb .xp-agente-mini img {
		margin: 6px 4px;
		width: 80px;
		height: 80px;
		border-radius: 50%;
	}

	.frctl.xpcb .xp-agente-mini .salute {
		background: black;
		padding: 12px;
		max-height: 70px;
		position: absolute;
		top: -85px;
		left: -100px;
		color: #eee;
		border-radius: 15px;
		font-size: 13px;
		opacity: 0.8;
	}

	.frctl.xpcb .xp-agente-mini .salute svg {
		fill: #000000;
		width: 60px;
		height: 60px;
		margin: 10px 0 0 40px;
	}

	.frctl.xpcb .user-send,
	.frctl.xpcb .server-send {
		display: inline-block;
		padding: 10px 20px;
		border-radius: 10px;
		border: 1px solid #e6e6e6;
		margin: 5px 15px 5px 15px;
		font-size: 14px;
		clear: both;
		/* font-family: 'Roboto'; */
		max-width: 80%;
		line-height: 1.4;
	}

	.frctl.xpcb .server-send {
		background-color: #fff1eb;
		/* background-color: #eff7f3; */
		float: left;
	}

	.frctl.xpcb .user-send {
		background-color: #bff296;
		/* background-color: #dcf8c6; */
		float: right;
	}
</style>


<div id="xp_contenedor" class="frctl xpcb">
	<!-- ------------- AGENTE CHATBOT -------------------------------------------->
	<div class="row">

		<div id=xp_agente __xp_agente class="xp-agente col-lg-5 col-md-6 col-xs-11" style="display: none;">
			<!-- <div id="xp_agente_header" style="background-color: #2d2c42; width: 100%; max-height: 80px; height: 100%; display: table;"> -->
			<div class="xp-agente-header">
				<span __xp_btn_min="" class="text-white-darker text-center"
					style="position: absolute; right: 20px; top: 5px; width: 20px; cursor: pointer;">
					<i class="fas fa-minus"></i>
				</span>
				<div class="text-white va-m" style="display: table-cell;">
					<img src="<?php echo $chatbot->url . 'app/assets/img/observatorio.png'; ?>" alt=""
						style="display: inline-block; margin-left: 30px; width: 60px; height: 60px; vertical-align: middle; border-radius: 50%;">
					<span style=" display: inline-block; margin-left: 20px; font-size: large; ">Asistente virtual </span>
					<span
						style=" font-size: medium;  position: relative;   top: 22px;    left: -123px;    display: inline-block; ">
					</span>
				</div>
			</div>

			<div __xp_section="options" class="text-muted text-right xp_agente_powered">
				<span __xp_action_btn="explorer" class="mr10 p3 ph5 br12 bg-success"
					style=" color:white; cursor: pointer;">Exploración Guiada</span>
				<span __xp_action_btn="reset" class="mr10 p3 ph5 br12"
					style="background-color: #ff9427; color:white; cursor: pointer;">Limpiar</span>
			</div>
			<!-- <div class="text-muted text-right xp_agente_powered" style="width: 100%; height: 30px; border-bottom: solid 1px #efefef; 
                background-color: #f8f8ff; padding: 5px 15px; font-size: small; cursor: pointer;">
                <span class="mr10"> powered by Fractal-IA</span>
            </div> -->

			<div __xp_section=chat class="xp-agente-chat"
				style="width: 100%; height: 270px; background-color: white; overflow-y: auto; ">
				<div style="height: 100%;  min-height: 100%;  width: 100%; display: table;">
					<div __chat_conversacion style="display: table-cell; vertical-align: bottom;"></div>
				</div>
			</div>

			<div __xp_section="input" class="xp_agente_input" style="position: relative;">
				<input __user_msg style="border: 0; outline: none; padding: 8px 10px 5px 10px;  width: 80%; font-size: 15px;"
					placeholder="Escribe aquí tu consulta ...">
				<!-- boton enviar -->
				<span __xp_action_btn="send" class=" "
					style="cursor: pointer; color: #6ec662;padding: 10px 16px; display: none;">
					<i class="fas fa-sign-out-alt fa-2x va-m"></i>
				</span>
				<!-- boton minimizar -->
				<span __xp_btn_min="" class="text-center " hidden=""
					style="cursor: pointer;color: #666;display: block;position: absolute;top: -4px;right: 0px;background-color: white;padding: 0px 12px;">
					<i class="fas fa-chevron-down fa-lg_ va-m" style="
                        margin-top: -10px;">
					</i>
				</span>

			</div>
		</div>
	</div>

	<div id=xp_min __xp_agente_min class="xp-agente-mini" style="display: none;">
		<div style="position: relative;" __xp_agente_min_dialog></div>
		<div __xp_agente_min_bubble>
			<img src="<?php echo $chatbot->url . 'app/assets/img/observatorio.png'; ?>" alt="">
		</div>
	</div>

</div>




<script>
	jQuery(function ($) {
		let ctxG = {
			urlApi: xyzFuns.urlRestApiWP + 'xpcb/v1/',
			wrapper: ".frctl.xpcb",
			urlChatbot: "<?php echo $chatbot->url; ?>",
		}

		/* xpcb: Expert ChatBot*/
		let xpcb = {
			contexto: {},
			contexto_opciones: {},
			setContext: (op) => {
				if (op.pregunta || op.pregunta == '') {
					/* Si no hay contexto o se ha reseteado, entonces empieza la primera pregunta*/
					if (_.isEmpty(xpcb.contexto)) {
						xpcb.contexto.asks = [{ pregunta: op.pregunta, nivel: -1, inicial: true }];
						return;
					}

					/* Si se ha seleccionado una opcion con click , entonces habra nivel*/
					if (op.nivel >= 0) {
						/* Si la nueva nivel es menor a alguna existente (si se escocio una opcion anterior), se deben eliminar los niveles iguales o superiores */
						_.remove(xpcb.contexto.asks, function (opcion, k) {
							return opcion.nivel >= op.nivel && k > 0;
						});
						xpcb.contexto.asks.push({ pregunta: op.pregunta, nivel: op.nivel });
						return;
					}

					/* Si se hace recorrido guiado*/
					// if(op.nivel == 0){
					//     xpcb.contexto.asks = [op];
					//     return;
					// }

					/* Si se ha escrito una pregunta o un numero y hay contexto de opciones anterior*/
					if (!(op.nivel)) {
						if (xpcb.contexto_opciones.contenido) {
							let pregEscrita = xpcbFuns.verificarPregunta(op);
							if (pregEscrita.esNumeroCorrecto) {
								xpcb.contexto.asks.push({ pregunta: pregEscrita.pregunta, nivel: pregEscrita.nivel });
								xpcbFuns.printUserMsg(pregEscrita.pregunta)
							}
							/* Si no es numero es como primera pregunta*/
							else {
								xpcb.contexto.asks = [{ pregunta: op.pregunta, nivel: -1, inicial: true }];
							}
						}

					}

				}
				if (op.contexto) {
					let noEncontradoInicial = (op.contexto.status == 'no_encontrado');
					let finNodo = (op.contexto.status == 'fin'); /** Las opciones son opciones, no_encontrado, varios_resultados, un_resultado    el fin no se usa , no afecta en nada*/

					if (noEncontradoInicial) { //|| finNodo){
						xpcb.contexto = {};
						xpcb.contexto_opciones = {};
					}
					else {
						xpcb.contexto = op.contexto;
					}
				}

			},
			/** Realiza la interaccion de las respuestas recibe objMsg = {whoSend: '', msg: 'mensaje para enviar', msg_nivel: 'nivel para el mensaje(nivel)' }*/
			interaccion: (whoSend, objMsg) => {

				if (whoSend == 'user') {
					if (objMsg.pregunta.trim() != '')
						xpcbFuns.printUserMsg(objMsg.pregunta);

					xpcb.setContext({ pregunta: objMsg.pregunta, nivel: objMsg.nivel });

					console.log('SEND User BEFORE POST contexto request');
					console.log(xpcb.contexto);

					$.post(ctxG.urlApi + 'xp-request', { contexto: xpcb.contexto }, function (resp) {
						let ctx = resp.ctx;
						xpcb.interaccion('expert', { respuesta: ctx.respuesta, status: ctx.status });

						xpcb.setContext({ contexto: ctx });
					})
				}

				if (whoSend == 'expert') {
					let respuesta = objMsg.respuesta;
					let status = objMsg.status;
					let delay = 0;
					let delayEvery = 500;

					_.each(respuesta, function (item) {
						let respuestaObj = item;
						if (respuestaObj.tipo == 'opcion' && status == 'opciones')
							xpcb.contexto_opciones = respuestaObj;

						let tiempoDeEscribiendo = _.random(1000, 2000);
						xpcbFuns.printExpertMsg({ tipo: 'escribiendo' });
						setTimeout(() => {
							xpcbFuns.printExpertMsg({ tipo: 'escribiendo' });
							setTimeout(() => {
								xpcbFuns.printExpertMsg(respuestaObj);
							}, delay);
						}, delay);
						delay += tiempoDeEscribiendo;
					})

				}
			},

			solicita: (solicitud) => {
				if (solicitud == 'saludo')
					$.post(ctxG.urlApi + 'xp-mensaje', { solicita: solicitud }, function (resp) {
						let ctx = resp.ctx;
						xpcb.interaccion('expert', ctx);
					})
			}

		}

		let xpcbFuns = {

			/** Escribe la pregunta/respúesta del usuario*/
			printUserMsg: (msg) => {
				let userHtml = /*html*/ `<div class="user-send" >${msg}</div>`;
				$("[__chat_conversacion]").append(userHtml);
				$("[__xp_section=chat]").animate({ scrollTop: $("[__chat_conversacion]").height() }, 200);
				$("[__user_msg]").val("")
			},

			/** Escribe la pregunta/respúesta del Sistema Experto*/
			printExpertMsg: (msg) => {
				let serverHtml = '';
				$("[__escribiendo]").remove();

				if (msg.tipo == 'escribiendo')
					serverHtml = /*html*/ `<div class="server-send" __escribiendo style="padding:0px 10px; "><img src="<?php echo $chatbot->url . 'app/assets/animations/dots.gif'; ?>" style="width:65px; height:30px" /></div>`;

				if (msg.tipo == 'normal')
					serverHtml = /*html*/ `<div class="server-send" >${msg.contenido}</div>`;

				if (msg.tipo == 'opcion')
					serverHtml = /*html*/ `<div class="server-send" >${xpcbFuns.creaOpciones(msg.contenido, msg.opciones_nivel)}</div>`;

				if (msg.tipo == 'gif'){
					let gifs = [
						// `<img src="` . $urlChatBotPlugin . `fcl-xp-chatbot/assets/animations/saludar-con-la-mano-imagen-animada-0020.gif" border="0" alt="saludar" style="width: 160px; height: 160px" />`,
						`<img src="${ctxG.urlChatbot}app/assets/animations/hola-saltarin.gif" border="0" alt="saltarin" style="width: 160px; height: 160px" />`,
						`<img src="${ctxG.urlChatbot}app/assets/animations/hola-01.gif" border="0" alt="hola" style="width: 180px; height: 180px" />`,
						`<img src="${ctxG.urlChatbot}app/assets/animations/hola-02.gif" border="0" alt="hola" style="width: 180px; height: 180px" />`,
						`<img src="${ctxG.urlChatbot}app/assets/animations/hola-03.gif" border="0" alt="hola" style="width: 180px; height: 180px" />`,
						// `<video src="` . $urlChatBotPlugin . `app/assets/animations/io_back_colors.mp4" border="0" loop autoplay style="width: 400px; height: 180px" />`,
						// `<video src="` . $urlChatBotPlugin . `app/assets/animations/io_back_orange.mp4" border="0" loop autoplay style="width: 400px; height: 180px" />`,
					];
					let indexRandom = _.random(0, 100000000) % gifs.length;
					serverHtml = /*html*/ `<div class="server-send" style="background: white; border: 0" >${gifs[indexRandom]}</div>`;
				}

				$("[__chat_conversacion]").append(serverHtml);
				$("[__xp_section=chat]").animate({ scrollTop: $("[__chat_conversacion]").height() }, 200);

			},

			/** Crea la respuesta de opciones Nivel 3 si es para seleccionar o solo descriptiva*/
			creaOpciones: (opciones, nivel) => {
				let opcionesHtml = '';
				if (nivel < 3)
					opcionesHtml = _.reduce(opciones, function (carry, opt, index) {
						return carry + /*html*/`<span class='btn btn-sm btn-danger mt5 mh5 br6' style='max-width:100%; white-space: normal; text-align: left;' 
                                                            __xpcb_respuesta_opcion='${opt.opcion}'  __xpcb_respuesta_opcion_number='${index}'   __xpcb_respuesta_opcion_nivel='${nivel}' > 
                                                            ${index + 1}. ${opt.opcion}
                                                        </span>`;
					}, '');
				if (nivel == 3)
					opcionesHtml = _.reduce(opciones, function (carry, opt, index) {
						return carry + `<p>${opt.opcion}</p>`;
					}, '');
				return opcionesHtml;
			},

			/* Si se escribio un numero como respuesta o texto*/
			verificarPregunta: (obj) => {
				let preguntaSC = _.replace(obj.pregunta, /[ .,;:()\-]/g, '');
				// objOpciones = xpcb.contexto_opciones;
				if (!isNaN(preguntaSC)) {
					index = parseInt(preguntaSC) - 1;
					return {
						pregunta: xpcb.contexto_opciones.contenido[index].opcion,
						nivel: xpcb.contexto_opciones.opciones_nivel,
						esNumeroCorrecto: true,
					}
				}
				return {
					pregunta: obj.pregunta,
					nivel: xpcb.contexto_opciones.opciones_nivel
				}
			},

			/** Funcion al Maximizar o minimizar el chat*/
			agenteMaxMin: () => {
				let mostrarAgente = $("[__xp_agente]").css('display') == 'none';

				mostrarAgente ? $("[__xp_agente]").show(300) : $("[__xp_agente]").hide(300);
				mostrarAgente ? $("[__xp_agente_min]").hide(300) : $("[__xp_agente_min]").show(300);

				if (mostrarAgente && $("[__chat_conversacion]").html().trim() == '') {
					xpcb.contexto = {};
					xpcb.solicita('saludo');
				}

			},

			/* Para mostrar las animaciones y mensaje del bot minimizado*/
			animacionAgenteMin: (estado = 'run', interval = 1000 * 60 * 5) => {
				let element = $(`${ctxG.wrapper} [__xp_agente_min]`);
				let animationsClass = [
					, 'fcl_animate__bounce', 'fcl_animate__bounce', 'fcl_animate__bounce', 'fcl_animate__bounce', 'fcl_animate__bounce'
					, 'fcl_animate__pulse', 'fcl_animate__rubberBand', 'fcl_animate__shakeX', 'fcl_animate_giraReloj', 'fcl_animate__rotateXYZ'
				];
				/* Funcion que ejecuta una animacion aleatoriamente en un elemento html el*/
				function ejecutarAnimacionAleatoria(el) {
					let index = _.random(0, 100000000) % animationsClass.length;
					let animation = animationsClass[index];
					el.addClass(animation).one('animationend', () => {
						el.removeClass(animation);
					})
				}

				if (estado == 'once')
					ejecutarAnimacionAleatoria(element);

				if (estado == 'active')
					setInterval(() => {
						ejecutarAnimacionAleatoria(element);
						console.log('se ejecuta')
					}, interval);

				if (estado == 'salute') {
					let msg = "Hola, puedes hacerme cualquier consulta!!"
					let salute = /*html*/`
                                <div class="salute" style="display:none">                                    
                                    <span class="cancel pull-right " style="margin: -10px;color: #777;padding: 5px;cursor: pointer;"><i class="fa fa-close "></i></span>
                                    <div>${msg}</div>
                                    <svg>
                                        <polygon points="0,0 30,0 40,30" style=""></polygon>
                                    </svg>                                    
                                </div>`;
					$("[__xp_agente_min_dialog").html(salute);
					$(".salute").fadeIn(300);
				}
			},
		}


		let xpcbListeners = () => {

			$(`${ctxG.wrapper}`)
				/** Botones de Accion del Ususario SEND, RESET, EXPLORACION GUIADA */
				.on('click', `[__xp_action_btn]`, function (e) {
					let accion = $(e.currentTarget).attr('__xp_action_btn');

					if (accion == 'reset') {
						xpcb.contexto = {};
						xpcb.contexto_opciones = {};
						$("[__chat_conversacion]").html('');
						xpcb.solicita('saludo');
					}

					if (accion == 'explorer') {
						xpcb.contexto = {};
						xpcb.contexto_opciones = {};
						// xpcb.contexto.asks = [{ pregunta: '', nivel: 0 }];
						let objMsg = { pregunta: '', nivel: 0 };
						xpcb.interaccion('user', objMsg);
					}

					if (accion == 'send') {
						if ($(`${ctxG.wrapper} [__user_msg]`).val().trim() != '') {
							let objMsg = { pregunta: $(`${ctxG.wrapper} [__user_msg]`).val() };
							xpcb.interaccion('user', objMsg);
						}
					}
				})
				/** SECCION CHAT  */
				/** Al HACER CLICK sobre una OPCION de respuesta */
				.on('click', `[__xpcb_respuesta_opcion]`, function (e) {
					let objMsg = {
						pregunta: $(e.currentTarget).attr('__xpcb_respuesta_opcion'),
						nivel: $(e.currentTarget).attr('__xpcb_respuesta_opcion_nivel'),
					}
					xpcb.interaccion('user', objMsg);
				})

				/** SECCION INPUT */
				/** Hace aparecer el boton de ENVIAR >> */
				.on('keyup', '[__user_msg]', function () {
					if ($("[__user_msg]").val() == ' ')
						$("[__user_msg]").val('');

					$("[__user_msg]").val().length > 0 ? $("[__xp_action_btn=send]").fadeIn(300) : $("[__xp_action_btn=send]").fadeOut(300)
				})

				/** Al presionar ENTER */
				.on('keydown', "[__user_msg]", function (e) {
					if (e.keyCode == 13)
						$("[__xp_action_btn=send]").trigger('click');
				})

				/** Al presionar boton x */
				.on('click', '.close, .cancel', function (e) {
					$(e.currentTarget).closest(".salute").remove();
				})

				/** Al MAXIMIZAR o MINIMIZAR */
				.on('click', "[__xp_btn_min], [__xp_agente_min] [__xp_agente_min_bubble]", function () {
					xpcbFuns.agenteMaxMin();
				})


		}

		let xpcbInit = (() => {
			xpcbListeners();
			setTimeout(() => {
				$("[__xp_agente_min]").fadeIn(700);
			}, 2000);

			/* ejecuta una animacion la primera vez e y activa el timer de animaciones*/
			setTimeout(() => {
				xpcbFuns.animacionAgenteMin('once');
				xpcbFuns.animacionAgenteMin('active', 1000 * 60 * 3);
			}, 4000)

			/* Muestra mensaje salute solo unica ves despues de la primera animacion, y se oculya automaticamente despues de unos segundos*/
			setTimeout(() => {
				xpcbFuns.animacionAgenteMin('salute');
				setTimeout(() => {
					$(".salute").fadeOut(400);
				}, 6000)
			}, 8000)
		})();


	})


</script>

<?php
    return ob_get_clean();
}
?>