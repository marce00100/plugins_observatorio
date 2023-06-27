http://reactivamype.pnud.bo/wp-json/chatbot/v1/xp-mensaje
ENVIAR_MENSAJE = {
    ruta: "http://reactivamype.pnud.bo/wp-json/chatbot/v1/xp-mensaje",
    metodo: POST,    
    comentarios: "Metodo para solicitar un mensaje predefinido, puede ser Saludo, Opciones, Error, No se encuentra, Fin de ciclo, video, Gif, imagen",
    envia_al_server: {
        solicita: 'saludo',        
    },
    respuesta_server: {
        "ctx": {
          "respuesta": [
            {
              "tipo": "normal",
              "contenido": "Buenas noches, Soy IO, en que te puedo ayudar"
            },
            {
              "tipo": "gif",
              "contenido": "<video src=\"http://reactivamype.pnud.bo/wp-content/plugins/xyz-xp-chatbot/fcl-xp-chatbot/assets/animations/io_back_colors.mp4\" border=\"0\" loop autoplay style=\"width: 400px; height: 180px\" />"
            }
          ],
          "status": "saludo"
        }
    }

}

ENVIAR_REQUERIMIENTO = {
    ruta: "http://reactivamype.pnud.bo/wp-json/chatbot/v1/xp-request",
    metodo: POST,
    comentarios: "Sirve para responder a cualquier pregunta por parte de un usuario, tambien en caso de enviar vacio, responde con la exploracion guiada del arbol de nodos ",
    envia_al_server: {        
            "ask_first": "Como usar los guantes",
            "ask_nivel" : null,
            "pregunta" : "",
            "pregunta_nivel" : null       

    },
    respuesta_server: {
        
        "ctx": {
            "respuesta": [
                {
                    "tipo": "normal",
                    "contenido": "Dentro de ese contexto, se tienen las siguientes opciones relacionadas"
                },
                {
                    "tipo": "opcion",
                    "contenido": [
                        {
                            "opcion": "Guantes de látex"
                        },
                        {
                            "opcion": "Guantes de goma"
                        }
                    ],
                    "opciones_nivel": 2
                }
            ],
            "opciones_nivel": 2,
            "status": "opciones",
            "ask_first": "guant",
            "ask_nivel": 2,
            "pregunta": "",
            "pregunta_nivel": null,
            "data": [
                {
                    "contenido_id": "741",
                    "nivel0_lema": "informacion general",
                    "nivel1_lema": "epp-001 equip proteccion personal general epp",
                    "nivel2_lema": "epp-001 guant latex",
                    "nivel3_lema": "guant latex reduc neces lav man solucion jabon continu",
                    "concat": null,
                    "nivel0": "Información general",
                    "codigo": "EPP-001",
                    "nivel1": "Equipos de proteccion personal",
                    "nivel2": "Guantes de látex",
                    "nivel3": "El uso de guantes de látex no reduce la necesidad de un lavado de manos con solución jabonosa continuamente."
                },
                {
                    "contenido_id": "742",
                    "nivel0_lema": "informacion general",
                    "nivel1_lema": "epp-001 equip proteccion personal general epp",
                    "nivel2_lema": "epp-001 guant latex",
                    "nivel3_lema": "coloc guant lav man solucion jabon",
                    "concat": null,
                    "nivel0": "Información general",
                    "codigo": "EPP-001",
                    "nivel1": "Equipos de proteccion personal",
                    "nivel2": "Guantes de látex",
                    "nivel3": "Antes de colocarse los guantes lavarse las manos con solución jabonosa."
                },
                {
                    "contenido_id": "743",
                    "nivel0_lema": "informacion general",
                    "nivel1_lema": "epp-001 equip proteccion personal general epp",
                    "nivel2_lema": "epp-001 guant latex",
                    "nivel3_lema": "coloc guant latex man calz perfect verific huec",
                    "concat": null,
                    "nivel0": "Información general",
                    "codigo": "EPP-001",
                    "nivel1": "Equipos de proteccion personal",
                    "nivel2": "Guantes de látex",
                    "nivel3": "Se debe colocar los guantes de látex una mano primero y luego la otra haciendo calzar perfectamente y verificando que no existan huecos."
                }, 
                {"etc": "etc....."}
                
            ],

        }
        

    }

}

ENTRENAMIENTO = {
    ruta: "http://reactivamype.pnud.bo/wp-json/chatbot/v1/training",
    metodo: GET,
    comentarios: "Realiza el entrenamiento y llenado de la informacion dentro de la tabla xp_tokens, a partir de la informacion contenida en la tabla xp_contenidos ",
    respuesta_server: {
        status: 'ok',
        msg: 'Se realizo el entrenamiento con exito'
    }

}

