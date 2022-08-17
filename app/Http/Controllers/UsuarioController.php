<?php 


    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class UsuarioController extends Controller{
        
        public function obtener_usuarios($id){

            // Check if user admin
            $user =     app('db')
                        ->table('usuario')
                        ->select('administrador')
                        ->where('id', $id)
                        ->first();

            // Obtener zonas 

            $zonas =    app('db')
                        ->table('usuario_zona')
                        ->select('zona')
                        ->where('id_usuario', $id)
                        ->get();

            $arr_zonas = [];

            foreach ($zonas as $zona) {
                
                $arr_zonas [] = $zona->zona;

            }

            // Usuarios zonas

            $id_usuarios =  app('db')
                            ->table('usuario_zona')
                            ->select('id_usuario')
                            ->whereIn('zona', $arr_zonas)
                            ->distinct()
                            ->get();

            $arr_id_usuarios = [];

            foreach ($id_usuarios as $id_usuario) {
                
                $arr_id_usuarios [] = $id_usuario->id_usuario;

            }

            if ($user->administrador) {
                
                $results =  app('db')
                        ->table('usuario')
                        ->select(
                            'id', 
                            'usuario',
                            app('db')->raw('concat(nombre, concat(" ", apellido)) as nombre')
                        )
                        ->whereIn('id', $arr_id_usuarios)
                        ->get();

            }else{

                $results =  app('db')
                        ->table('usuario')
                        ->select(
                            'id', 
                            'usuario',
                            app('db')->raw('concat(nombre, concat(" ", apellido)) as nombre')
                        )
                        ->whereIn('id', $arr_id_usuarios)
                        ->whereNull('administrador')
                        ->get();

            }

            $fields = [
                [
                    "key" => "id",
                    "label" => "ID"
                ],
                [
                    "key" => "usuario",
                    "label" => "Usuario"
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
                "items" => $results,
                "fields" => $fields,
                "id_usuarios" => $id_usuarios
            ]);

        }

        public function registrar_usuario(Request $request){

            try {
                
                $pass_encrypted = Crypt::encrypt($request->password);

                 $result = app('db')->table('usuario')->insert([
                    "usuario" => "$request->usuario",
                    "password" => "$pass_encrypted",
                    "nombre" => "$request->nombre",
                    "apellido" => "$request->apellido",
                    "created_at" => app('db')->raw('NOW()')
                ]);

            } catch (\Throwable $th) {
                
                return response()->json($th->getMessage());

            }

            return response()->json($result);

        }

        public function eliminar_usuario($id){

            $result = app('db')->table('usuario')->where('id', '=', $id)->delete();

            return response()->json($result);

        }

        public function detalle_usuario($id){

            $result = app('db')->table('usuario')->select('*')->where('id', '=', $id)->first();

            $result->password = Crypt::decrypt($result->password);
            $result->repite_password = $result->password;

            return response()->json($result);

        }

        public function actualizar_usuario(Request $request){

            $pass_encrypted = Crypt::encrypt($request->password);

            $result = app('db')->table('usuario')->where('id', $request->id)->update([
                "usuario" => "$request->usuario",
                "nombre" => "$request->nombre",
                "apellido" => "$request->apellido",
                "password" => "$pass_encrypted",
                "updated_at" => app('db')->raw('NOW()')
            ]);

            return response()->json($request);

        }

        public function zonas_usuario($id_usuario){

            $zonas = app('db')->table('usuario_zona')->select('zona')->where('id_usuario', $id_usuario)->get();
    
            $arr_zonas = [];
    
            foreach ($zonas as $zona) {
                
                $arr_zonas [] = $zona->zona;
    
            }
    
            return response()->json($arr_zonas);
    
        }

        public function zonas_completas_usuario($id_usuario){

            $zonas =    app('db')
                        ->table('usuario_zona')
                        ->join('zona', 'usuario_zona.zona', '=', 'zona.zona')
                        ->select('zona.*')
                        ->where('id_usuario', $id_usuario)
                        ->get();
    
            foreach ($zonas as &$zona) {
                
                $zona->pressed = true;

            }

            // $arr_zonas = [];
    
            // foreach ($zonas as $zona) {
                
            //     $arr_zonas [] = $zona->zona;
    
            // }
    
            return response()->json($zonas);

        }

        public function t_zonas_usuario($id_usuario){

            $result = app('db')->table('usuario_zona')->select('*')->where('id_usuario', $id_usuario)->get();

            $fields = [];

            return response()->json([
                "items" => $result,
                "fields" => $fields
            ]);

        }

        public function eliminar_zona_usuario(Request $request){

            $result = app('db')->table('usuario_zona')->where('id_usuario', $request->id_usuario)->where('zona', $request->zona)->delete();

            return response()->json($result);

        }

        public function zonas_disponibles_usuario($id_usuario){

            $zonas_usuario = app('db')->table('usuario_zona')->select('zona')->where('id_usuario', $id_usuario)->get();

            $arr_zonas = [];

            foreach ($zonas_usuario as $zona) {
            
                $arr_zonas [] = $zona->zona;

            }

            $zonas_disponibles = app('db')->table('zona')->whereNotIn('zona', $arr_zonas)->get();

            foreach ($zonas_disponibles as $zona) {
                
                $zona->selected = false;

            }

            return response()->json($zonas_disponibles);

        }

        public function registrar_zonas_usuario(Request $request){

            foreach ($request->zonas as $zona) {
                
                app('db')->table('usuario_zona')->insert([
                    "id_usuario" => $request->id_usuario,
                    "zona" => $zona["zona"]
                ]);

            }

            return response()->json($request);

        }

    }

?>