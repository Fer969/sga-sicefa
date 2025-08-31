<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SGA\Entities\Apprentice;
use Illuminate\Support\Facades\DB;

class AdmApprenticeController extends Controller
{
    public function index(Request $request)
    {
        $titlePage = trans("sga::menu.Apprentices");
        $titleView = trans("sga::menu.Apprentices");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
        ];

        $query = Apprentice::with([
            'person.conditions',
            'person.socioeconomic',
            'person.swornStatements',
            'person.representativeLegal',
            'course.program',
        ]);

        // Filtro mejorado por nombre (incluye normalización de texto)
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('person', function ($q) use ($name) {
                // Concatenamos los nombres y apellidos en una sola búsqueda
                $q->whereRaw("LOWER(CONCAT(
                    COALESCE(first_name, ''), ' ', 
                    COALESCE(first_last_name, ''), ' ', 
                    COALESCE(second_last_name, '')
                )) LIKE ?", ['%' . strtolower($name) . '%'])
                    // También permitimos búsqueda por campos individuales
                    ->orWhere('first_name', 'like', "%{$name}%")
                    ->orWhere('first_last_name', 'like', "%{$name}%")
                    ->orWhere('second_last_name', 'like', "%{$name}%");
            });
        }

        if ($request->filled('document')) {
            $document = $request->input('document');
            $query->whereHas('person', fn($q) => $q->where('document_number', 'like', "%{$document}%"));
        }

        if ($request->filled('course_code')) {
            $courseCode = $request->input('course_code');
            $query->whereHas('course', fn($q) => $q->where('code', 'like', "%{$courseCode}%"));
        }

        // Agregamos estado activo si existe el campo
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Ordenamiento por defecto
        $query->orderBy('created_at', 'desc');

        $apprentices = $query->paginate(20);

        return view('sga::admin.apprentice', compact('apprentices'), $data);
    }
}