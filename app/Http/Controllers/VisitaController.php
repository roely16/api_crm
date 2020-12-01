<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class VisitaController extends Controller{

        public function index(Request $request){

            $zonas_usuario =    app('db')
                                ->table('usuario_zona')
                                ->select('zona')
                                ->where('id_usuario', $request->id_usuario)
                                ->get();

            $arr_zonas = [];

            foreach ($zonas_usuario as $zona) {
                
                $arr_zonas [] = $zona->zona;

            }

            // Reuniones 

            $reuiones =     app('db')
                            ->table('visita')
                            ->select('*', app('db')->raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'))
                            ->whereIn('zona', $arr_zonas)
                            ->get();

            $fields = [
                [
                    "key" => "id",
                    "label" => "ID",
                    "sortable" => true
                ],
                [
                    "key" => "fecha",
                    "label" => "Fecha"
                ],
                [
                    "key" => "motivo",
                    "label" => "Motivo"
                ],
                [
                    "key" => "zona",
                    "label" => "Zona"
                ],
                [
                    "key" => "action",
                    "label" => "Acción",
                    "class" => "text-right"
                ]

            ];

            return response()->json([
                "items" => $reuiones,
                "fields" => $fields
            ]);

        }

        public function store(Request $request){

            // Format date
            $fecha = explode("/", $request->fecha);

            $result = app('db')->table('visita')->insert([

                "fecha" => $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0],
                "motivo" => "$request->motivo",
                "resumen" => "$request->resumen",
                "created_at" => app('db')->raw('NOW()'),
                "zona" => $request->zona

            ]);

            return response()->json($request);

        }

        public function show($id){

            $reunion =  app('db')
                        ->table('visita')
                        ->select('*', app('db')->raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'))
                        ->where('id', $id)
                        ->first();

            return response()->json($reunion);

        }

        public function delete($id){

            $result = app('db')->table('visita')->where('id', $id)->delete();

        }

        public function edit(Request $request){

            // Format date
            $fecha = explode("/", $request->fecha);

            $result =   app('db')
                        ->table('visita')
                        ->where('id', $request->id)
                        ->update([
                            "fecha" => $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0],
                            "motivo" => "$request->motivo",
                            "resumen" => "$request->resumen",
                            "zona" => $request->zona,
                            "updated_at" => app('db')->raw('NOW()')
                        ]);

            return response()->json($request);

        }

    }

?>