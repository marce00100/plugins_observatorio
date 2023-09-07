<?php
function get_view_contenidos_migrate($xfrContenidos) {   
  #CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
  

  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
  wp_enqueue_script('lodash.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
  wp_enqueue_script('moment.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/moment/min/moment.min.js', array(), null, true);

ob_start(); ?>
  <div style="min-height: 10vh;"></div>

  <div id="wrap_migrate" class="bg-content frctl" style="min-height: 70vh;">
    <div
      class="bg-white col-xs-12 col-sm-offset-1_ col-sm-12  col-md-offset-1_ col-md-11 col-lg-offset-1 col-lg-10 col-xl-offset-2 col-xl-8  ">
      <h2>Migración Automática</h2>
      <div clasS="mt20 mb20 bg-eee br-a br-greyer br6 p20 flex justify-center wrap font-roboto ">
        <div class="form-group wp40 mr10">
          <label for="sistema" class="form-label">Sistema:</label>
          <select id="sistema" name="sistema" __migrate_field="sistema" class="form-control">
            <option value="observatorio">observatorio</option>
            <!-- <option value="magistratura">magistratura</option> -->
          </select>
        </div>
        <div class="form-group wp40">
          <label for="tipo_contenido" class="form-label">Tipo Contenido</label>
          
          <select id="sistema" name="sistema" __migrate_field="tipo_contenido" class="form-control">
            <option value="contenidos">CONTENIDOS</option>
            <option value="noticias">noticias</option>
            <option value="actividades">actividades</option>
            <option value="biblioteca_juridica">BIBLIOTECA_JURIDICA</option>
            <option value="normas">normas</option>
            <option value="jurisprudencias">jurisprudencias</option>
            <option value="recomendaciones">recomendaciones</option>
            <option value="jurisprudencia_relevante">jurisprudencia_relevante</option>
            <option value="_">_</option>
            <option value="sentencias_premiadas">sentencias_premiadas</option>
            <!-- <option value="magistratura">magistratura</option> -->
          </select>
          <div class="fs11">
            <em>OBSERVATORIO: contenidos: noticias, actividades; biblioteca_juridica: normas, jurisprudencias,
              recomendaciones, jurisprudencia_relevante </em>
          </div>
        </div>
        <hr class="wp100">
        <div class="flex justify-around wp100">
          <button __migrate class="btn btn-md bg-primary--40 br-a br-info text-eee">MIGRAR MÓDULO -
            TipoContenido</button>
          <!-- <button __migrate_full class="btn btn-md bg-danger--40 br-a br-greyer text-eee hide">MIGRAR TODO</button> -->
        </div>
        <div __migrate_msg class="p10 mt20 hide wp100 text-center"></div>
      </div>
    </div>
  </div>

<script>
  jQuery(function ($) {     
    let ctxG = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      content: '#wrap_migrate',
      
    }

    let funs = {
      cargarModulos: () => {
        
      },
      migrateTables: () => {
        // let tipo_contenido = $("[__migrate_field=tipo_contenido]").val();
        $("[__migrate_msg]").addClass("hide");
        funs.spinner();
        let objSend = xyzFuns.getData__fields('__migrate_field');
        $.post(`${ctxG.rutabase}/migrate-tables-to-xfr-contenidos-format`, objSend,
          (resp) => {
            $("[__migrate_msg]").removeClass('hide bg-success--20 bg-danger--20  br-a br-dark')
            .addClass(resp.status == 'ok' ? 'bg-success--20  br-a br-dark': 'bg-danger--20  br-a br-dark').html(resp.msg);
            funs.spinner(0);
          })

      },


      spinner: (obj = {}) => {
        xyzFuns.spinner(obj, ctxG.content)
      },

    }

    //-------------------- Listeners  --------------------------------

    let listen = () => {
      
      /* DEL CONTENEDOR */
      $(ctxG.content)
        .on('click', '[__migrate]', () => {
          funs.migrateTables();
        })
        .on('click', '[__migrate_full]', () => {
          // funs.migrateTables();
        })

    }

    /**
     * Inicializa 
     */
    let init = () => {
    }

    listen();
    init();
  })
</script>

<?php
  return ob_get_clean();
}
?>