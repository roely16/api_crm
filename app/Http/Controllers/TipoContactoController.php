<?php 

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;

	class TipoContactoController extends Controller{

		public function tipos_contactos(){

			$result = app('db')->table('tipo_contacto')->select('*')->get();

			return response()->json($result);

		}
		
	}

?>