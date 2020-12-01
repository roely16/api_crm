<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedioContactoController extends Controller
{
	public function registrar(Request $request){

		$result = app('db')->table('medio_contacto')->insert([
			'id_persona' => $request->id_persona,
			'id_tipo' => $request->id_tipo,
			'medio' => "$request->medio",
			'estado' => "$request->estado",
			'created_at' => app('db')->raw('NOW()')
		]);

		return response()->json($result);

	}

	public function tipos_contacto(){

		$result = app('db')->table('tipo_medio_contacto')->select('id', 'nombre')->get();

		return response()->json($result);

	}

	public function medios_contacto($id){

		$results = 	app('db')
					->table('medio_contacto')
					->join('tipo_medio_contacto', 'medio_contacto.id_tipo', '=', 'tipo_medio_contacto.id')
					->select('medio_contacto.id', 'medio_contacto.id_persona', 'medio_contacto.id_tipo', 'medio_contacto.medio', 'medio_contacto.estado', 'tipo_medio_contacto.nombre as nombre_medio')
					->where('medio_contacto.id_persona', '=', $id)
					->orderByRaw('id DESC')
					->get();

		$fields = [
			[
				'key' => 'id',
				'label' => 'ID'
			],
			[
				'key' => 'nombre_medio',
				'label' => 'Tipo'
			],
			[
				'key' => 'medio',
				'label' => 'Medio'
			],
			[
				'key' => 'estado',
				'label' => 'Estado'
			],
			[
				'key' => 'action',
				'label' => 'Acción',
				'class' => 'text-right'
			]
		];

		return response()->json([
			'items' => $results,
			'fields' => $fields
		]);

	}

	public function eliminar($id){

		$result = app('db')->table('medio_contacto')->where('id', '=', $id)->delete();

		return response()->json($result);

	}

	public function detalle($id){

		$result = 	app('db')
					->table('medio_contacto')
					->select("*")
					->where('id', '=', $id)
					->first();

		return response()->json($result);

	}

	public function editar(Request $request){

		try {
			
			$result = 	app('db')
						->table('medio_contacto')
						->where('id', '=', $request->id)
						->update([
							"id_tipo" => $request->id_tipo,
							"medio" => "$request->medio",
							"estado" => "$request->estado",
							"updated_at" => app('db')->raw('NOW()')
						]);

		} catch (\Throwable $th) {
			//throw $th;

			return response()->json($th->getCode());

		}

		return response()->json($result);

	}

}

?>