<?php
# $vars contiene un array con valores de url, etc
function get_view_st_dash_public($vars) {


/** CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS     CARGA DE ESTILOS        CSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSSCSS */


/*  PIVOTTABLE distribuido y modificado */
wp_enqueue_style('pivot_dist.css'   , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/pivottable-dist/pivot.css', array(), '1.0.4', 'all');
wp_enqueue_style('pivot__css'       , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable/pivot__.css', array(), '1.0.8', 'all');

/* Carga estilo comun select2 */
wp_enqueue_style('select2_sty'      , $vars['core_url'] . 'assets/libs-ext/select2-4.1.0/dist/css/select2.min.css');  
// wp_enqueue_style('datatable_sty' , $vars['core_url'] . 'assets/libs-ext/datatables-1.10.25/css/jquery.dataTables.min.css');    

/* Carga estilo comun theme para plugin */
wp_enqueue_style('theme_lite'       , $vars['core_url']     . 'assets/libs-ext/sty-02/assets/skin/default_skin/css/theme_lite.css', array(), '1.0.4', 'all');

/* Carga estilo del dash */
wp_enqueue_style('style-dash'       , $vars['datadash_url'] . 'frctl-datadash/assets/css/st-dash.css', array(), '1.0.200003', 'all');



/** JSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJSJS       CARGA DE JS          JSJSJSJjsjsjsjsSJSJSJSJSJSJSJSJSJSJSJSJSJSJS */

/* carga Highcharts */
wp_enqueue_script('hightcharts'      , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/hightcharts-6.0.4-code/highcharts.js', array(), null, true);
wp_enqueue_script('hightcharts_3d'   , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/hightcharts-6.0.4-code/highcharts-3d.js', array(), null, true);
wp_enqueue_script('hightcharts_exp'  , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/hightcharts-6.0.4-code/modules/exporting.js', array(), null, true);

// wp_enqueue_script('d3_cdn'             , 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js', array(), null, true);
// wp_enqueue_script('plyly_cdn'          , 'https://cdn.plot.ly/plotly-basic-latest.min.js', array(), null, true);
/* Carga jqueryUI para drag pivottable */
wp_enqueue_script('jqueryUI_de_pivot'  , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/pivottable-dist/jquery-ui.min.js', array(), null, true);
 


/* ================>>   carga pivot table modificados */
// wp_enqueue_script('pivot__'               , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable-2.23.0/dist/pivot_23_opt.js', array(), null, true);
//wp_enqueue_script('pivot__'          , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/pivottable-dist/pivot.js', array(), null, true);
wp_enqueue_script('pivot__new'       , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable/pivot___.js', array(), '2.1.0001001', true);
wp_enqueue_script('pivot__es'        , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable/pivot___.es.js', array(), '2.1.0112001', true);

/** Externals  */
// wp_enqueue_script('pivotRenderD3'         , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable-2.23.0/dist/d3_renderers.js', array(), null, true);
// wp_enqueue_script('pivotRenderPlotly'     , $vars['datadash_url'] . 'frctl-datadash/assets/libs-ext/modify/pivottable-2.23.0/dist/plotly_renderers.js', array(), null, true);

/* librerias comunes */
wp_enqueue_script('xcod_lodash'     , $vars['core_url'] . 'assets/libs-ext/lodash.min.js', array(), null, true);
wp_enqueue_script('select2_js'      , $vars['core_url'] . 'assets/libs-ext/select2-4.1.0/dist/js/select2.min.js', array(), null, true);
// wp_enqueue_script('datatables'   , $vars['core_url'] . 'assets/libs-ext/datatables-1.10.25/js/jquery.dataTables.min.js', array(), null, true);

ob_start(); ?>

<style>

/** Para el select2 de los campos del dataset   */
/* Tamaño de cada item del combo expandible */
ul#select2-campos_dataset-results li {
    font-size: 14px !important;
    font-family: 'Roboto';
    border-bottom: 1px solid #eee !important;
    padding-left: 25px;
}
/* estilo del combo expandido de los seleccionados */
ul#select2-campos_dataset-results li.select2-results__option--selected {
    background-color: #b4d468;
    border-bottom: 1px solid #c1de8b !important;
}
/* estilo de los campos seleccionados en el select */
ul#select2-campos_dataset-container li.select2-selection__choice {
    background-color: #555555;
    color: #eeeeee;
}


/*.pvtTotal, .pvtTotalLabel, .pvtGrandTotal {display: none}*/

/* para mover el footer del slider Rovolution mas abajo, se hace mas alto el main */
main.site-main{
    min-height: 2500px !important;
}


 
</style>
<!-- frctl stadistic dashhboard std -->
<!-- <div class="frctl std" __std style="display: none;">    
    <hr>
    <div class="container-fluid">
        
        <div class="std_titulo" __std_titulo></div>
        <div class="" __std_dash=tablero1 style="position: relative;">           
            <div class="row" >
                <div class=" std-filtro" __std_section=filtro_master>
                </div>
            </div>
            <div class="row" style="display: table;">
                <div class="col-md-5 col-xs-12 " __std_section=cuadrante_a1 >
                </div>
                <div class="col-md-5 col-xs-12 "  __std_section=cuadrante_b1>
                </div>
            </div>
            <div class="row" style="display: table;">
                <div class="col-md-5 col-xs-12 " __std_section=cuadrante_a2>
                </div>
                <div class="col-md-5 col-xs-12 "   __std_section=cuadrante_b2>
                </div>
            </div>
        </div>
        <div __std_notificaciones></div>
        

        <div __espacio_pruebas >
            <button class="btn btn-primary" __crear_config>crear configuraciones de los slaves</button>
            <div __configs></div>
        </div>
    </div>
    
 </div> -->


<div class="frctl std" __std style="display: none;">   
    <hr>
    <div class="">
        <div class="std-titulo" __std_titulo>
        </div>
        <div class="std-tablero" __std_dash=tablero1 style="position: relative;">        
            <div class="std-filtro" __std_section=filtro_master> </div>       
        
            <div class="std-cuadrante " __std_section=cuadrante_a1>  </div>
            <div class="std-cuadrante " __std_section=cuadrante_b1>   </div>  
            <div class="std-cuadrante " __std_section=cuadrante_a2>   </div>
            <div class="std-cuadrante " __std_section=cuadrante_b2>   </div>
        
        </div>
        <div __std_notificaciones></div>


        <!-- quitar hidden para sacar config del salaves -->
        <div __espacio_pruebas  class="hidden">
            <button class="btn btn-primary" __crear_config>crear configuraciones de los slaves</button>
            <div __configs></div>
        </div>
    </div>
    
</div>

<script>
jQuery(function ($) {

    let stdConfigModelo = {
            camposDataset: ['fecha_de_la_denuncia', 'departamento', 'municipio', 'delitos', 'tipos_de_violencia', 'sexo_victima', 'edad_victima'],
            master: {
                selector_wrapper: "[__std_section=filtro_master]",
                wrapper_class: 'std-filtro',
                tipo: "filtro",
                filtros: ['departamento', 'fecha_de_la_denuncia', 'edad_victima', 'delitos' ],
                html: /*html*/`<div __pre></div><div __pivot></div><div __post></div>`,
                config_pivot: {},

            },
            slaves: [
                {
                    selector_wrapper: "[__std_section=cuadrante_a1]",
                    wrapper_class: 'std-cuadro',
                    tipo: "card",
                    html: /*html*/`<div __pre>Numero de Feminicidios</div>
                        <div __pivot></div><div __std_chart></div><div __post></div>`,
                    config_pivot: { "derivedAttributes": {}, "renderers": {}, "hiddenAttributes": [], "hiddenFromAggregators": [], "hiddenFromDragDrop": [], "menuLimit": 500, "cols": [], "rows": [ "delitos" ], "vals": [ "cantidad" ], "rowOrder": "key_a_to_z", "colOrder": "key_a_to_z", "inclusions": { "delitos": [ "FEMINICIDIO" ] }, "unusedAttrsVertical": 85, "autoSortUnusedAttrs": false, "onRefresh": null, "sorters": {}, "aggregatorName": "Cantidad total", "inclusionsInfo": { "delitos": [ "FEMINICIDIO" ] }, "rendererName": "Tabla" }
                },
                {
                    selector_wrapper: "[__std_section=cuadrante_b1]",
                    wrapper_class: 'std-cuadro',
                    tipo: "grafico",
                    config_tipo: {tipoGrafico: 'spline', viewLabels:false}, 
                    html: /*html*/`<div __pre></div>
                        <div __pivot></div><div __std_chart></div><div __post></div>`,
                    config_pivot: { "derivedAttributes": {}, "renderers": {}, "hiddenAttributes": [], "hiddenFromAggregators": [], "hiddenFromDragDrop": [], "menuLimit": 500, "cols": [ "departamento" ], "rows": [ "delitos" ], "vals": [ "cantidad" ], "rowOrder": "key_a_to_z", "colOrder": "value_z_to_a", "exclusions": {}, "inclusions": { "delitos": [ "ABUSO SEXUAL", "ACOSO SEXUAL", "ESTUPRO", "FEMINICIDIO", "VIOLACION", "VIOLACION AGRAVADA", "VIOLACION DE INFANTE NIÑO , NIÑA Y ADOLESCENTE", "VIOLENCIA FAMILIAR Y/O DOMESTICA" ] }, "unusedAttrsVertical": 85, "autoSortUnusedAttrs": false, "onRefresh": null, "sorters": {}, "aggregatorName": "Cantidad total", "inclusionsInfo": {}, "rendererName": "Line Chart" }
                },
                {
                    selector_wrapper: "[__std_section=cuadrante_a2]",
                    wrapper_class: 'std-cuadro',
                    tipo: "grafico",
                    config_tipo: {tipoGrafico: 'column'},
                    html: /*html*/`<div __pre></div>
                        <div __pivot></div><div __std_chart></div><div __post></div>`,
                    config_pivot: { "derivedAttributes": {}, "renderers": {}, "hiddenAttributes": [], "hiddenFromAggregators": [], "hiddenFromDragDrop": [], "menuLimit": 500, "cols": [], "rows": [ "tipos_de_violencia" ], "vals": [ "cantidad" ], "rowOrder": "key_a_to_z", "colOrder": "value_a_to_z", "exclusions": { "sexo_victima": [ "HOMBRE", "MUJER Y HOMBRE" ] }, "inclusions": { "sexo_victima": [ "", "MUJER" ] }, "unusedAttrsVertical": 85, "autoSortUnusedAttrs": false, "onRefresh": null, "sorters": {}, "aggregatorName": "Cantidad total", "rendererName": "Bar Chart", "inclusionsInfo": { "sexo_victima": [ "", "MUJER" ] } }
                },                    
                {
                    selector_wrapper: "[__std_section=cuadrante_b2]",
                    wrapper_class: 'std-cuadro',
                    tipo: "tabla",
                    html: /*html*/`<div __pre></div>
                        <div __pivot></div><div __std_chart></div><div __post></div>`,
                    config_pivot: { "derivedAttributes": {}, "renderers": {}, "hiddenAttributes": [], "hiddenFromAggregators": [], "hiddenFromDragDrop": [], "menuLimit": 500, "cols": [ "departamento" ], "rows": [ "delitos" ], "vals": [ "cantidad" ], "rowOrder": "value_z_to_a", "colOrder": "value_z_to_a",  "inclusions": { "delitos": [ "ABUSO SEXUAL", "ACOSO SEXUAL", "ESTUPRO", "FEMINICIDIO", "VIOLACION", "VIOLACION AGRAVADA", "VIOLACION DE INFANTE NIÑO , NIÑA Y ADOLESCENTE", "VIOLENCIA FAMILIAR Y/O DOMESTICA" ] }, "unusedAttrsVertical": 85, "autoSortUnusedAttrs": false, "onRefresh": null, "sorters": {}, "aggregatorName": "Cantidad total", "inclusionsInfo": { "delitos": [ "ABUSO SEXUAL", "ACOSO SEXUAL", "ESTUPRO", "FEMINICIDIO", "VIOLACION", "VIOLACION AGRAVADA", "VIOLACION DE INFANTE NIÑO , NIÑA Y ADOLESCENTE", "VIOLENCIA FAMILIAR Y/O DOMESTICA" ] }, "rendererName": "Heatmap" }
                }
            ]            
            
    }


    /*-----------------------------------------------------------------------
    *     Objeto principal
    */
    let std = {
        urlApi: xyzFuns.urlRestApi + 'st',
        wrapper: '.frctl.std',
        collection: [],
    }

    /*-----------------------------------------------------------------------
        *      stdC variable que contiene el contexto del Contenido, contenedorPredefinidos, titulos, new update del config
        */
    let stdC = {
        
        /* Obtiene los datos de la BD */
        cargaDatos: (cnf) => {        

            if(!cnf.camposDataset){
                xyzFuns.alertMsg('[__std_notificaciones]', 'No se ha seleccionado ninguna variable o campo.', 'alert alert-danger', 'fas fa-exclamation-triangle fa-2x', '', true);
                return;
            }
            
            xyzFuns.alertMsg('[__std_notificaciones]', ' Cargando...', 'mt40', 'fa fa-pulse fa-3x fa-spinner text-system p30 br8', 'colocar_span_class', false);
            $.post(std.urlApi + '/obtener-dataset', {'campos_dataset':cnf.camposDataset},                     
                function (res) {
                    std.collection      = res.collection;
                    stdPiv.collection   = res.collection;
                    stdPiv.allConfigs = cnf;

                    stdPiv.renderizaDash();
                    xyzFuns.alertMsgClose('[__std_notificaciones]');
                    // stdC.statePage('pivot'); 
                });
        },

        /** Setea la Pagina segun estado, muestra u oculta elementos */
        // statePage: (state) => {
        //     if (state == 'inicial') {
        //         $("[__section=campos_dataset]").show();
        //         $("[__std_notificaciones]").show();
        //     }

        //     if (state == 'pivot') {
        //         $("[__section=contenedor_estadistica]").show();
        //     }
        // }
    };


    /*------------------------- stdPiv variable que contiene el contexto del Pivot   */
    let stdPiv = {
        collection: [],
        allConfigs: {},            

        /** Realiza el renderizado del json config*/
        renderizaDash: () => {
            let cnf = stdPiv.allConfigs;
        
            /* Carga objetos html*/
            $(cnf.master.selector_wrapper).html(cnf.master.html).addClass(cnf.master.wrapper_class);
            /* Carga los slaves en sus wrappers*/
            _.forEach(cnf.slaves, function (item) {
                $(item.selector_wrapper).html(item.html).addClass(item.wrapper_class);
            });

            /* ---------------------------------------------------------------------------------------------------------*/
            /*   ------- HABiLITA la CRACION DE LOS CUADRANTES y generacion del config pata copiarlo -------------------*/
            let creacionConfig = false; 
            /* Eslo mismo que anterior se sobreescribe solo que quita la clase*/
            if (creacionConfig) {
                /* Carga objetos html*/
                $(cnf.master.selector_wrapper).html(cnf.master.html).removeClass(cnf.master.wrapper_class);;
                /* Carga los slaves en sus wrappers*/
                _.forEach(cnf.slaves, function (item) {
                    $(item.selector_wrapper).html(item.html).removeClass(item.wrapper_class);;
                });
            }
            /* ---------------------------------------------------------------------------------------------------------- */

            /* vuelve pivot a cada elementos: Todos los elementos tienen los atributos selector_wrapper, pivot, config_pivot*/
            /* MASTER  desntro de wrapper esta pre pivot post*/
            stdPiv.pivottableUI(`${cnf.master.selector_wrapper} [__pivot]`, cnf.master.config_pivot, true);
            
            /** SLAVES  dentro de wrapper esta pre pivot pos  TODO: COMENTAR Solo es para configurar los cuadrantes***/
            _.forEach(cnf.slaves, function(item){
                stdPiv.pivottableUI(`${item.selector_wrapper} [__pivot]`, item.config_pivot, false);
            });

            /** Una vez renderizados los pivots , Se ocultan los botones y solo se hacen visibles los filtros */
            $(cnf.master.selector_wrapper + ' .pvtUnused li').hide();
            _.forEach(cnf.master.filtros, function(item){
                    /* todos los botones tienen una clase .attr_atributo */
                $(`${cnf.master.selector_wrapper} .attr_${item}`).show();
            })
        },
        /** MAIN PivotUI Crea objetos PivotUI, renders, onRefrsh, configuraciones, estados iniciales  */
        pivottableUI: function (selector, configPivot, esMaster = true) {
            
            // let renderers = $.extend($.pivotUtilities.renderers, $.pivotUtilities.d3_renderers);
            // renderers = $.extend($.pivotUtilities.renderers, $.pivotUtilities.plotly_renderers);
            $(selector).pivotUI(stdPiv.collection, {
                // renderers: renderers,
                // cols: ['departamento'],
                // rows: ['delitos'],
                // vals: ['cantidad'],
                // aggregatorName: "Integer Sum",
                /* Coloca la configuracion */
                cols          : configPivot.cols,
                rows          : configPivot.rows,
                aggregatorName: configPivot.aggregatorName,
                vals          : configPivot.vals,
                inclusions    : configPivot.inclusions,
                rowOrder      : configPivot.rowOrder,
                colOrder      : configPivot.colOrder,
                rendererName  : configPivot.rendererName,
                onRefresh: function (state) {
                    if(!esMaster)
                        return;
                    // ctxG.pivotInstancia = p;
                    // stdPiv.trnDatosDePivot();
                    // stdChart.transformarDatosParaGrafico();
                    // stdChart.graficarH();
                    // if(esMaster)
                    // stdPiv.refreshSlaves(selector, configPivot, state);

                    _.forEach(stdPiv.allConfigs.slaves, function (cogSlave) {
                        stdPiv.refreshSlaves(`${cogSlave.selector_wrapper}`, cogSlave, state);
                        // stdPiv.refreshSlaves(`${item.selector_wrapper} [__pivot]`, item.config_pivot, state);  // ok
                    });
                }
            }, true, "es");
        },
        refreshSlaves: function (selector_wrapper, cogSlave, stateCopy) {
            let configPivot = cogSlave.config_pivot;
            $(`${selector_wrapper}  [__pivot]`).pivotUI(stdPiv.collection,  {
                // cols: stateCopy.cols,
                // rows: stateCopy.rows,
                // aggregatorName: stateCopy.aggregatorName,
                // vals: stateCopy.vals,
                /** Se mantiene la configuracion del slave, Solo se merge los filtros del master, con los de la configuracion inicial del slave*/
                cols          : configPivot.cols,
                rows          : configPivot.rows,
                aggregatorName: configPivot.aggregatorName,
                vals          : configPivot.vals,
                rowOrder      : configPivot.rowOrder,
                colOrder      : configPivot.colOrder,
                rendererName  : configPivot.rendererName,
                inclusions    : $.extend({}, stateCopy.inclusions, configPivot.inclusions) , 
                onRefresh: (stateChild) => {
                     /* Se crea el nuevo atributo de datos tranformados a partir de pivotData*/
                    stateChild.pivotData.dataTransform = stdPiv.trnDatosDePivot(stateChild.pivotData);

                    if(cogSlave.tipo == 'tabla' || cogSlave.tipo == 'card'){
                        $(`${cogSlave.selector_wrapper} [__pivot], ${cogSlave.selector_wrapper} [__std_chart]`).removeClass('hidden');
                        $(`${cogSlave.selector_wrapper} [__std_chart]`).addClass('hidden');

                    } 
                    if(cogSlave.tipo == 'grafico'){
                        $(`${cogSlave.selector_wrapper} [__pivot], ${cogSlave.selector_wrapper} [__std_chart]`).removeClass('hidden');
                        $(`${cogSlave.selector_wrapper} [__pivot]`).addClass('hidden');
                        stdGra.graficarH(`${selector_wrapper}  [__std_chart]`, stateChild.pivotData, cogSlave.config_tipo)
                    }
                    // $(`${selector_wrapper}  [__std_chart]`).highcharts(json);

                }
            }, true, "es");
        },
        /** Tranforma los datos del pivot */
        trnDatosDePivot: function (pivotData) {
            let trnPivot = {
                colDimension: pivotData.colAttrs.length > 0 ? pivotData.colAttrs.join('-') : '',
                rowDimension: pivotData.rowAttrs.length > 0 ? pivotData.rowAttrs.join('-') : '',
                colKeys: pivotData.colAttrs.length > 0 ? pivotData.colKeys : [['total',],],
                rowKeys: pivotData.rowAttrs.length > 0 ? pivotData.rowKeys : [['total',],],
            }

            let trnData = [];
            if (!_.isEmpty(pivotData.tree)) {
                let tree = pivotData.tree;
                for (row in tree) {
                    for (col in tree[row]) {
                        var item = {};
                        arg = tree[row][col];
                        item['valor'] = arg.value();
                        item[trnPivot.colDimension] = col;
                        item[trnPivot.rowDimension] = row;
                        trnData.push(item);
                    }
                }
            }
            if (_.isEmpty(pivotData.tree) && !_.isEmpty(pivotData.colTotals)) {
                trnData = _.map(pivotData.colTotals, function (item, k) {
                    return {
                        valor: item.value(),
                        [trnPivot.colDimension]: k,
                        [trnPivot.rowDimension]: 'total',
                    }
                })
            }
            if (_.isEmpty(pivotData.tree) && !_.isEmpty(pivotData.rowTotals)) {
                trnData = _.map(pivotData.rowTotals, function (item, k) {
                    return {
                        valor: item.value(),
                        [trnPivot.rowDimension]: k,
                        [trnPivot.colDimension]: 'total',
                    }
                })
            }
            console.log(trnData);
            trnPivot.data = trnData;
            return trnPivot;

        },

    }


    let stdGra = {
        graficarH: function (selector, pivotData, opcionesChart) {
            opcionesChart = $.extend({tipoGrafico: 'spline', viewLabels: false}, opcionesChart);
            let datosGraph = {};
            let dataTrn = pivotData.dataTransform;
            datosGraph.categorias = dataTrn.colKeys.map(function (cat, key) {
                return cat.join('-');
            });

            datosGraph.series = _.chain(dataTrn.data).groupBy(function (item) {
                                return item[dataTrn.rowDimension]
                                }).map(function (setDatos, key) {
                                    serie = {};
                                    serie.name = key;
                                    /* con valores ceros los discontinuos */
                                    serie.data = datosGraph.categorias.map(function (elem) {
                                        var s = { name: elem, y: 0 };
                                        setDatos.forEach(function (sd) {
                                            if (sd[dataTrn.colDimension] == elem) {
                                                var num;
                                                if (pivotData.aggregatorName[0] == "%") /* los porcentajes empiezan con %*/
                                                    num = parseFloat((Math.round(sd.valor * 100 * 10) / 10).toString());
                                                else
                                                    num = sd.valor;
                                                s.y = num;
                                            }
                                        });
                                        return s;
                                    });
                                    return serie;
                                }).value();

            
            let tipoGrafico    = opcionesChart.tipoGrafico.split('-') ;
            let stacked        = tipoGrafico[1] == 'stacked' ? 'normal' : (tipoGrafico[1] == 'stackedp' ? 'percent' : '');
            let viewLabels     = opcionesChart.viewLabels;
            let subtituloChart = `${pivotData.aggregatorName} por ${dataTrn.colDimension} ${dataTrn.rowDimension == '' ? '' : ' vs. ' + dataTrn.rowDimension}`;
            let tituloEjeY     = `${pivotData.aggregatorName} (${pivotData.valAttrs.join('-')})`;
            let tool           = '{series.name}: <b>{point.y:.1f}</b> ';

            if (tipoGrafico[1]) {
                tool += '<br>porcentaje: <b>{point.percentage:.1f} %</b>';
            }

            // colores= [
            // '#E86D00', '#FFB97F', '#E8E400', '#80699B', '#00E820',
            // '#4572A7', '#AA4643', '#89A54E', '#70E800', '#3D96AE',      
            // '#00E8D6', '#00A5E8', '#0054E8', '#A013E6', '#E800CF', 
            // '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92', '#E80000',
            // '#E8007B', '#FF766D', '#EDFF6D', '#8AFF6D', '#89FFEA',
            // '#FF72F4', '#84345E', '#348445', '#C4D21C', '#9C0000'
            // ];

            colores= [
                '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4',
                '#E86D00', '#FFB97F', '#E8E400', '#80699B', '#00E820',
                '#4572A7', '#AA4643', '#89A54E', '#70E800', '#3D96AE', 
                '#00E8D6', '#00A5E8', '#0054E8', '#A013E6', '#E800CF', 
                '#E8007B', '#FF766D', '#EDFF6D', '#8AFF6D', '#89FFEA',
            ],
            coloresAleatorios = _.chain(colores).map(function (color) {
                                        return { id: _.random(100), color: color }
                                    }).sortBy('id').map(function (obj) {
                                        return obj.color
                                    }).value();

            Highcharts.setOptions({
                // colors: colores,
                // colors: coloresAleatorios,
            });

            let json = {
                chart: {
                    type: tipoGrafico[0],
                    zoomType: 'xy',
                },
                title: { text: null },
                subtitle: { text: subtituloChart },
                xAxis: {
                    type: 'category',
                    categories: datosGraph.categorias,
                    labels: {
                        style: {
                            color: '#444444'
                        }
                    }
                },
                yAxis: {
                    title: { text: tituloEjeY, 
                    style: {color: '#555555'}
                    }
                },
                tooltip: { pointFormat: tool, },
                plotOptions: {
                    /* Es comun para todas las series*/
                    series: {
                        marker: {
                            symbol: 'circle',
                            radius: 1,
                        },
                        stacking: stacked,
                        dataLabels: {
                            enabled: viewLabels,
                            format: tipoGrafico[0] != 'pie' ? '{y:.1f}' : '<b>{point.name}</b>: {point.percentage:.1f} %' ,
                            color: '#333333'
                        }
                    },
                    // column: {
                    //     stacking: stacked,
                    //     dataLabels: {
                    //         enabled: true,
                    //         color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#ccc'
                    //     }
                    // },
                    // areaspline: {
                    //     stacking: stacked,
                    //     // lineColor: '#eee',
                    //     // lineWidth: 1,
                    // },
                    pie: {
                        innerSize: '60%',
                        depth: 45,
                        allowPointSelect: true,
                        cursor: 'pointer',
                    },
                },
                series: datosGraph.series,
            }
            $(selector).highcharts(json);
        }

    }


    /**
     *  FUNCIONES
     * */
    let stdFuns = {
        
        inicializaControles: () => { 
            stdC.cargaDatos(stdConfigModelo);
        },            
    }


    /**
     * LISTENERS 
     */
    let stdListeners = () => {
        /** ENVOLTURA CSS para selectores */
        $(std.wrapper)

        /** Cuando se hace click en la x de los mensajes de alertas*/
        .on('click', '.close, .cancel', function (e) {
            $(e.currentTarget).closest("[__alert_msg]").remove();
        })


        /** Cerrar opciones cuando se hace click fuera */
        $(document).mouseup(function(e){
            let container = $(".pvtFilterBox");            
            // si el click no es en el contenedor ni en un hijo del contenedor ni en la flecha de pvtTriangle
            if(!container.is(e.target) && container.has(e.target).length === 0 && !$(e.target).hasClass("pvtTriangle") ) {
                container.hide();
            }
        });

        

        /* DE PRUEBAS CREA configs de SLAVES */
        $("[__crear_config]").click(function(){
            slaves = stdPiv.allConfigs.slaves;

            let configs = _.reduce(slaves,  function(carry, slv){

                selectorPivot = slv.selector_wrapper + ' [__pivot]';
                let config = $(selectorPivot).data("pivotUIOptions");
                config_copy = JSON.parse(JSON.stringify(config));
                //delete some values which are functions
                delete config_copy["aggregators"];
                //delete some bulky default values
                delete config_copy["rendererOptions"];
                delete config_copy["localeStrings"];
                delete config_copy["pivotData"];
                config_copy = JSON.stringify(config_copy, undefined, 2);

                return carry + /*html*/`<h2>${selectorPivot}</h2><div>${config_copy}</div><br><br><hr>`;
            }, '');


            $("[__configs]").html(configs);
        })
        
    };



    let stInit = (() => {
        stdListeners();
        stdFuns.inicializaControles();
        setTimeout(function(){
            $("[__std]").fadeIn(2000);
        }, 2000)
    })();

})

  
</script>

<?php
    return ob_get_clean();
}
?>