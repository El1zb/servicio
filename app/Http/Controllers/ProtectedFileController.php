<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\FileStudentUpload;
use App\Services\DocxToPdfConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProtectedFileController extends Controller
{
    /**
     * Sirve archivos del disco privado ("local") tras verificar que el
     * usuario autenticado tiene permiso para verlos: un admin puede ver
     * cualquiera, un estudiante solo los suyos, y las plantillas/ejemplos
     * subidos por el admin (bajo "files/") son visibles para cualquier
     * usuario autenticado.
     */
    public function show(Request $request)
    {
        $path = (string) $request->query('path', '');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $user    = $request->user();
        $isAdmin = $user->hasRole('admin');

        if (str_starts_with($path, 'student_uploads/')) {
            $document = Document::where('student_file_path', $path)->first();
            abort_unless($document, 404);
            abort_unless($isAdmin || $document->student?->user_id === $user->id, 403);
        } elseif (str_starts_with($path, 'files/individual_uploads/')) {
            $upload = FileStudentUpload::where('file_path', $path)->first();
            abort_unless($upload, 404);
            abort_unless($isAdmin || $upload->student?->user_id === $user->id, 403);
        } elseif (str_starts_with($path, 'files/')) {
            // Plantillas y ejemplos administrados por el admin: no son datos
            // personales, cualquier usuario autenticado puede verlos.
        } else {
            abort(404);
        }

        abort_unless(Storage::disk('local')->exists($path), 404);

        // El visor pide el Word "como PDF" para reusar el mismo render de
        // canvas que los PDF reales — la autorización de arriba ya cubrió
        // este $path, así que aquí solo convertimos y servimos el resultado.
        if ($request->query('as') === 'pdf' && str_ends_with(strtolower($path), '.docx')) {
            $path = app(DocxToPdfConverter::class)->convert($path);
        }

        return Storage::disk('local')->response($path);
    }
}
