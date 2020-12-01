<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;


    class DocumentoController extends Controller{

        public function subir_documento_reunion(Request $request){

            $picName = $request->file->getClientOriginalName();

            $picID = uniqid() . '_' . $picName;
            
            $request->file('file')->move(base_path()."/public/uploads", $picID);

            // Registrar en tabla
            $result = app('db')->table('documento')->insert([
                "nombre_archivo" => "$picName",
                "id_archivo" => "$picID",
                "url" => "/public/uploads/" . "$picID",
                "id_reunion" => $request->id_reunion,
                "created_at" => app('db')->raw('NOW()')
            ]);

            return response()->json($request->file->getClientOriginalName());

            //return response()->json($request);

        }

        public function descargar_documento($id){

            $documento =    app('db')
                            ->table('documento')
                            ->select('*')
                            ->where('id', $id)
                            ->first();

            return response()->download(base_path('public') . '/uploads/' . $documento->id_archivo, $documento->nombre_archivo);

        }

    }

?>