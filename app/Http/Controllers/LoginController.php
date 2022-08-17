<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class LoginController extends Controller{
        
        public function login(Request $request){

            $result = app('db')->table('usuario')->select('id', 'usuario', 'password', 'nombre', 'apellido')->where('usuario', '=', $request->usuario)->first();

            if (!$result) {
                    
                $response = [
                    "login" => false,
                    "message" => "Usuario o contraseña incorrectos"
                ];

                return response()->json($response);

            }

            // Existe el usuario, validar contraseña
            $decrypted_password = Crypt::decrypt($result->password);

            if ($decrypted_password == $request->password) {
                
                $response = [
                    "login" => true,
                    "data" => $result
                ];
    
                return response()->json($response);

            }else{

                $response = [
                    "login" => false,
                    "message" => "Usuario o contraseña incorrectos"
                ];

                return response()->json($response);

            }           

        }

    }

?>