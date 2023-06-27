<?php
function agregarBotonSearch() {  
	// Verificar que jQuery esté encolado en el sitio
	if (wp_script_is('jquery', 'done')) {
?>
<style>
  /* --------  para obser -------- */
  .boton_search{
    color: #eeeeee;
    padding-top: 25px!important;
    padding-bottom: 25px!important;
  }
  .boton_search:hover{
    background-color: #263f6d;
    /* border: 1px solid rgb(228, 228, 228); */
    
  }

  /** --------  para magis -------- */
  /* .boton_search:hover{
    background-color: #fafafa;
    border: 1px solid rgb(228, 228, 228);
  } */
</style>
<script>
  jQuery(document).ready(function ($) {
    let ctxG = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      contentMenu: '.main-navigation .inside-navigation',
    }

    let funs = {
      adecuarSiteMenu: () => {
        $(ctxG.contentMenu).css('padding', '0 40px' )  ;
      },

      agregaBotonSearch: () => {
        let botonSearchHtml = /*html*/`
          <span class="frctl">
            <div>
              <div __search_box clasS="br-a br-light br8 bg-light font-roboto" style="position: absolute; top: 5px; right: 0px; z-index: 101; height: 95%; width: 80vw;display: none ">
                <div clasS="flex align-center justify-evenly pn ph40" style=" height: 100%;"> 
                  <input __search_field class="" style="border: none; width: 80% !important; border-bottom: 1px solid lightgray; padding: 5px 20px; background-color: transparent;"> 
                  <button __search_action="buscar" class="btn btn-md bg-light br-a br-greyer br8">Buscar</button>
                  
                  <span __search_action="close_search" class="cursor p10 fs20 fw600 " style="position: absolute; top: 0; right: 0px; ">&times;</span>
                </div>
                <div __search_results class="fs12 bg-white br-a br-light p10 text-dark">

                </div>
              </div>
              <span __search_action="open_search" style="position:absolute; top:3px; right:0px; z-index: 100; display: inline-block" 
              class=" p10 cursor br6 boton_search">
                    <i class="glyphicons glyphicons-search fa-lg"></i>
              </span>
            </div>
          </span>
          `
        $(ctxG.contentMenu).append(/*html*/`${botonSearchHtml}`);
      },
      buscar: () => {
        let text = $("[__search_field]").val();
        $.post(`${ctxG.rutabase}/get-contents`, {texto_busqueda : text}, function(res){
          let htmlResults = /*html*/`
                    <h4>Resultados</h4>
                    <hr>`;
          res.data.forEach(function(item){
            let fechaPub = new Date(item.fecha_publicacion);
            let fechaPublicacionFormat = `${fechaPub.getDay()}/${fechaPub.getMonth() + 1}/${fechaPub.getFullYear()}`;
            let html = /*html*/`
              <div class="p5 mt5" style="border-bottom: 1px solid #ccc">
                <h5 class="cursor" __contenido_id="${item.id_contenido}" __tipo_contenido="${item.tipo_contenido}"><a href="../${item.tipo_contenido}?id=${item.id_contenido}" >${item.titulo}</a> </h5>
                <em>Publicado en: ${fechaPublicacionFormat}; Sección: ${item.tipo_contenido}</em>
                <div>${item.resumen}</div>
              </div>
            `;
            htmlResults += html;
          })
          $("[__search_results]").html(htmlResults);
        });

      },
      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },

    }


    let listen = () => {

    /* DEL CONTENEDOR */
    $(ctxG.contentMenu)
    //   /** Click en el boton search  */

      .on('click', '[__search_action]', (e) => {
        let accion = $(e.currentTarget).attr('__search_action');
        if(accion == 'open_search' || accion == 'close_search')
          $("[__search_box]").toggle();
        if(accion == 'buscar')
          funs.buscar();
      })
      .on('click', "[__contenido_id]", function(){

      })

    // /** DEL MODAL */
    // $(ctxG.modal)
    //   /* Cancel Modal*/
    //   .on('click', "[__cerrar], .close", () => {
    //     xyzFuns.closeModal();
    //   })
    //   /* del alert */
    //   .on('click', "[__alert_msg] .close", (e) => {
    //     $(e.currentTarget).closest('[__alert_msg]').remove();
    //   })
    //   /* clock en navegasion siguiente atras*/
    //   .on('click', "[__nav]", (e) => { 
    //     let nav = $(e.currentTarget).attr('__nav');
    //     funs.navegar(nav);
    //   })
    }


    let init = (() => {
      listen();
      funs.adecuarSiteMenu();
      funs.agregaBotonSearch();

    })();


  });
</script>


<?php
	}
}


