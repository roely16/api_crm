<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class ReunionController extends Controller{
        
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
                            ->table('reunion')
                            ->select('*', app('db')->raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'))
                            ->whereIn('zona', $arr_zonas)
                            ->where('id_categoria', $request->id_categoria)
                            ->orderBy('id', 'desc')
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

            $result = app('db')->table('reunion')->insertGetId([

                "fecha" => $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0],
                "motivo" => "$request->motivo",
                "resumen" => "$request->resumen",
                "id_categoria" => $request->id_categoria,
                "created_at" => app('db')->raw('NOW()'),
                "zona" => $request->zona

            ]);
            

            return response()->json($result);

        }

        public function show($id){

            $reunion =  app('db')
                        ->table('reunion')
                        ->select('*', app('db')->raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'))
                        ->where('id', $id)
                        ->first();

            // Obtener las imagenes 
            $imagenes = app('db')
                        ->table('imagen')
                        ->select('*')
                        ->where('id_reunion', $reunion->id)
                        ->get();

            foreach ($imagenes as &$imagen) {
                
                $imagen->url = 'http://localhost/apis/api_crm' . $imagen->url;
                
                $imagen->selected = false;
                $imagen->border_color = null;

            }

            $reunion->imagenes = $imagenes;

            // Obtener los documentos
            $documentos = app('db')
                        ->table('documento')
                        ->select(app('db')->raw('id, id_archivo, nombre_archivo as nombre'))
                        ->where('id_reunion', $reunion->id)
                        ->get();

            foreach ($documentos as $documento) {
                
                $documento->selected = false;
                $documento->_rowVariant = null;
                $documento->link = 'descargar_documento/' . $documento->id;

            }

            $fields_documentos = [

                [
                    "key" => "id",
                    "label" => "ID"
                ],
                [
                    "key" => "nombre",
                    "label" => "Nombre"
                ],
                [
                    "key" => "estado",
                    "label" => "Estado",
                    "class" => "text-center"
                ],
                [
                    "key" => "action",
                    "label" => "Acción",
                    "class" => "text-right"
                ]

            ];

            $reunion->documentos = $documentos;

            $reunion->fields_documentos = $fields_documentos;

            // Participantes
            $participantes =    app('db')
                                ->table('participante')
                                ->join('persona', 'participante.id_persona', '=', 'persona.id')
                                ->select('persona.*', app('db')->raw('concat(persona.nombre, concat(" ", persona.apellido)) as nombre_completo'))
                                ->where('id_reunion', $reunion->id)
                                ->get();

            foreach ($participantes as &$participante) {
                
                $participante->upload = true;
                $participante->_rowVariant = null;

            }

            $fields_participantes = [

                [
                    "key" => "id",
                    "label" => "ID"
                ],
                [
                    "key" => "nombre_completo",
                    "label" => "Nombre"
                ],
                [
                    "key" => "zona",
                    "label" => "Zona"
                ],
                [
                    "key" => "estado",
                    "label" => "Estado",
                    "class" => "text-center"
                ],
                [
                    "key" => "action",
                    "label" => "Acción",
                    "class" => "text-right"
                ]

            ];
                                
            $reunion->participantes = $participantes;
            $reunion->fields_participantes = $fields_participantes;

            return response()->json($reunion);

        }

        public function delete($id){

            $result = app('db')->table('reunion')->where('id', $id)->delete();

        }

        public function edit(Request $request){

            // Format date
            $fecha = explode("/", $request->fecha);

            $result =   app('db')
                        ->table('reunion')
                        ->where('id', $request->id)
                        ->update([
                            "fecha" => $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0],
                            "motivo" => "$request->motivo",
                            "resumen" => "$request->resumen",
                            "zona" => $request->zona,
                            "updated_at" => app('db')->raw('NOW()')
                        ]);
            
            // Eliminar las imagenes marcadas
            foreach ($request->imagenes as $imagen) {

                if ($imagen["border_color"] === 'danger') {
                    
                    // Borrar de la base de datos
                    $result = app('db')->table('imagen')->where('id', $imagen["id"])->delete();

                    //Borrar del disco
                    unlink(base_path('public') . "/uploads/" . $imagen["id_archivo"]);

                }

            }

            // Eliminar los documentos marcados
            foreach ($request->documentos as $documento) {
                
                if ($documento["_rowVariant"] == 'danger') {
                    
                    // Borrar de la base de datos
                    $result = app('db')->table('documento')->where('id', $documento["id"])->delete();

                    //Borrar del disco
                    unlink(base_path('public') . "/uploads/" . $documento["id_archivo"]);

                }

            }

            // Registrar o eliminar los participantes
            foreach ($request->participantes as $participante) {
                
                if (!$participante["upload"]) {
                    
                     $result =  app('db')
                                ->table('participante')
                                ->insert([

                                    "id_persona" => $participante["id"],
                                    "id_reunion" => $request->id,
                                    "created_at" => app('db')->raw('NOW()')

                                ]);

                }

                if ($participante["_rowVariant"] === 'danger') {
                    
                    $result =   app('db')
                                ->table('participante')
                                ->where('id_persona', $participante["id"])
                                ->where('id_reunion', $request->id)
                                ->delete();

                }

            }

            return response()->json($request);

        }

        public function tipo_reunion($id){

            $tipo_reunion = app('db')->table('clasificacion_reunion')->select('*')->where('id', $id)->first();

            return response()->json($tipo_reunion);

        }

    }

?>