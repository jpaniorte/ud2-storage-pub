<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class HelloWorldController extends Controller
{

    public function index(): JsonResponse
{
    $files = Storage::files('/');

    return response()->json([
        'mensaje' => 'Listado de ficheros',
        'contenido' => $files
    ], 200);
}


    /**
     * Recibe por parámetro el nombre de fichero y el contenido. Devuelve un JSON con el resultado de la operación.
     * Si el fichero ya existe, devuelve un 409.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
{
    $request->validate([
        'filename' => 'required|string',
        'content' => 'required|string',
    ]);

    $filename = $request->input('filename');
    $content = $request->input('content');

    if (Storage::exists($filename)) {
        return response()->json([
            'mensaje' => 'El archivo ya existe'
        ], 409);
    }

    Storage::put($filename, $content);

    return response()->json([
        'mensaje' => 'Guardado con éxito'
    ], 200); // Cambiado de 201 a 200
}


    /**
     * Recibe por parámetro el nombre de fichero y devuelve un JSON con su contenido.
     *
     * @param string $filename
     * @return JsonResponse
     */
    public function show(string $filename): JsonResponse
{
    if (!Storage::exists($filename)) {
        return response()->json([
            'mensaje' => 'Archivo no encontrado'
        ], 404);
    }

    $content = Storage::get($filename);

    return response()->json([
        'mensaje' => 'Archivo leído con éxito',
        'contenido' => $content
    ], 200);
}



    /**
     * Recibe por parámetro el nombre de fichero, el contenido y actualiza el fichero.
     * Devuelve un JSON con el resultado de la operación.
     * Si el fichero no existe devuelve un 404.
     *
     * @param Request $request
     * @param string $filename
     * @return JsonResponse
     */
    public function update(Request $request, string $filename): JsonResponse
{
    // Validación del contenido
    $request->validate([
        'content' => 'required|string',
    ]);

    // Verificar si el archivo existe
    if (!Storage::exists($filename)) {
        return response()->json([
            'mensaje' => 'El archivo no existe'  // Cambiado para coincidir con la prueba
        ], 404);
    }

    try {
        // Obtener el contenido de la solicitud
        $content = $request->input('content');
        
        // Intentar actualizar el archivo con el nuevo contenido
        Storage::put($filename, $content);
    } catch (\Exception $e) {
        // Manejo de errores si algo falla al guardar el archivo
        return response()->json([
            'mensaje' => 'Error al actualizar el archivo.',
            'error' => $e->getMessage()
        ], 500);
    }

    // Responder con mensaje de éxito
    return response()->json([
        'mensaje' => 'Actualizado con éxito'  // Cambiado para coincidir con la prueba
    ], 200);
}



    /**
     * Recibe por parámetro el nombre de fichero y lo elimina.
     * Si el fichero no existe devuelve un 404.
     *
     * @param string $filename
     * @return JsonResponse
     */
    public function destroy(string $filename): JsonResponse
{
    // Verificar si el archivo existe
    if (!Storage::exists($filename)) {
        return response()->json([
            'mensaje' => 'El archivo no existe'  // Cambiado para coincidir con la prueba
        ], 404);
    }

    // Intentar eliminar el archivo
    Storage::delete($filename);

    // Responder con mensaje de éxito
    return response()->json([
        'mensaje' => 'Eliminado con éxito'  // Cambiado para coincidir con la prueba
    ], 200);
}


}
