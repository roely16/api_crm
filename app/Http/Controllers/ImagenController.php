<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;


    class ImagenController extends Controller{

        public function subir_imagen_reunion(Request $request){

            $picName = $request->file->getClientOriginalName();

            $picID = uniqid() . '_' . $picName;
            
            $request->file('file')->move(base_path()."/public/uploads", $picID);

            // Registrar en tabla
            $result = app('db')->table('imagen')->insert([
                "nombre_archivo" => "$picName",
                "id_archivo" => "$picID",
                "url" => "/public/uploads/" . "$picID",
                "id_reunion" => $request->id_reunion,
                "created_at" => app('db')->raw('NOW()')
            ]);

            return response()->json($request->file->getClientOriginalName());

        }

        public function subir_imagen_convivencia(Request $request){

            

        }

        public function descargar_imagen(){

            //return response()->json($request[0]["id_archivo"]);

            // foreach ($request as $imagen) {
            //     # code...

            //     // return response()->download("/public/uploads/" . $imagen->id_archivo);

            //     return response()->json($imagen);

            // }
            
            //return response()->download("public/uploads/" . $request[0]["id_archivo"]);

            //return response()->json($request->id_archivo);

            try {
                
                //return response()->download(storage_path("5e628dea42e83_logo_muni.jpg"));

                //return Storage::download("5e628dea42e83_logo_muni.jpg");

                // $type = 'image/png';
                // $headers = ['Content-Type' => $type];
                // $path = storage_path("/app/5e628dea42e83_logo_muni.jpg");

                // $response = new BinaryFileResponse($path, 200 , $headers);

                // return $response;

                echo storage_path("5e628dea42e83_logo_muni.jpg");

                //return response()->json(storage_path("/app/5e628dea42e83_logo_muni.jpg"));

            } catch (\Throwable $th) {
               
                return response()->json($th->getMessage());

            }

        }

    }

?>