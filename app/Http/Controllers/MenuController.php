<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class MenuController extends Controller{

        public function accesos_usuario($id){

            $result =   app('db')
                        ->table('menu_usuario')
                        ->join('menu', 'menu_usuario.id_menu', '=', 'menu.id')
                        ->select('*')
                        ->where('id_usuario', $id)
                        ->orderBy('menu.id')
                        ->get();

            foreach ($result as &$item) {
                
                $categoria = app('db')->table('menu')->select('nombre')->where('id', $item->depende)->first();

                $item->categoria = $categoria->nombre;

            }

            $fields = [
                [
                    "key" => "id",
                    "label" => 'ID'
                ],
                [
                    "key" => "categoria",
                    "label" => "Categoría"
                ],
                [
                    "key" => "nombre",
                    "label" => "Acceso"
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

        public function eliminar_acceso(Request $request){

            $result = app('db')->table('menu_usuario')->where('id_usuario', $request->id_usuario)->where('id_menu', $request->id_menu)->delete();

            return response()->json($result);

        }

        public function menu_usuario($id){

            $result =   app('db')
                        ->table('menu_usuario')
                        ->join('menu', 'menu_usuario.id_menu', '=', 'menu.id')
                        ->select('*')
                        ->where('id_usuario', $id)
                        ->orderBy('menu.orden')
                        ->get();

            // Buscar las categorias 

            $categorias = [];

            foreach ($result as $item) {
                
                $categorias [] = $item->depende;
            }

            $categorias = app('db')->table('menu')->select('*')->whereIn('id', $categorias)->get();

            // Por cada categoria buscar la opción habilitada en el menu 
            
            foreach ($categorias as &$categoria) {
                
                $opciones_categoria =   app('db')
                                        ->table('menu')
                                        ->select('id')
                                        ->where('depende', $categoria->id)
                                        ->get();

                $array = [];

                foreach ($opciones_categoria as $opcion) {
                    
                    $array [] = $opcion->id;

                }

                $opciones_usuario = app('db')
                                    ->table('menu_usuario')
                                    ->join('menu', 'menu_usuario.id_menu', '=', 'menu.id')
                                    ->select('*')
                                    ->where('id_usuario', $id)
                                    ->whereIn('id_menu', $array)
                                    ->get();

                $categoria->opciones = $opciones_usuario;


            }

            return response()->json($categorias);

        }

        public function menu_disponible_usuario($id){

            $categorias = app('db')->table('menu')->select('*')->where('depende', '=', null)->get();

            $categorias_f = [];

            foreach ($categorias as &$categoria) {
            
                // Buscar las dependencias
                $opciones =     app('db')
                                ->table('menu')
                                ->select('*')
                                ->where('depende', $categoria->id)
                                ->get();

                //$categoria->opciones = $opciones;

                $categoria->expand = false;
                $categoria->check = false;

                // Buscar los permisos del usuario
                $opciones_usuario =     app('db')
                                        ->table('menu_usuario')
                                        ->join('menu', 'menu_usuario.id_menu', '=', 'menu.id')
                                        ->select('menu.*')
                                        ->where('id_usuario', $id)
                                        ->where('depende', $categoria->id)
                                        ->get();

                //$opc_faltantes = array_diff($opciones, $opciones_usuario);

                $opc_faltantes = [];

                // Por cada opción de la categoria
                foreach ($opciones as $opcion) {
                    
                    // Comparar con cada opción asignada al usuario

                    $find = false;

                    foreach ($opciones_usuario as $opcion_u) {
                        
                        // Si esta la opción
                        if ($opcion == $opcion_u) {
                        
                            $find = true;

                        }

                    }

                    if (!$find) {
                        
                        $opc_faltantes [] = $opcion;

                    }

                }

                
                if ($opc_faltantes) {
                    
                    $categoria->opciones = $opc_faltantes;
                    $categorias_f [] = $categoria;
                }
                

            }

            return response()->json($categorias_f);

        }

        public function registrar_accesos(Request $request){
            
            foreach ($request->opciones as $opcion) {
                
                $result = app('db')->table('menu_usuario')->insert([
                    "id_usuario" => $request->id_usuario,
                    "id_menu" => $opcion["id"]
                ]);

            }

            return response()->json($result);

        }

    }

?>