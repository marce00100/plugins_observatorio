<?php
function get_view_contenidos_migrate($xfrContenidos) {   
  #CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS
  

  # JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS
  wp_enqueue_script('lodash.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
  wp_enqueue_script('moment.min.js'             , $xfrContenidos->core_url . 'assets/libs-ext/moment/min/moment.min.js', array(), null, true);

ob_start(); ?>

    <div id="wrap_migrate" class="bg-content frctl">
      <div clasS="mt20 mb20 bg-eee br-a br-greyer br6 p20 flex justify-center flex-wrap font-roboto ">
        <div class="form-group wp40 mr10">
          <label for="sistema" class="form-label">Sistema:</label>
          <select id="sistema" name="sistema" __migrate_field="sistema" class="form-control">
            <option value="observatorio">observatorio</option>
            <option value="magistratura">magistratura</option>
          </select>
        </div>
        <div class="form-group wp40">
          <label for="tipo_contenido" class="form-label">Tipo Contenido</label>
          <input id="tipo_contenido" name="tipo_contenido" type="text" __migrate_field="tipo_contenido"
            class="form-control">
        </div>
        <hr class="wp100">
        <div class="flex justify-space-around wp100">
          <button __migrate class="btn btn-md bg-success--40 br-a br-success text-eee">MIGRAR MÓDULO -
            TipoContenido</button>
          <button __migrate_full class="btn btn-md bg-danger--40 br-a br-greyer text-eee">MIGRAR TODO</button>
        </div>
        <div __migrate_msg class="p10 mt20 hide wp100 text-center"></div>
      </div>
    </div>

<script>
  jQuery(function ($) {     
    let ctxG = {
      rutabase: xyzFuns.urlRestApiWP + 'cont/v1',
      content: '#wrap_migrate',
    }

    let funs = {
      migrateTables: () => {
        let tipo_contenido = $("[__migrate_field=tipo_contenido]").val();
        $("[__migrate_msg]").addClass("hide");
        funs.spinner();
        let objSend = xyzFuns.getData__fields('__migrate_field');
        $.post(`${ctxG.rutabase}/migrate-tables-to-xfr-contenidos-format`, objSend,
          (resp) => {
            $("[__migrate_msg]").removeClass('hide bg-light br-a br-danger br-success')
            .addClass(resp.status == 'ok' ? 'bg-light br-a br-success': 'bg-light br-a br-danger').html(resp.msg);
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