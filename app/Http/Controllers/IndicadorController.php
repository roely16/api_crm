<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class IndicadorController extends Controller{
        
        public function obtener_indicadores(){

            $result = app('db')->table('indicador')->select('*')->get();

            return response()->json($result);

        }

        public function indicador_total_clasificacion(Request $request){

            $clasificaciones = app('db')->table('clasificacion_contacto')->select('id', 'color')->orderBy('id')->get();

            $arra_cla = [];

            foreach ($clasificaciones as &$clasificacion) {
                
                $total =    app('db')
                            ->table('persona')
                            ->select(app('db')->raw('count(*) as total'))
                            ->where('clasificacion', $clasificacion->id)
                            ->whereIn('zona', $request->zonas)
                            // ->whereBetween(app('db')->raw("DATE_FORMAT(fecha_registro, '%m/%Y')"), [$request->de, $request->hasta])
                            ->get();

                if ($total[0]->total != 0) {
                    
                    $clasificacion->total = $total[0]->total;

                    $arra_cla [] = $clasificacion;

                }

            }

            // Ordenar 

            usort($arra_cla, function ($a, $b){

                return strnatcmp($b->total, $a->total);

            });

            return response()->json([
                "data" => $arra_cla,
                "request" => $request
            ]);

        }

        public function indicador_contactos_ingresados(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de la meta

            $total_meta =   app('db')
                            ->table('meta')
                            ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                            ->whereIn('zona', $request->zonas)
                            ->whereBetween('mes', [$de_mes, $hasta_mes])
                            ->where('id_indicador', $request->id_indicador)
                            ->whereBetween('year', [$de_year, $hasta_year])
                            ->get();

            // Total de registrados

            $total_registrados =    app('db')
                                    ->table('persona')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha_registro, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();

            $meta = intval($total_meta[0]->TOTAL_META);
            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            if ($meta > 0) {
                
                $porcentaje = floatval(number_format( ($registrados / $meta) * 100, 2 ));

            }else{

                $porcentaje = 0;

            }

            $data = [
                "meta" => $meta,
                "registrados" => $registrados,
                "porcentaje" => $porcentaje
            ];


            return  response()->json($data);

        }

        public function indicador_reuniones(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de la meta

            $total_meta =   app('db')
                            ->table('meta')
                            ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                            ->whereIn('zona', $request->zonas)
                            ->whereBetween('mes', [$de_mes, $hasta_mes])
                            ->where('id_indicador', $request->id_indicador)
                            ->whereBetween('year', [$de_year, $hasta_year])
                            ->get();

            // Total de registrados

            $total_registrados =    app('db')
                                    ->table('reunion')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->where('id_categoria', $request->id_clasificacion)
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $meta = intval($total_meta[0]->TOTAL_META);
            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            if ($meta > 0) {
                
                $porcentaje = floatval(number_format( ($registrados / $meta) * 100, 2 ));

            }else{

                $porcentaje = 0;

            }

            $data = [
                "meta" => $meta,
                "registrados" => $registrados,
                "porcentaje" => $porcentaje
            ];

            return  response()->json($data);

        }

        public function indicador_convivencias(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de la meta

            $total_meta =   app('db')
                            ->table('meta')
                            ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                            ->whereIn('zona', $request->zonas)
                            ->whereBetween('mes', [$de_mes, $hasta_mes])
                            ->where('id_indicador', $request->id_indicador)
                            ->whereBetween('year', [$de_year, $hasta_year])
                            ->get();

            // Total de registrados

            $total_registrados =    app('db')
                                    ->table('convivencia')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->where('id_categoria', $request->id_clasificacion)
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $meta = intval($total_meta[0]->TOTAL_META);
            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            if ($meta > 0) {
                
                $porcentaje = floatval(number_format( ($registrados / $meta) * 100, 2 ));

            }else{

                $porcentaje = 0;

            }

            $data = [
                "meta" => $meta,
                "registrados" => $registrados,
                "porcentaje" => $porcentaje
            ];

            return  response()->json($data);

        }

        public function indicador_organizaciones(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de la meta

            $total_meta =   app('db')
                            ->table('meta')
                            ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                            ->whereIn('zona', $request->zonas)
                            ->whereBetween('mes', [$de_mes, $hasta_mes])
                            ->where('id_indicador', $request->id_indicador)
                            ->whereBetween('year', [$de_year, $hasta_year])
                            ->get();

            // Total de registrados

            $total_registrados =    app('db')
                                    ->table('organizacion')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $meta = intval($total_meta[0]->TOTAL_META);
            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            if ($meta > 0) {
                
                $porcentaje = floatval(number_format( ($registrados / $meta) * 100, 2 ));

            }else{

                $porcentaje = 0;

            }

            $data = [
                "meta" => $meta,
                "registrados" => $registrados,
                "porcentaje" => $porcentaje
            ];

            return  response()->json($data);

        }

        public function indicador_visitas(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de la meta

            $total_meta =   app('db')
                            ->table('meta')
                            ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                            ->whereIn('zona', $request->zonas)
                            ->whereBetween('mes', [$de_mes, $hasta_mes])
                            ->where('id_indicador', $request->id_indicador)
                            ->whereBetween('year', [$de_year, $hasta_year])
                            ->get();

            // Total de registrados

            $total_registrados =    app('db')
                                    ->table('visita')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $meta = intval($total_meta[0]->TOTAL_META);
            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            if ($meta > 0) {
                
                $porcentaje = floatval(number_format( ($registrados / $meta) * 100, 2 ));

            }else{

                $porcentaje = 0;

            }

            $data = [
                "meta" => $meta,
                "registrados" => $registrados,
                "porcentaje" => $porcentaje
            ];

            return  response()->json($data);

        }

        public function indicador_total_indicadores(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            // Total de Meta
            $meta =     app('db')
                        ->table('meta')
                        ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                        ->whereIn('zona', $request->zonas)
                        ->whereBetween('mes', [$de_mes, $hasta_mes])
                        ->whereBetween('year', [$de_year, $hasta_year])
                        ->get();

            // Total de todos los indicadores

            // Cantidad de Personas registradas
            $total_registrados =    app('db')
                                    ->table('persona')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha_registro, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();

            $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

            // Cantidad de reuniones
            $total_reuniones =      app('db')
                                    ->table('reunion')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $total_reuniones = intval($total_reuniones[0]->TOTAL_REGISTRADOS);

            // Convivencias 
            $total_convivencias =    app('db')
                                    ->table('convivencia')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $total_convivencias = intval($total_convivencias[0]->TOTAL_REGISTRADOS);

            // Organizaciones
            $total_organizaciones =    app('db')
                                    ->table('organizacion')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $total_organizaciones = intval($total_organizaciones[0]->TOTAL_REGISTRADOS);

            // Visitas
            $total_visitas =        app('db')
                                    ->table('visita')
                                    ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                    ->whereIn('zona', $request->zonas)
                                    ->whereBetween(app('db')->raw("DATE_FORMAT(fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                    ->get();


            $total_visitas = intval($total_visitas[0]->TOTAL_REGISTRADOS);

            $data = [];

            $data = [
                [
                    "name" => "Meta",
                    "y" => intval($meta[0]->TOTAL_META),
                    "color" => '#dc3545'
                ],
                [
                    "name" => "Ingresados",
                    "y" => intval($registrados + $total_reuniones + $total_convivencias + $total_organizaciones + $total_visitas),
                    "color" => '#28a745'
                ]
            ];

            return response()->json($data);

        }

        public function total_ponderado(Request $request){

            $de = explode("/", $request->de);
            $hasta = explode("/", $request->hasta);

            $de_mes = intval($de[0]);
            $de_year = intval($de[1]);

            $hasta_mes = intval($hasta[0]);
            $hasta_year = intval($hasta[1]);

            $detail = [];
            $grafica = [];

            $indicadores =  app('db')
                            ->table('indicador')
                            ->select('*')
                            ->get();

            $total_indicador = 0;

            foreach ($indicadores as $indicador) {
                
                $total_meta =   app('db')
                                ->table('meta')
                                ->select(app('db')->raw('SUM(meta) as TOTAL_META'))
                                ->whereIn('zona', $request->zonas)
                                ->whereBetween('mes', [$de_mes, $hasta_mes])
                                ->where('id_indicador', $indicador->id)
                                ->whereBetween('year', [$de_year, $hasta_year])
                                ->get();

                // Total de registrados

                if (!$indicador->id_clasificacion) {
                    
                    $total_registrados =    app('db')
                                            ->table($indicador->tabla)
                                            ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                            ->whereIn('zona', $request->zonas)
                                            ->whereBetween(app('db')->raw("DATE_FORMAT($indicador->campo_fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                            ->get();
                }else{

                    $total_registrados =    app('db')
                                            ->table($indicador->tabla)
                                            ->select(app('db')->raw('COUNT(*) AS TOTAL_REGISTRADOS'))
                                            ->where('id_categoria', $indicador->id_clasificacion)
                                            ->whereIn('zona', $request->zonas)
                                            ->whereBetween(app('db')->raw("DATE_FORMAT($indicador->campo_fecha, '%m/%Y')"), [$request->de, $request->hasta])
                                            ->get();

                }

                // Cálculo del porcentaje 
                $meta = intval($total_meta[0]->TOTAL_META);
                $registrados = intval($total_registrados[0]->TOTAL_REGISTRADOS);

                if ($meta > 0) {
                    
                    $porcentaje = floatval(number_format( ($registrados / $meta), 4 ));

                }else{

                    $porcentaje = 0;

                }

                $total = floatval(number_format($porcentaje * $indicador->ponderacion, 4));

                $total_indicador += $total;

                $row = [
                    "indicador" => $indicador->nombre,
                    "meta" => $meta,
                    "ingresados" => $registrados,
                    "porcentaje" => $porcentaje,
                    "porcentaje_100" => $porcentaje * 100 . '%',
                    "ponderacion" => $indicador->ponderacion,
                    "ponderacion_100" => $indicador->ponderacion * 100 . '%',
                    "total" => $total * 100 . '%'
                ];

                $detail [] = $row;

            }

            $fiels_detail = [
                [
                    "key" => "indicador",
                    "label" => "Indicador"
                ],
                [
                    "key" => "meta",
                    "label" => "Meta",
                    "class" => "text-center"
                ],
                [
                    "key" => "ingresados",
                    "label" => "Ingresados",
                    "class" => "text-center"
                ],
                [
                    "key" => "porcentaje_100",
                    "label" => "Porcentaje",
                    "class" => "text-center"
                ],
                [
                    "key" => "ponderacion_100",
                    "label" => "Ponderación",
                    "class" => "text-center"
                ],
                [
                    "key" => "total",
                    "label" => "Total",
                    "class" => "text-center"
                ]
            ];

            $data["chart"] = [
                "y" => ($total_indicador * 100) 
            ];

            $data["detail"] = $detail; 

            $data["fields_detail"] = $fiels_detail;

            return response()->json($data);

        }

    }

?>