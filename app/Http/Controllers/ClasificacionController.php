<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class ClasificacionController extends Controller{

		public function obtener_clasificaciones($zona){

			$result = app('db')->table('clasificacion_contacto')->select('*')->get();

			return response()->json($result);

		}

    }

?>