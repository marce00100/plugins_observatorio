<?php
require_once $chatbot->path  . "app/libs-php/lib-normaliza.php";

use frctl\MasterController;

class xpcbController extends MasterController
// class xpcbController extends MasterChatbotController
{

	/** 
	 * API REST POST Funcion Funcional
	 * Se recibe una pregunta del chat, y se calcula la respuesta
	 */
	public function xpRequest(WP_REST_Request $req) {
		$DB = $this;
		$reqContexto = (object)$req['contexto'];
		$contexto = (object)[];

		$contexto->asks  = $reqContexto->asks ?? array(['pregunta' => '', 'nivel' => 0]); /* Si Asks llega como null,se crea un array de objetos vacio para consulta guiada*/

		$ask_first = $contexto->asks[0] = (object)$contexto->asks[0];
		$ask_first_lema  = empty($ask_first->pregunta) ? '' : Normaliza::lematizaConStemSW($contexto->asks[0]->pregunta);
		$ask_first_nivel = !isset($ask_first->nivel) ? null : $contexto->asks[0]->nivel;

		/* Si es primera pregunta el nivel es -1, si es 0 es pregunta guiada es mayo que 1 ya existe interaccion*/
		$ask_new = $ask_first_nivel < 0;

		$query = "SELECT * FROM 
                        (SELECT t.contenido_id, t.nivel0_lema, t.nivel1_lema, t.nivel2_lema, t.nivel3_lema,
                            t.concat, c.nivel0, c.codigo1, c.nivel1 , c.nivel2, c.nivel3  
                        FROM xfr_tokens t, xfr_textos c 
                        WHERE c.id = t.contenido_id ) q
                    WHERE 1=1 ";

		$queryAskFirst = empty($ask_first_lema) ? ' ' : ' AND (false ' .  collect(explode(' ', $ask_first_lema))
			->reduce(function ($carr, $word) use ($ask_new, $ask_first_nivel) {
				$nivel = $ask_new ? "#_nivel_#" : "nivel{$ask_first_nivel}_lema";
				return $carr . " OR {$nivel} LIKE '%{$word}%' ";
			}, ' ') . ' ) ';

		$queryAsksAfter = collect($contexto->asks)
			->reduce(function ($carry, $item) {
				$item = (object)$item;
				$str = '';
				// if($k > 0){
				if (!isset($item->inicial)) {
					// $pregunta_lema = Normaliza::lematizaConStemSW($item->pregunta);
					// $str = " AND nivel{$item->nivel}_lema LIKE '{$pregunta_lema}' "; /**Se compara con el valor igual para que no haya similitudes con otras opciones */
					$str = " AND nivel{$item->nivel} LIKE '%{$item->pregunta}%' ";
					/**Se compara con el valor igual para que no haya similitudes con otras opciones */
				}
				return $carry . $str;
			}, ' ');

		/** querycomplete se utiliza cuando ya hubo interacciones */
		$querycomplete = $query . $queryAskFirst . $queryAsksAfter;

		$ctx = (object)[];

		if ($ask_new || $ask_first_nivel == 1 || $ask_first_nivel == 0) {

			$data = $ask_new ? collect($DB->select($query . str_replace("#_nivel_#", "nivel1_lema", $queryAskFirst))) : collect($DB->select($querycomplete));
			// $data = $ask_new ? collect($DB->select("{$query} AND nivel1_lema LIKE '%{$ask_first_lema}%' ")) : collect($DB->select($querycomplete));
			$groupNivel1 = $data->groupBy('nivel1');

			/** 2 PRIMER MATCH CON TITULO */
			if (count($groupNivel1) >= 1) {
				$ctx                 = (object)self::revisaArbol($data, $groupNivel1, 'nivel0');

				$contexto->asks[0]->pregunta = $ask_first_lema;
				$contexto->asks[0]->nivel    = $ask_new ? 1 : $ask_first_nivel;
				$ctx->asks                   = $contexto->asks;

				return [
					'ctx' => $ctx,
				];
			}
		}

		if ($ask_new || $ask_first_nivel == 2) {
			$data = $ask_new ? collect($DB->select("{$query} " . str_replace("#_nivel_#", "nivel2_lema", $queryAskFirst))) : collect($DB->select($querycomplete));
			// $data = $ask_new ? collect($DB->select("{$query} AND nivel2_lema LIKE '%{$ask_first_lema}%' ")) : collect($DB->select($querycomplete));
			$groupNivel2 = $data->groupBy('nivel2_lema');
			/** 3 PRIMER MATCH CON FASE */
			if (count($groupNivel2) >= 1) {
				$groupNivel1 = $data->groupBy('nivel1');
				/** 3.1 SI ES UNICA SE MUESTRA EL LISTADO DE ACCIONES */
				if (count($groupNivel2) == 1 && count($groupNivel1) == 1) {
					$ctx                 = self::bajarNivelUnico($data, 3);
					// $ctx->ask_first      = $ask_first_lema;
					// $ctx->ask_nivel      = $ask_new ? 2 : $ask_first_nivel;

					$contexto->asks[0]->pregunta = $ask_first_lema;
					$contexto->asks[0]->nivel    = $ask_new ? 2 : $ask_first_nivel;
					$ctx->asks                   = $contexto->asks;
				}
				/** 3.2 SI SON VARIAS SE MUESTRA DE QUE NIVEL SUPERIOR QUIERE (TITULO) */
				else /*(count($groupNivel2) > 1 )*/ {
					$groupNivel1         = $data->groupBy('nivel1');
					$ctx                 = (object)self::revisaArbol($data, $groupNivel1, 'nivel0');
					// $ctx->ask_first      = $ask_first_lema;
					// $ctx->ask_nivel      = $ask_new ? 2 : $ask_first_nivel;

					$contexto->asks[0]->pregunta = $ask_first_lema;
					$contexto->asks[0]->nivel    = $ask_new ? 2 : $ask_first_nivel;
					$ctx->asks                   = $contexto->asks;
				}

				return [
					'ctx' => $ctx,
				];
			}
		}

		if ($ask_new || $ask_first_nivel == 3) {
			$data = $ask_new ?  collect($DB->select("{$query} " . str_replace("#_nivel_#", "nivel3_lema", $queryAskFirst))) : collect($DB->select($querycomplete));
			// $data = $ask_new ?  collect($DB->select("{$query} AND nivel3_lema LIKE '%{$ask_first_lema}%' ")) : collect($DB->select($querycomplete));
			$groupNivel3 = $data->groupBy('nivel3_lema');
			/** 4 PRIMER MATCH CON TEXTO */
			if (count($groupNivel3) >= 1) {
				$groupNivel1 = $data->groupBy('nivel1');
				/** 4.1 SI ES UNICA SE MUESTRA LA ACCION CORRESPONDIENTE */
				if (count($groupNivel3) == 1  && count($groupNivel1) == 1) {
					$ctx                 = self::bajarNivelUnico($data, 3);
					// $ctx->ask_first      = $ask_first_lema;
					// $ctx->ask_nivel      = $ask_new ? 3 : $ask_first_nivel;

					$contexto->asks[0]->pregunta = $ask_first_lema;
					$contexto->asks[0]->nivel    = $ask_new ? 3 : $ask_first_nivel;
					$ctx->asks                   = $contexto->asks;
				}
				/** 4.2 SI SON VARIAS Y DE LA MISMA CATEGORIA SE MUESTRA DE QUE FASE QUIERE, Y LUEGO SE MUESTRA DICHA ACCION */
				else /*(count($groupNivel3) > 1)*/ {
					$groupNivel1         = $data->groupBy('nivel1');
					$ctx                 = (object)self::revisaArbol($data, $groupNivel1, 'nivel0');
					$ctx->ask_first      = $ask_first_lema;
					$ctx->ask_nivel      = $ask_new ? 3 : $ask_first_nivel;

					$contexto->asks[0]->pregunta = $ask_first_lema;
					$contexto->asks[0]->nivel    = $ask_new ? 3 : $ask_first_nivel;
					$ctx->asks                   = $contexto->asks;
				}

				return [
					'ctx' => $ctx,
				];
			}
		}

		$ctx->respuesta      = [array('tipo' => 'normal', 'contenido' => self::mensajeAleatorio('no_encontrado'),)];
		$ctx->asks           = $contexto->asks;
		$ctx->status         = 'no_encontrado';

		return [
			'ctx' => $ctx
		];
	}

	/** LOCAL   ***********************************************************************************************************************************
	 * Realiza la comparacion con Nivel0 (Categoria) y Nivel1 (Titulo)
	 * Grupo Actual es de nivel 1
	 * JERARQUIA = {nivel0: categoria, nivel1: titulo, nivel2: fase, nivel3: texto } 
	 * REGLAS:
	 * 2.1 SI ES UNICA CATEGORIA MUESTRA DE QUE FASE SE QUIERE EL RESULTADO 
	 * 2.2 SI SON VARIAS SE MUESTRA DE QUE NIVEL SUPERIOR QUIERE (Categoria)
	 * 2.2.1 SI LA CATEGORIA ES UNICA SE MUESTRA TODOS LOS TITULOS COINCIDENTES EN LA BUSQUEDA
	 * 2.2.2 SI SON VARIAS CATEGORIAS SE MUESTRA ESTAS CATEGORIAS
	 */
	public static  function revisaArbol($collect, $groupActual, $nivelUp) {
		$ctx = (object)[];
		/** 2.1 SI ES UNICA NIVEL1 MUESTRA DE QUE FASE SE QUIERE EL RESULTADO */
		if (count($groupActual) == 1) {
			$opciones_nivel             = 2;
			$respuesta                  = self::bajarNivelUnico($collect, $opciones_nivel);
			$ctx->respuesta             = $respuesta->respuesta;
			$ctx->opciones_nivel        = $respuesta->opciones_nivel;
			$ctx->status                = $respuesta->status;
		}
		/** 2.2 SI SON VARIAS SE MUESTRA DE QUE NIVEL SUPERIOR QUIERE (Categoria) */
		if (count($groupActual) > 1) {
			$groupUp = $collect->groupBy($nivelUp);
			/** 2.2.1 SI LA CATEGORIA ES UNICA SE MUESTRA TODOS LOS TITULOS COINCIDENTES EN LA BUSQUEDA  */
			if (count($groupUp) == 1) {
				$opciones_nivel       = 1;
				$respuesta            = self::creaRespuesta($groupActual, $opciones_nivel);
				$ctx->respuesta       = $respuesta->respuesta;
				$ctx->opciones_nivel  = $respuesta->opciones_nivel;
				$ctx->status          = $respuesta->status;
			}
			/** 2.2.2 SI SON VARIAS CATEGORIAS SE MUESTRA ESTAS CATEGORIAS */
			if (count($groupUp) > 1) {
				$opciones_nivel       = 0;
				$respuesta            = self::creaRespuesta($groupUp, $opciones_nivel);
				$ctx->respuesta       = $respuesta->respuesta;
				$ctx->opciones_nivel  = $respuesta->opciones_nivel;
				$ctx->status          = $respuesta->status;
			}
		}

		return $ctx;
	}

	/** LOCAL
	 * Del nivel que se encuentra, se baja al nivel unico y se genera las opciones para las repsuestas
	 */
	public static function bajarNivelUnico($data, $nivel) {
		$newGroupDown = [];
		$unico = true;

		while ($unico && $nivel <= 3) {
			$newGroupDown = $data->groupBy("nivel{$nivel}");
			if (count($newGroupDown) == 1)
				$nivel++;
			if (count($newGroupDown) > 1)
				$unico = false;
		}
		$nivel = ($nivel > 3) ? 3 : $nivel;
		return self::creaRespuesta($newGroupDown, $nivel);
	}

	/** LOCAL 
	 * Genera el listado de opciones para las respuestas interactivas
	 */
	public static function creaRespuesta($asocArray, $opciones_nivel) {
		$opciones = [];
		foreach ($asocArray as $key => $group) {
			$opt = (object)[];
			$opt->opcion = $key;
			$opciones[] = $opt;
		}

		$tipoMensaje = in_array($opciones_nivel, [0, 1, 2]) ? 'opciones' : (($opciones_nivel == 3 && count($opciones) > 1) ? ['varios_resultados', 'resultados'] : ['un_resultado', 'resultados']);
		$status = in_array($opciones_nivel, [0, 1, 2])      ? 'opciones' : (($opciones_nivel == 3 && count($opciones) > 1) ? 'varios_resultados' : 'un_resultado');
		return (object)[
			'respuesta'      => [
				array('tipo' => 'normal', 'contenido' => self::mensajeAleatorio($tipoMensaje)),
				array('tipo' => 'opcion', 'contenido' => $opciones, 'opciones_nivel' => $opciones_nivel),
			],
			'opciones_nivel' => $opciones_nivel,
			'status'         => $status,
		];
	}


	/**  API REST POST
	 * PARA SALUDAR
	 */
	public function xpMensaje(WP_REST_Request $req) {
		global $chatbot;
		$urlChatBotPlugin = $chatbot->url;//$this->params()->urlPluginSelf;

		$solicita = $req['solicita'];

		if ($solicita == 'saludo') {
			$saludo = self::mensajeAleatorio('saludo');
			// $gif    = self::mensajeAleatorio('gifs', $urlChatBotPlugin);

			$ctx = (object)[];
			$ctx->respuesta = [
				array('tipo' => 'normal', 'contenido' => $saludo),
				array('tipo' => 'gif', 'contenido' => ''),
			];
			$ctx->status = 'saludo';

			return [
				'ctx' => $ctx,
			];
		}
	}

	/** LOCAL 
	 * Mensajes varios , que se seleccionaran de manera aleatoria segun sea la causa 
	 * $tipoMensaje array[string] o string
	 */
	public static function mensajeAleatorio($tipoMensaje, $urlChatBotPlugin = null) {
		$hora   =  date('H', time() - 4 * 60 * 60);
		$saludo =  in_array($hora, range(2, 11)) ? 'buen día' : (in_array($hora, range(12, 18))  ? 'buenas tardes' : 'buenas noches');
		$listaMensajes = [
			'saludo'                => [
				"Hola {$saludo}, Saludos Cordiales",
				"Hola {$saludo} , Soy el Asistente Virtual, en que te puedo ayudar",
				"Saludos, Soy el Asistente Virtual, y responderé tus preguntas",
				"Hola {$saludo}. puedes hacerme cualquier pregunta que tengas",
				"Saludos, {$saludo} soy el Asistente Virtual, y responderé cualquier duda que tengas",
				"Un gusto saludarte, yo soy el Asistente Virtual, que te ayudará en tus dudas",
			],
			'opciones'              => [
				'Sobre que tema relacionado quieres obtener más información',
				'Especificamente de que tema quieres conocer más:',
				'Se tienen las siguientes opciones relacionadas ',
				'Escoge una de las siguientes opciones que estan dentro tu interes',
				'Dentro de ese contexto, se tienen las siguientes opciones relacionadas',
			],
			'resultados'            => [
				'Respecto a su pregunta, se tiene que:',
				'Según la pregunta que hizo, se tiene que:',
			],
			'varios_resultados'     => [
				'Los resultados relacionados con su pregunta son:',
				'Se tienen lo siguientes puntos',
			],
			'un_resultado'          => [
				'El resultado de su pregunta es',
				'Bueno… la respuesta a su pregunta sería:',
				'La respuesta que buscas es:',
			],
			'no_encontrado'         => [
				'Lo siento, no pude entender tu pregunta, vuelve a elaborarla por favor',
				'No se encontró ninguna coincidencia, con esas caracteristicas. Por favor realiza tu pregunta de fomra mas simple',

			],

		];

		$mensajesSel = [];
		if (is_array($tipoMensaje))
			foreach ($tipoMensaje as $tipo) {
				$mensajesSel = array_merge($mensajesSel, (array)$listaMensajes[$tipo]);
			}
		else
			$mensajesSel = $listaMensajes[$tipoMensaje];

		$index = random_int(1, count($mensajesSel)) - 1;
		return $mensajesSel[$index];
	}


	/**API REST POST  
	 * Reliza el entrenamiento a partir de los contenidos , se llena la tabla xp_tokens en funcions de la tabla xfr_textos
	 */
	public function training() {
		global $wpdb;
		# Para evitar un error con $wpdb
		$wpdb = isset($wpdb) ? $wpdb : (object)[];

		set_time_limit(60 * 60 * 2);
		$contenidos = collect($wpdb->get_results("SELECT * FROM xfr_textos"));
		$wpdb->query('DELETE FROM xfr_tokens');

		$wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, '•', '<br>•')");
		// $wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, 'ü', '<br>•')");
		// $wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, '+', '<br>•')");
		$wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, '*', '<br>•')");
		// $wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, '\r\n', '<br/>')");
		$wpdb->query("UPDATE xfr_textos SET nivel3 = REPLACE(nivel3, '\n', '<br>')");
		$i = 0; /* contador para romper el ciclo, en un numero determinado */

		try {
			foreach ($contenidos as $contenido) {
				$stem = [];
				$stem['contenido_id']     = $contenido->id;
				$stem['nivel0_lema']      = Normaliza::lematizaConStemSW($contenido->nivel0);
				$stem['nivel1_lema']      = Normaliza::lematizaConStemSW($contenido->codigo1 . " " . $contenido->nivel1 . " " . $contenido->nivel1_tags);
				$stem['nivel2_lema']      = Normaliza::lematizaConStemSW($contenido->codigo1 . " " . $contenido->nivel2 . " " . $contenido->nivel2_tags);
				$stem['nivel3_lema']      = Normaliza::lematizaConStemSW($contenido->nivel3 . " "  . $contenido->nivel3_tags);
				$wpdb->insert('xfr_tokens', $stem);
				// $i++;
				// if ($i == 5) break;
			}
		} catch (\Exception $e) {
			return (object)array(
				'status' => 'error',
				'msg'    => 'Error , algo inesperado ha sucedido.' . $e->getMessage(),
			);
		}

		return [
			'data' => [],
			'msg' => 'Se realizó el entrenamiento Exitosamente!',
		];
	}


	/** API REST GET PRUEBA */
	public function frase() {
		$texto 			= "El pelicano voló por toda la costa hasta el gran mastil que se encontraba en la punta del velero, asi comenzaria su gran historia..";
		$sw         = getStopWords();
		$swText     = implode(' ', $sw);

		return [
			'texto'           =>  $texto,
			'textoSinSC'      =>  Normaliza::quitaSpecialChars($texto),
			'textoSinSW'      =>  Normaliza::quitaStopwordsDeTexto($sw, $texto),
			'textoStemF'      =>  Normaliza::stemmerFactory($texto),
			'swText'          =>  $swText,
			'swSinSC'         =>  Normaliza::quitaSpecialChars($swText),
			'swStemF'         =>  Normaliza::stemmerFactory($swText),
			'textoLematizado' => Normaliza::lematizaConStemSW($texto),
		];
	}
}
