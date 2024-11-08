<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HelloWorldController extends Controller
{
    /**
     * Lista todos los ficheros de la carpeta storage/app.
     *
     * @return JsonResponse La respuesta en formato JSON.
     */
    public function index(): JsonResponse
{
    $files = Storage::files();
    return response()->json([
        'mensaje' => 'Listado de ficheros',
        'contenido' => $files
    ]);
}

public function store(Request $request): JsonResponse
{
    $filename = $request->input('filename');
    $content = $request->input('content');

    if (!$filename || !$content) {
        return response()->json(['mensaje' => 'Faltan parámetros'], 422);
    }

    if (Storage::exists($filename)) {
        return response()->json(['mensaje' => 'El archivo ya existe'], 409);
    }

    Storage::put($filename, $content);
    return response()->json(['mensaje' => 'Guardado con éxito']);
}

public function show(string $filename): JsonResponse
{
    if (!Storage::exists($filename)) {
        return response()->json(['mensaje' => 'Archivo no encontrado'], 404);
    }

    $content = Storage::get($filename);
    return response()->json([
        'mensaje' => 'Archivo leído con éxito',
        'contenido' => $content
    ]);
}

public function update(Request $request, string $filename): JsonResponse
{
    $content = $request->input('content');

    if (!$content) {
        return response()->json(['mensaje' => 'Faltan parámetros'], 422);
    }

    if (!Storage::exists($filename)) {
        return response()->json(['mensaje' => 'El archivo no existe'], 404);
    }

    Storage::put($filename, $content);
    return response()->json(['mensaje' => 'Actualizado con éxito']);
}

public function destroy(string $filename): JsonResponse
{
    if (!Storage::exists($filename)) {
        return response()->json(['mensaje' => 'El archivo no existe'], 404);
    }

    Storage::delete($filename);
    return response()->json(['mensaje' => 'Eliminado con éxito']);
}
}