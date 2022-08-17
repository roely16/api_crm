<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class ProfesionController extends Controller{

        public function obtener_profesiones(){

            $result = app('db')->table('profesion')->select('*')->get();

            $fields = [
                [
                    "key" => "id",
                    "label" => "ID",
                    "sortable" => true
                ],
                [
                    "key" => "nombre",
                    "label" => "Nombre",
                ],
                [
                    "key" => "action",
                    "label" => "Acción",
                    "class" => "text-right"
                ]
            ];

            return response()->json([
                "items" => $result,
                "fields" => $fields
            ]);

        }

        public function show($id){

            $result = app('db')->table('profesion')->select('*')->where('id', $id)->first();

            return response()->json($result);

        }

        public function editar(Request $request){

            $result = app('db')->table('profesion')->where('id', $request->id)->update([
                "nombre" => "$request->nombre"
            ]);

            return response()->json($result);

        }

        public function delete($id){

            $result = app('db')->table('profesion')->where('id', $id)->delete();

            return response()->json($result);

        }

        public function registrar(Request $request){

            $result = app('db')->table('profesion')->insert([
                "nombre" => "$request->nombre"
            ]);

            return response()->json($result);

        }

    }

?>