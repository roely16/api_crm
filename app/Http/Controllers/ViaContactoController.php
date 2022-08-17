<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class ViaContactoController extends Controller{

        public function obtener_vias(){

            $result = app('db')->table('via_contacto')->select('*')->get();

            $fields = [
                [
                    "key" => "id",
                    "label" => "ID",
                    "sortable" => true
                ],
                [
                    "key" => "nombre",
                    "label" => "Nombre"
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

        public function store(Request $request){

            $result = app('db')->table('via_contacto')->insert([
                "nombre" => "$request->nombre"
            ]);

            return response()->json($result);

        }

        public function delete($id){

            $result = app('db')->table('via_contacto')->where('id', $id)->delete();

            return response()->json($result);

        }

        public function show($id){

            $result = app('db')->table('via_contacto')->select('*')->where('id', $id)->first();

            return response()->json($result);

        }

        public function edit(Request $request){

            $result = app('db')->table('via_contacto')->where('id', $request->id)->update([
                "nombre" => $request->nombre
            ]);

            return response()->json($result);

        }
        
    }

?>