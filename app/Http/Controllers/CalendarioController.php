<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class CalendarioController extends Controller{

        public function registrar_evento(Request $request){

            // Format date
            $fecha_inicio = explode("/", $request->fecha_inicio);
            $fecha_fin = explode("/", $request->fecha_fin);

            $result =   app('db')->table('calendario')->insert([
                            "fecha_inicio" => $fecha_inicio[2] . '-' . $fecha_inicio[1] . '-' . $fecha_inicio[0],
                            "fecha_fin" => $fecha_fin[2] . '-' . $fecha_fin[1] . '-' . $fecha_fin[0],
                            "hora_inicio" => $request->hora_inicio,
                            "hora_fin" => $request->hora_fin,
                            "titulo" => "$request->titulo",
                            "detalle" => "$request->detalle",
                            "observacion" => "$request->observacion",
                            "zona" => $request->zona
                        ]);

            return response()->json($request);

        }

        public function obtener_eventos(Request $request){

            $eventos =   app('db')
                        ->table('calendario')
                        ->join('zona', 'calendario.zona', '=', 'zona.zona')
                        ->select(app('db')->raw('
                            id,
                            concat(fecha_inicio, concat(" ", hora_inicio)) as start,
                            concat(fecha_fin, concat(" ", hora_fin)) as end,
                            TIME_FORMAT(hora_inicio, "%H:%i") as hora_inicio,
                            TIME_FORMAT(hora_fin, "%H:%i") as hora_fin,
                            titulo as title, 
                            zona.class
                        '))
                        ->whereIn('calendario.zona', $request)
                        ->get();

            foreach($eventos as &$evento){

                //$evento->content = "<p>" . $evento->hora_inicio . " - " . $evento->hora_fin . "</p>";
                $evento->deletable = false;
                $evento->resizable = false;
                $evento->editable = false;
                $evento->draggable = false;

            }

            return response()->json($eventos);

        }

        public function detalle_evento($id){

            $evento =   app('db')
                        ->table('calendario')
                        ->select(app('db')->raw('
                            id,
                            DATE_FORMAT(fecha_inicio, "%d/%m/%Y") as fecha_inicio,
                            DATE_FORMAT(fecha_fin, "%d/%m/%Y") as fecha_fin,
                            hora_inicio,
                            hora_fin,
                            titulo,
                            detalle,
                            observacion,
                            zona
                        '))
                        ->where('id', $id)
                        ->first();

            return response()->json($evento);

        }

        public function editar_evento(Request $request){

            // Format date
            $fecha_inicio = explode("/", $request->fecha_inicio);
            $fecha_fin = explode("/", $request->fecha_fin);

            $result =   app('db')->table('calendario')->where('id', $request->id)->update([
                            "fecha_inicio" => $fecha_inicio[2] . '-' . $fecha_inicio[1] . '-' . $fecha_inicio[0],
                            "fecha_fin" => $fecha_fin[2] . '-' . $fecha_fin[1] . '-' . $fecha_fin[0],
                            "hora_inicio" => $request->hora_inicio,
                            "hora_fin" => $request->hora_fin,
                            "titulo" => "$request->titulo",
                            "detalle" => "$request->detalle",
                            "observacion" => "$request->observacion",
                            "zona" => $request->zona,
                            "updated_at" => app('db')->raw('NOW()')
                        ]);

            return response()->json($request);


        }

        public function eliminar_evento($id){

            $result =   app('db')
                        ->table('calendario')
                        ->where('id', $id)
                        ->delete();

            return response()->json($result);

        }

    }

?>