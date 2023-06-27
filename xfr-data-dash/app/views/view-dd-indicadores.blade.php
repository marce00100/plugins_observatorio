<?php
# $vars contiene un array con valores de url, etc
function get_view_dd_indicadores($datadash) {

/**  CARGA DE ESTILOS */

/* Carga estilo comun select2 */
	wp_enqueue_style('select2.min.css'      , $datadash->core_url . 'assets/libs-ext/select2-4.1.0/dist/css/select2.min.css');   

	/* =================>> Carga estilo para PIVOTTABLE distribuido y modificado */
	wp_enqueue_style('pivot.css'       			, $datadash->url . 'app/assets/libs-ext/pivottable-dist/pivot.css', array(), '1.0.4', 'all');
	wp_enqueue_style('pivot__.css'          , $datadash->url . 'app/assets/libs-ext/modify/pivottable/pivot__.css', array(), '1.0.5', 'all');

	/* LeaftLEt*/
	// wp_enqueue_style('leftlet.css'          , $datadash->url . 'app/assets/libs-ext/leaflet1.7.1/leaflet.css', array(), '1.0.4', 'all');
	// wp_enqueue_style('leaflet.awesome-markers.css'   , "https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css", array(), '1.0.4', 'all');

	/**  CARGA DE JS */

	/* carga Highcharts */
	wp_enqueue_script('hightcharts.js'      , $datadash->url . 'app/assets/libs-ext/hightcharts-6.0.4-code/highcharts.js', array(), null, true);
	wp_enqueue_script('hightcharts_3d.js'   , $datadash->url . 'app/assets/libs-ext/hightcharts-6.0.4-code/highcharts-3d.js', array(), null, true);
	wp_enqueue_script('exporting.js'        , $datadash->url . 'app/assets/libs-ext/hightcharts-6.0.4-code/modules/exporting.js', array(), null, true);

	// wp_enqueue_script('d3_cdn'             , 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js', array(), null, true);
	// wp_enqueue_script('plyly_cdn'          , 'https://cdn.plot.ly/plotly-basic-latest.min.js', array(), null, true);
	/* Carga jqueryUI para drag pivottable */
	wp_enqueue_script('jquery-ui.min.js'  , $datadash->url . 'app/assets/libs-ext/pivottable-dist/jquery-ui.min.js', array(), null, true);
	


	/* ================>>   carga pivot table modificados */
	// wp_enqueue_script('pivot__'    , $datadash->url . 'app/assets/libs-ext/modify/pivottable-2.23.0/dist/pivot_23_opt.js', array(), null, true);
	//wp_enqueue_script('pivot__'     , $datadash->url . 'app/assets/libs-ext/pivottable-dist/pivot.js', array(), null, true);
	wp_enqueue_script('pivot__.js'    , $datadash->url . 'app/assets/libs-ext/modify/pivottable/pivot___.js', array(), '2.3.00001', true);
	wp_enqueue_script('pivot__.es.js' , $datadash->url . 'app/assets/libs-ext/modify/pivottable/pivot___.es.js', array(), '2.3.00001', true);

	/** Externals  */
	// wp_enqueue_script('pivotRenderD3'         , $datadash->url . 'app/assets/libs-ext/modify/pivottable-2.23.0/dist/d3_renderers.js', array(), null, true);
	// wp_enqueue_script('pivotRenderPlotly'     , $datadash->url . 'app/assets/libs-ext/modify/pivottable-2.23.0/dist/plotly_renderers.js', array(), null, true);

	/* librerias comunes */
	wp_enqueue_script('lodash.min.js'         , $datadash->core_url . 'assets/libs-ext/lodash.min.js', array(), null, true);
	wp_enqueue_script('select2.min.js'        , $datadash->core_url . 'assets/libs-ext/select2-4.1.0/dist/js/select2.min.js', array(), null, true);
	
	/** MAPAS  */
	// wp_enqueue_script('leaflet.js'                          , $datadash->url  . 'app/assets/libs-ext/leaflet1.7.1/leaflet.js', array(), null, true);
	// wp_enqueue_script('lea_AW-leaflet.awesome-markers.js'   , "https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js", array(), null, true);

	ob_start(); 
?>

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

	/* Para ocultar las cabeceras y lado izquierda del resultado del pivot */
	.oculta_pvt .pvtTdForRender,
	.oculta_pvt .pvtAxisContainer,
	.oculta_pvt .pvtVals {
		display: none
	}

	/* config_grafico */
	.config_grafico label,
	.config_grafico select {
		font-size: 14px;
	}

	/* Oculta hightchart credits*/
	.highcharts-credits {
		display: none;
	}

	/*.pvtTotal, .pvtTotalLabel, .pvtGrandTotal {display: none}*/

	/* para mover el footer del slider Rovolution mas abajo, se hace mas alto el main */
	main.site-main {
		min-height: 2500px !important;
	}

	.frctl .sta_map {
		position: relative;
	}

	.sta_map {
		width: 100%;
		height: 580px;
		box-shadow: 5px 5px 5px #888;
	}


	.leaflet-control-attribution {
		display: none;
	}
</style>

<!-- PREFIJO WRAPPER : {frctl: "clase principal" , sta: 'stadistic admin'} -->
<div class="frctl sta">
	<div class='ph10 pv20 pw100' >
		<!-- =============================== CAMPOS DATASET ================================================= -->
		<div __section="campos_dataset" class="p15 bg-light dark br6 hide" style="display: none;">
			<h4 class="text-center">Selección de los Campos para cargar los sets de datos</h4>
			<div class="form-horizontal">
				<div class="form-group fs14">
					<label class="col-md-2 control-label" for="campos_dataset">Seleccionados</label>
					<div class="col-md-9">
						<select class="pl30 form-control  fs12" id="campos_dataset" __field="campos_dataset" name="campos_dataset"
							multiple="multiple" title="Seleccione los campos de datos" style="width:95%">
						</select>
					</div>
				</div>
				<em class="fs11" style="display: block; text-align: center;">Solo seleccione los necesarios para su análisis.
					Muchos campos o variables, hacen lento los resultados. </em><br>
			</div>
			<!-- <div class=text-center""> -->
			<button __cargar_dataset class="btn btn-success btn-sm darker br6 mt15 center-block">
				<i class="fa fa-check"></i> <span> Cargar datos </span>
			</button>
		</div>

		<!-- =================== NOTIFICACIONES MENSAJES -->
		<div __section="notificaciones" style="display: none;"></div>

		<div __section="contenedor_estadistica" id="contenedor" style="display: none;">

			<div class="row m-0">
				<!-- {{-- ============================= CONTENEDOR ====================================== --}} -->
				<div style="min-height: 900px; max-height: auto; width: 100%; " class="bg-white p15 mt-1">

					<!-- {{-- =========================================== CONTENIDO PIVOT ============================================================--}} -->
					<div class="col-md-12 slick-slide" id="contenido_pivot">
						<!-- {{-- *======   PIVOT  PARA ADMIN *=================================== --}} -->
						<div __contenedor_pivot>
							<div id=tituloDatosUI class="mb15 tituloDatos"></div>
							<div class="row m-0 bg-white mt-2" style="overflow: auto; width: 100%; max-height: 600px; padding: 2px">
								<div id="pvtTableUI" __pvtTable></div>
							</div>
							<hr style="margin: 25px 0">
						</div>

						<!-- {{-- ============================================ CHARTS ==================--}} -->
						<div style="display: flex; justify-content: space-evenly ; ">
							<div __contenedor_grafico>
								<div id="tituloGrafico" class="mb15"></div>
								<div __configuracion_grafico class="config_grafico">
									<h5>OPCIONES DE GRAFICO</h5>
									<label>Tipo Gráfico</label>
									<select __tipo_grafico id="opcionesGrafico" style="width: 300px; display: block; padding: 5px 15px">
										<option value="spline">Linea</option>
										<option value="column">Columnas</option>
										<option value="column-stacked">Columnas apiladas</option>
										<option value="column-stackedp">Columnas apiladas en proporcion</option>
										<option value="bar">Barras</option>
										<option value="bar-stacked">Barras apiladas</option>
										<option value="bar-stackedp">Barras apiladas en proporcion</option>
										<option value="areaspline">Area</option>
										<option value="areaspline-stacked">Areas apiladas</option>
										<option value="areaspline-stackedp">Areas apiladas en proporcion</option>
										<option value="pie">Dona</option>
									</select>
									<label class="block"><input type="checkbox" __view_labels id="viewlabels" />
										Visualizar Datos</label>
								</div>
								<div class="text-center" style="height: 480px; min-width: 500px;  margin: 0 auto">
									<div id="divChart" __chart style="font-family: 'Roboto'; width: 100%; min-height: 100%; margin: 0 auto">
									</div>
								</div>
							
								<!-- <div id="mapa" class="sta_map">
																									</div> -->
							</div>
							<div __contenedor_mapa style="position: relative; min-width: 400px; max-width: 600px ;"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

</div>




<script>
	jQuery(function ($) {

		/*-----------------------------------------------------------------------
		*      stadm variables de configuracion  
		*/
		let ctxG = {
			urlApi: xyzFuns.urlRestApiWP + 'st/v1',
			wrapper: '.frctl.sta',


		}

		/*-----------------------------------------------------------------------
		 *      staC variable que contiene el contexto del Contenido, contenedorPredefinidos, titulos, new update del config
		 */
		let staC = {
			contenedorPredefinidos: $("#contenedorPredefinidos"),
			titulo: $("#titulo"),
			tituloGrafico: $("#tituloGrafico"),
			tituloDatos: $(".tituloDatos"),  //son dos uno de admin y otro de consulta       
			/* Obtiene los datos de la BD */
			obtenerDataset: () => {
				let campos_dataset = $("[__field='campos_dataset']").val();
				//* TODO QUITAR */
				//campos_dataset = ['gestion', 'temperancia_del_agresor', 'departamento', 'latitud', 'longitud', 'agresion', 'relacion_con_la_victima'];

				if (!campos_dataset) {
					xyzFuns.alertMsg('[__section=notificaciones]', 'No se ha seleccionado ninguna variable o campo.', 'alert alert-danger', 'fas fa-exclamation-triangle fa-2x', '', true);
					return;
				}

				xyzFuns.alertMsg('[__section=notificaciones]', '', 'mt40', 'fa fa-spin fa-4x fa-spinner text-system', 'colocar_span_class', false);
				$.post(ctxG.urlApi + '/obtener-dataset', { 'campos_dataset': campos_dataset },
					function (res) {
						// ctxG.collection = res.collection;
						staPiv.collection = res.collection;
						staPiv.pivottableUI("[__pvtTable]");
						xyzFuns.alertMsgClose('[__section=notificaciones]');
						staC.statePage('pivot');
					});
			},

			statePage: (state) => {
				if (state == 'inicial') {
					$("[__section=campos_dataset]").show();
					$("[__section=notificaciones]").show();
				}

				if (state == 'pivot') {
					$("[__section=contenedor_estadistica]").show();
				}
			}
		};

		/*------------------------- staPiv variable que contiene el contexto del Pivot   */
		let staPiv = {
			collection: [],
			pivotData: {},
			/* Principal crea la instancia de Pivot*/
			pivottableUI: function (selector) {

				// let derivers = $.pivotUtilities.derivers;
				// let renderers = $.extend($.pivotUtilities.renderers, $.pivotUtilities.d3_renderers);
				// renderers = $.extend($.pivotUtilities.renderers, $.pivotUtilities.plotly_renderers);
				$(selector).pivotUI(staPiv.collection, {
					// renderers: renderers,
					cols: ['departamento'],
					rows: ['delitos'],
					vals: ['cantidad'],
					aggregatorName: "Cantidad total",
					/* Coloca la configuracion */
					// cols          : configPivot.cols,
					// rows          : configPivot.rows,
					// aggregatorName: configPivot.aggregatorName,
					// vals          : configPivot.vals,
					// inclusions    : configPivot.inclusions,
					// rowOrder      : configPivot.rowOrder,
					// colOrder      : configPivot.colOrder,
					// rendererName  : configPivot.rendererName,
					onRefresh: function (p) {
						/* Se crea el nuevo atributo de datos tranformados a partir de pivotData*/
						p.pivotData.dataTransform = staPiv.trnDatosDePivot(p.pivotData);
						let pivotData = p.pivotData;
						staPiv.pivotData = pivotData;
						console.log(pivotData);
						console.log(p);
						let opcionesChart = staGra.opcionesChart();
						staGra.graficarH('[__chart]', pivotData, opcionesChart);
						// staMap.putMarkers(p.inclusions);
						funs.calculaTotalesDepartamento();
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

		/*------------------------- staGra variable que contiene el contexto del grafico  */
		let staGra = {
			opcionesChart: (ops) => {
				if (ops) {
					tipoGrafico = ops.tipoGrafico ? ops.tipoGrafico : 'spline';
					viewLabels = ops.viewLabels == true;
					$("[__tipo_grafico]").val(tipoGrafico);
					$("[__view_labels]").prop("checked", viewLabels);
				}
				else {
					return {
						tipoGrafico: $("[__tipo_grafico]").val(),
						viewLabels: $("[__view_labels]").prop("checked")
					};
				}
			},
			graficarH: function (selector, pivotData, opcionesChart) {
				opcionesChart = $.extend({ tipoGrafico: 'spline', viewLabels: false }, opcionesChart);
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


				let tipoGrafico = opcionesChart.tipoGrafico.split('-');
				let stacked = tipoGrafico[1] == 'stacked' ? 'normal' : (tipoGrafico[1] == 'stackedp' ? 'percent' : '');
				let viewLabels = opcionesChart.viewLabels;
				let subtituloChart = `${pivotData.aggregatorName} por ${dataTrn.colDimension} ${dataTrn.rowDimension == '' ? '' : ' vs. ' + dataTrn.rowDimension}`;
				let tituloEjeY = `${pivotData.aggregatorName} (${pivotData.valAttrs.join('-')})`;
				let tool = '{series.name}: <b>{point.y:.1f}</b> ';

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

				colores = [
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
						title: {
							text: tituloEjeY,
							style: { color: '#555555' }
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
								format: tipoGrafico[0] != 'pie' ? '{y:.1f}' : '<b>{point.name}</b>: {point.percentage:.1f} %',
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

		// let staMap= {
		//     map: {},
		//     mapInit: () => {
		//         staMap.map = L.map('mapa', {dragging:true,scrollWheelZoom: false} );
		//         L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		//         // L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		//             attribution: '',
		//             maxZoom: 18,
		//             minZoom: 3
		//         }).addTo(staMap.map);
		//         L.control.scale().addTo(staMap.map);
		//     },
		//     showmap: () => {
		//         /* quita las unidades de escala que se repiten REVISAR */
		//         $(".leaflet-control-scale leaflet-control").remove();
		//         let bolCoords = [-17.390305, -66.1558085]; //centrar de bolivia
		//         let zoom = 6; // 14 para departamentos            

		//         staMap.map.setView(bolCoords,  zoom);                
		//     },
		//     putMarkers: (inclusions) => {
		//         let markers = 
		//         {
		//             hospital: L.AwesomeMarkers.icon({
		//                             icon: 'x fas fa-hospital-symbol fa-lg',
		//                             prefix: '',
		//                             markerColor: 'darkred',
		//                             iconColor: 'white'
		//                         }),
		//             policia: L.AwesomeMarkers.icon({
		//                             icon: 'x fas fa-user-shield fa-lg',
		//                             prefix: '',
		//                             markerColor: 'darkgreen',
		//                             iconColor: 'white'
		//                         }),
		//             institucion: L.AwesomeMarkers.icon({
		//                             icon: 'x fas fa-university fa-lg',
		//                             prefix: '',
		//                             markerColor: 'darkblue',
		//                             iconColor: 'white'
		//                         })
		//         }

		//         let circlemarker = {
		//             // radius: 5,
		//             // stroke: true,
		//             // fillColor: "#ee7766",
		//             // fillOpacity: 0.9,
		//             "radius": 7,
		//             "fillColor": "#ff7800",
		//             "color": "#ff4444",
		//             "weight": 1,
		//             "opacity": 1
		//         };

		//         // let osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
		//         let osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		//         staMap.map.eachLayer(function (layer) {
		//             if (osmUrl != layer._url){staMap.map.removeLayer(layer)};
		//         });

		//         let dataMap = _.forEach(staPiv.collection, function(item){
		//             if(item.latitud && item.latitud != '' && !isNaN(item.latitud) && item.longitud && item.longitud != '' && !isNaN(item.longitud ) ){
		//                 if(_.isEmpty(inclusions))
		//                         // m = new L.marker([item.latitud, item.longitud]) //, { icon: markers[cat] })
		//                         // // .bindPopup(`<b>${item.properties.institucio} </b>
		//                         // // <br><br><em> ${item.properties.Info}</em> `)
		//                         // .addTo(staMap.map);
		//                         L.circleMarker([item.latitud, item.longitud], circlemarker).addTo(staMap.map);
		//                 else{
		//                     // TODO revisar cuando son varias variables  no funcona
		//                     _.forOwn(inclusions, function(elems, k){
		//                         if (elems.includes(item[k]))
		//                             // m = new L.marker([item.latitud, item.longitud]) //, { icon: markers[cat] })
		//                             // // .bindPopup(`<b>${item.properties.institucio} </b>
		//                             // // <br><br><em> ${item.properties.Info}</em> `)
		//                             // .addTo(staMap.map);


		//                             L.circleMarker([item.latitud, item.longitud], circlemarker).addTo(staMap.map);
		//                     })
		//                     // return {latitud: item.latitud, longitud: item.longitud };
		//                 }
		//             }   
		//         });

		//         // _.forEach(dataMap, (item) => {
		//         //     // let coords = item.geometry.coordinates;
		//         //     m = new L.marker([item.latitud, item.longitud]) //, { icon: markers[cat] })
		//         //     // .bindPopup(`<b>${item.properties.institucio} </b>
		//         //     // <br><br><em> ${item.properties.Info}</em> `)
		//         //     .addTo(staMap.map);
		//         // });

		//         // console.log(staMap.datalocaliza('defensorias'));

		//         // let defensorias = staMap.datalocaliza('defensorias');
		//         // defensorias_new = _.filter(defensorias, (x) => {
		//         //                         return x.properties.departamen;// == 'La Paz';
		//         //                     });
		//         // _.forEach(defensorias, (item) => {
		//         //     let coords = item.geometry.coordinates;
		//         //     m = new L.marker([coords[1], coords[0]], { icon: markers.institucion })
		//         //         .bindPopup(`<b>${item.properties.institucio} </b>
		//         //         <br><br><em> ${item.properties.Info}</em> `).addTo(xpmC.map);
		//         // });



		//     },
		// }

		/** Funciones*/
		let funs = {

			cargarCamposDataset: (fn) => {
				$.post(ctxG.urlApi + '/obtener-campos', {}, function (res) {
					let campos = res.data;
					let opciones = xyzFuns.generaOpciones(campos, 'nombre_columna', 'nombre_columna', '');
					$("[__field=campos_dataset]").html(opciones);
					fn();
				})
			},

			inicializaControles: () => {
				$("[__field=campos_dataset]").select2({
					placeholder: 'Seleccione los campos necesarios',
					width: 'resolve',
					closeOnSelect: false,
				})
			},

			/*
			coloca en el mapa
			*/
			calculaTotalesDepartamento: () => {
				let pd = staPiv.pivotData;
				let ejeTotals = [];
				let deptosData = []; 
				if (pd.colAttrs.length == 1 && (pd.colAttrs[0]).toLowerCase().includes('departamento')) 
					ejeTotals = pd.colTotals;
				if(pd.rowAttrs.length == 1 && (pd.rowAttrs[0]).toLowerCase().includes('departamento'))
					ejeTotals = pd.rowTotals;

				let maxValor = 0;
				deptosData = _.map(ejeTotals, (item, k) => {
												maxValor = (item.value() > maxValor) ? item.value() : maxValor;
												return {
													valor: item.value(),
													departamento: k,
												}
											});


				if(deptosData.length == 0){
					$("[__contenedor_mapa]").hide();
					return;
				}
				let allTotal = pd.allTotal.value();
				console.log(deptosData);
				console.log(allTotal)
				
				$("[__contenedor_mapa]").show().html(
					/*html*/`<img src="../wp-content/plugins/xfr-data-dash/app/assets/img/mapa-observatorio.png" 
					style="width:100%; height:100% " >`);
				
				let coordRelativas = {
					ch: { x: 48, y: 70 },
					la: { x: 20, y: 40 },
					co: { x: 40, y: 50 },
					sa: { x: 65, y: 50 },
					or: { x: 25, y: 60 },
					po: { x: 32, y: 75 },
					ta: { x: 50, y: 83 },
					be: { x: 40, y: 28 },
					pa: { x: 24, y: 18 },
				}
				function color(valor ){
					if (valor <= 20)
						return '#ff000033';
					if (valor <= 40)
						return '#ff000066';
					if (valor <= 60)
						return '#ff000099'; 
					if (valor <= 80)
						return '#ff0000cc';
					if (valor > 80)
						return '#ff0000ff';
				}

				_.forEach(deptosData, (item, k) => {
					if (item.departamento != 'null' && item.departamento != '') {
						let item2chars = item.departamento.toLowerCase().substring(0, 2);
						let coord = coordRelativas[item2chars];

						let tasaRespectoMax = item.valor/maxValor;
						let colorIntensidad = color(tasaRespectoMax * 100);
						let tamaño = Math.round(15 + (50 - 15) * tasaRespectoMax);
						let punto = /*html*/
						`<span style="width: ${tamaño}px; height: ${tamaño}px; border-radius:50%; 
									position:absolute; left:${coord.x}%; top:${coord.y}% ;
									background-color: ${colorIntensidad}; box-shadow: 0px 0px 20px 17px ${colorIntensidad}; 
									color: #eee; display: flex; justify-content:center; align-items:center; font-size: 12px;">${item.valor}</span>`;
						$("[__contenedor_mapa]").append(punto);
					}
				})
				

		// 		width: 12px;
    // height: 10px;
    // border-radius: 50%;
    // position: absolute;
    // left: 38%;
    // top: 28%;
    
			}


		}


		let ddListeners = () => {
			/** ENVOLTURA CSS para selectores */
			$(ctxG.wrapper)

				/* Al hacer click en cargar datos   */
				.on('click', '[__cargar_dataset]', function () {
					staC.obtenerDataset()
				})

				/** Cuando se hace click en la x de los mensajes de alertas*/
				.on('click', '.close, .cancel', function (e) {
					$(e.currentTarget).closest("[__alert_msg]").remove();
				})

				/*  -------------Cuando se selecciona grafico  Cambia config del grafico */
				.on('change', "[__configuracion_grafico]", function () {
					let opcionesGraph = {
						tipoGrafico: $("[__tipo_grafico]").val(),
						viewLabels: $("[__view_labels]").prop("checked")
					};
					staGra.graficarH("[__chart]", staPiv.pivotData, opcionesGraph);
				});

			/** Cerrar opciones cuando se hace click fuera */
			$(document).mouseup(function (e) {
				let container = $(".pvtFilterBox");
				// si el click no es en el contenedor ni en un hijo del contenedor ni en la flecha de pvtTriangle
				if (!container.is(e.target) && container.has(e.target).length === 0 && !$(e.target).hasClass("pvtTriangle")) {
					container.hide();
				}
			});

		};


		let ddxtInit = (() => {
			ddListeners();
			funs.inicializaControles();
			funs.cargarCamposDataset(function () {
				/* despues de cargar recien muestra el estado inicial*/
				staC.statePage('inicial');
				// staMap.mapInit();
				// staMap.showmap();
				let campos_preseleccionados = ['gestion', 'edad_victima', 'relacion_victima_agresor', 'departamento', 'municipio'];
				$("[__field=campos_dataset]").val(campos_preseleccionados).trigger('change');
				$("[__cargar_dataset]").trigger('click');

			});
		})();

		/* QUITAR  SOLO PARA PRUEBAS*/
		staC.obtenerDataset();

	})


</script>

<?php
    return ob_get_clean();
}
?>