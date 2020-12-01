<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PersonaController extends Controller
{

    public function store(Request $request){

        $now = date('Y-m-d H:i:s');

         if (!$request->fecha_nacimiento) {
            
            $request->profesion = NULL;
        }

        if (!$request->profesion) {
            
            $request->profesion = NULL;
        }

        $result = app('db')->table('persona')->insert([
            "nombre" => "$request->nombre",
            "apellido" => "$request->apellido",
            "apellido_casada" => "$request->apellido_casada",
            "estado_civil" => "$request->estado_civil",
            "genero" => "$request->genero",
            "fecha_nacimiento" => "$request->fecha_nacimiento",
            "no_dpi" => "$request->no_dpi",
            "direccion" => "$request->direccion",
            "no_casa" => "$request->no_casa",
            "zona" => "$request->zona",
            "colonia" => "$request->colonia",
            "habitantes" => "$request->habitantes",
            "referido_por" => "$request->referido_por",
            "profesion" => $request->profesion,
            "motivo" => "$request->motivo",
            "status" => "$request->status",
            "fecha_registro" => "$now",
            "clasificacion" => $request->clasificacion,
            "id_tipo" => $request->id_tipo,
            "rango" => $request->rango,
            "mes_cumple" => $request->mes_cumple,
            "observaciones" => $request->observaciones,
            "usuario_registro" => $request->usuario_registro
        ]);

        return response()->json($request);

    }

    public function index($id_usuario){

        $zonas =    app('db')
                    ->table('usuario_zona')
                    ->select('zona')
                    ->where('id_usuario', $id_usuario)
                    ->get();

        $array_zonas = [];

        foreach ($zonas as $zona) {
            
            $array_zonas [] = $zona->zona;

        }

        $result =   app('db')
                    ->table('persona')
                    ->leftJoin('clasificacion_contacto', 'persona.clasificacion', '=', 'clasificacion_contacto.id')
                    ->orderByRaw('id DESC, zona ASC')
                    ->select(
                        'persona.id', 
                        'persona.nombre', 
                        'persona.apellido', 
                        'persona.direccion', 
                        'persona.fecha_nacimiento',
                        'persona.zona',
                        app('db')->raw('clasificacion_contacto.nombre as nombre_clasificacion'),
                        'clasificacion_contacto.color',
                        app('db')->raw('concat(persona.nombre, concat(" ", persona.apellido)) as nombre_completo')
                    )
                    ->whereIn('persona.zona', $array_zonas)
                    ->get();

        $fields = [
            [
                'key' => 'id',
                'label' => 'ID',
                'sortable' => true
            ],
            [
                'key' => 'nombre',
                'label' => 'Nombre',
            ],
            [
                'key' => 'apellido',
                'label' => 'Apellido',
            ],
            [
                'key' => 'direccion',
                'label' => 'Dirección',
            ],
            [
                'key' => 'fecha_nacimiento',
                'label' => 'Fecha de Nacimiento',
                'sortable' => true
            ],
            [
                'key' => 'clasificacion',
                'label' => 'Clasificación',
                'sortable' => true,
                'class' => 'text-center'
            ],
            [
                'key' => 'action',
                'label' => 'Acción',
                'class' => 'text-right'
            ]
        ];

        return response()->json(
            ['items' => $result, 'fields' => $fields]
        );

    }

    public function show($id){

        $result = app('db')->table('persona')->select("*")->where('id', $id)->first();

        $clasificacion_persona =  app('db')->table('clasificacion_contacto')->select('*')->where('id', $result->clasificacion)->first();

        //$result->clasificacion = $clasificacion_persona;

        $clasificacion = app('db')->table('clasificacion_contacto')->select('*')->get();

        return response()->json([
            "detalle" => $result,
            "clasificacion" => $clasificacion
        ]);

    }

    public function delete($id){

        $result = app('db')->delete("delete from persona where id = $id");

        return $result;

    }

    public function update(Request $request){

        if (!$request->fecha_nacimiento) {
            
            $request->fecha_nacimiento = NULL;
        }

        if (!$request->profesion) {
            
            $request->profesion = NULL;
        }

        $result = app('db')->table('persona')->where('id', $request->id)->update([
            "nombre" => "$request->nombre",
            "apellido" => "$request->apellido",
            "apellido_casada" => "$request->apellido_casada",
            "estado_civil" => "$request->estado_civil",
            "genero" => "$request->genero",
            "fecha_nacimiento" => $request->fecha_nacimiento,
            "no_dpi" => "$request->no_dpi",
            "direccion" => "$request->direccion",
            "no_casa" => "$request->no_casa",
            "zona" => "$request->zona",
            "colonia" => "$request->colonia",
            "habitantes" => "$request->habitantes",
            "referido_por" => "$request->referido_por",
            "profesion" => $request->profesion,
            "motivo" => "$request->motivo",
            "status" => "$request->status",
            "clasificacion" => $request->clasificacion,
            "id_tipo" => $request->id_tipo,
            "rango" => $request->rango,
            "mes_cumple" => $request->mes_cumple,
            "observaciones" => $request->observaciones
        ]);

        return response()->json($result);

    }

    public function profesiones(){

        $result = app('db')->select('select id as value, nombre as text from profesion');

        return response()->json($result);

    }

    public function vias_contacto(){

        $result = app('db')->select('select id as value, nombre as text from via_contacto');

        return response()->json($result);

    }

    //
}
