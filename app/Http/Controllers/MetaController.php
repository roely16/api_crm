<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class MetaController extends Controller{

        public function index($id_usuario){

            $zonas = app('db')->table('usuario_zona')->select('zona')->where('id_usuario', $id_usuario)->get();

            $arr_zonas = [];

            foreach ($zonas as $zona) {
            
                $arr_zonas [] = $zona->zona;

            }

            // Buscar las metas de las zonas con permiso 

            $metas =    app('db')
                        ->table('meta')
                        ->join('indicador', 'meta.id_indicador', '=', 'indicador.id')
                        ->select('meta.*', 'indicador.nombre', app('db')->raw('concat(mes, concat("/", year)) as fecha'))
                        ->get();

            $fields = [
                [
                    "key" => "id",
                    "label" => "ID",
                    "sortable" => true
                ],
                [
                    "key" => "nombre",
                    "label" => "Indicador"
                ],
                [
                    "key" => "zona",
                    "label" => "Zona",
                    "sortable" => true
                ],
                [
                    "key" => "fecha",
                    "label" => "Fecha",
                    "sortable" => true
                ],
                [
                    "key" => "meta",
                    "label" => "Meta",
                    "sortable" => true
                ],
                [
                    "key" => "action",
                    "label" => "Acción",
                    "class" => "text-right"
                ]
            ];

            return response()->json([
                "items" => $metas,
                "fields" => $fields
            ]);

        }

        public function store(Request $request){

            $split_fecha = explode("/", $request->fecha);
            $month = $split_fecha[0];
            $year = $split_fecha[1];

            $result = app('db')->table('meta')->insert([
                "id_indicador" => $request->id_indicador,
                "zona" => $request->zona,
                "mes" => $month,
                "year" => $year,
                "meta" => $request->meta,
                "registrado_por" => $request->registrado_por,
                "created_at" => app('db')->raw('NOW()')
            ]);

            return response()->json($request);

        }

        public function delete($id){

            $result = app('db')->table('meta')->where('id', $id)->delete();

            return response()->json($result);

        }

        public function show($id){

            $result =   app('db')
                        ->table('meta')
                        ->select('*', app('db')->raw('concat(mes, concat("/", year)) as fecha'))
                        ->where('id', $id)
                        ->first();

            return response()->json($result);

        }

        public function edit(Request $request){

            $split_fecha = explode("/", $request->fecha);
            $month = $split_fecha[0];
            $year = $split_fecha[1];

            $result = app('db')->table('meta')->where('id', $request->id)->update([
                "id_indicador" => $request->id_indicador,
                "zona" => $request->zona,
                "mes" => $month,
                "year" => $year,
                "meta" => $request->meta,
                "updated_at" => app('db')->raw('NOW()')
            ]);

            return response()->json($result);

        }

    }

?>