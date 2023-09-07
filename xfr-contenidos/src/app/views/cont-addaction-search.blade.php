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
    let ctxGSearch = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      contentMenu: '.main-navigation .inside-navigation',
      dirRaiz: "<?php echo get_home_url(); ?>",
    }

    let funsSearch = {
      adecuarSiteMenu: () => {
        $(ctxGSearch.contentMenu).css('padding', '0 40px' )  ;
      },

      agregaBotonSearch: () => {
        let botonSearchHtml = /*html*/`
          <div class="frctl">
            <div>
              <div __search_box clasS="br-a br-light br8 bg-light font-roboto" style="position: absolute; top: 5px; right: 0px; z-index: 101; height: 95%; width: 80vw;display: none ">
                <div clasS="flex align-center justify-evenly pn ph40" style=" height: 100%;"> 
                  <input __search_field class="" style="border: none; width: 80% !important; border-bottom: 1px solid lightgray; padding: 5px 20px; background-color: transparent;"> 
                  <button __search_action="buscar" class="btn btn-md bg-light br-a br-greyer br8">Buscar</button>
                  
                  <span __search_action="close_search" class="cursor p10 fs20 fw600 " style="position: absolute; top: 0; right: 0px; ">&times;</span>
                </div>
                <div __search_results class="fs12 bg-white br-a br-light p10 text-dark" style= "box-shadow: 0px 3px 6px 0px #000;">

                </div>
              </div>
              <span __search_action="open_search" style="position:absolute; top:3px; right:0px; z-index: 100; display: inline-block" 
              class=" p10 cursor br6 boton_search">
                    <i class="glyphicons glyphicons-search fa-lg"></i>
              </span>
            </div>
          </div>
          `
        $(ctxGSearch.contentMenu).append(/*html*/`${botonSearchHtml}`);
      },
      buscar: () => {
        funsSearch.spinner();
        let text = $("[__search_field]").val();
        $.post(`${ctxGSearch.rutabase}/get-contents`, {texto_busqueda : text}, function(res){
          let htmlResults = /*html*/`
                    <h4 class="text-theme1--20">Resultados</h4>
                    <hr>`;
          res.data.forEach(function(item){
            // let fechaPub = new Date(item.fecha_publicacion);
            // let fechaPublicacionFormat = `${fechaPub.getDay()}/${fechaPub.getMonth() + 1}/${fechaPub.getFullYear()}`;
            let html = /*html*/`
              <div class="p5 mt5" style="border-bottom: 1px solid #ccc">
                <h5 class="cursor" __contenido_id="${item.id_contenido}" __tipo_contenido="${item.tipo_contenido}">
                  <a class="text-333 fw600" href="${ctxGSearch.dirRaiz}/${item.tipo_contenido}/?id=${item.id_contenido}" >${item.titulo}</a> 
                </h5>
                <em>Publicado en: ${item.fecha_publicacion}; Sección: ${item.tipo_contenido}</em>
                <div>${item.resumen}</div>
              </div>
            `;
            htmlResults += html;
          })
          $("[__search_results]").html(htmlResults).removeClass('hide');
          funsSearch.spinner(0);
        });

      },
      limpiarBuscar: () => {
        $("[__search_field]").val('');
        $("[__search_results]").html('').addClass('hide');
      },
      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxGSearch.content)
      },

    }


    let listenSearch = () => {

    /* DEL CONTENEDOR */
    $(ctxGSearch.contentMenu)
    //   /** Click en el boton search  */

      .on('click', '[__search_action]', (e) => {
        let accion = $(e.currentTarget).attr('__search_action');
        if(accion == 'open_search' || accion == 'close_search'){
          funsSearch.limpiarBuscar();
          $("[__search_box]").toggle();
          $("[__search_field]").focus();
        }
        if(accion == 'buscar')
          funsSearch.buscar();
      })
      .on('keypress', "[__search_field]", function(e){
        if (event.which === 13) {
          event.preventDefault(); 
          // $("[__search_box]").toggle();
          funsSearch.buscar();
        }
      })

    }


    let initSearch = (() => {
      listenSearch();
      funsSearch.adecuarSiteMenu();
      funsSearch.agregaBotonSearch();

    })();


  });
</script>


<?php
	}
}


