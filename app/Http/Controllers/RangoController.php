<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class RangoController extends Controller{

        public function index(){

            $result = app('db')->table('rango_edad')->select('*')->get();

            return response()->json($result);

        }

    }

?>