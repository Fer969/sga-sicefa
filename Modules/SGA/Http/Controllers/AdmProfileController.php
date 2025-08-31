<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AdmProfileController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.Profile");
        $titleView = trans("sga::menu.Profile");

        // Cargar datos para los selects
        $eps = DB::table('e_p_s')->select('id', 'name')->get();
        $populationGroups = DB::table('population_groups')->select('id', 'name')->get();
        $pensionEntities = DB::table('pension_entities')->select('id', 'name')->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'eps' => $eps,
            'populationGroups' => $populationGroups,
            'pensionEntities' => $pensionEntities
        ];

        return view('sga::admin.profile', $data);
    }

    public function changePassword(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.min' => 'La nueva contraseña debe tener al menos :min caracteres.',
        ]);

        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Verificar que la contraseña actual es correcta
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'La contraseña actual no es correcta.'
                ]);
            }

            // Actualizar la contraseña con hash
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', '¡Contraseña cambiada exitosamente!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'general' => 'Ocurrió un error al cambiar la contraseña. Por favor, inténtalo de nuevo.'
            ]);
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'personal_info.document_type' => 'required|in:Cédula de ciudadanía,Tarjeta de identidad,Cédula de extranjería,Pasaporte,Documento nacional de identidad,Registro civil',
            'personal_info.document_number' => 'required|integer|min:1',
            'personal_info.date_of_issue' => 'nullable|date',
            'personal_info.first_name' => 'required|string|max:255',
            'personal_info.first_last_name' => 'required|string|max:255',
            'personal_info.second_last_name' => 'nullable|string|max:255',
            'personal_info.date_of_birth' => 'nullable|date',
            'personal_info.blood_type' => 'nullable|in:No registra,O+,O-,A+,A-,B+,B-,AB+,AB-',
            'personal_info.gender' => 'nullable|in:No registra,Masculino,Femenino',
            'personal_info.eps_id' => 'required|exists:e_p_s,id',
            'personal_info.marital_status' => 'nullable|in:No registra,Soltero(a),Casado(a),Separado(a),Unión libre',
            'personal_info.military_card' => 'nullable|integer|min:1',
            'personal_info.socioeconomical_status' => 'nullable|in:No registra,1,2,3,4,5,6',
            'personal_info.sisben_level' => 'nullable|in:A,B,C,D',
            'personal_info.address' => 'nullable|string|max:255',
            'personal_info.telephone1' => 'nullable|integer|min:1',
            'personal_info.telephone2' => 'nullable|integer|min:1',
            'personal_info.telephone3' => 'nullable|integer|min:1',
            'personal_info.personal_email' => 'nullable|email|max:255',
            'personal_info.misena_email' => 'nullable|email|max:255',
            'personal_info.sena_email' => 'nullable|email|max:255',
            'personal_info.population_group_id' => 'required|exists:population_groups,id',
            'personal_info.pension_entity_id' => 'required|exists:pension_entities,id',
        ], [
            'personal_info.document_type.required' => 'El tipo de documento es obligatorio.',
            'personal_info.document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'personal_info.document_number.required' => 'El número de documento es obligatorio.',
            'personal_info.document_number.integer' => 'El número de documento debe ser un número válido.',
            'personal_info.first_name.required' => 'El primer nombre es obligatorio.',
            'personal_info.first_name.max' => 'El primer nombre no puede tener más de 255 caracteres.',
            'personal_info.first_last_name.required' => 'El primer apellido es obligatorio.',
            'personal_info.first_last_name.max' => 'El primer apellido no puede tener más de 255 caracteres.',
            'personal_info.eps_id.required' => 'La EPS es obligatoria.',
            'personal_info.eps_id.exists' => 'La EPS seleccionada no es válida.',
            'personal_info.population_group_id.required' => 'El grupo poblacional es obligatorio.',
            'personal_info.population_group_id.exists' => 'El grupo poblacional seleccionado no es válido.',
            'personal_info.pension_entity_id.required' => 'La entidad pensional es obligatoria.',
            'personal_info.pension_entity_id.exists' => 'La entidad pensional seleccionada no es válida.',
            'personal_info.personal_email.email' => 'El email personal debe tener un formato válido.',
            'personal_info.misena_email.email' => 'El email Misena debe tener un formato válido.',
            'personal_info.sena_email.email' => 'El email SENA debe tener un formato válido.',
            'personal_info.date_of_birth.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'personal_info.date_of_issue.date' => 'La fecha de expedición debe ser una fecha válida.',
        ]);

        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Obtener los datos del formulario
            $personalData = $request->input('personal_info');

            // Limpiar valores vacíos para campos opcionales (excepto los requeridos)
            $requiredFields = ['document_type', 'document_number', 'first_name', 'first_last_name', 'eps_id', 'population_group_id', 'pension_entity_id'];
            $personalData = array_filter($personalData, function ($value, $key) use ($requiredFields) {
                return in_array($key, $requiredFields) || ($value !== null && $value !== '');
            }, ARRAY_FILTER_USE_BOTH);

            // Verificar si el usuario ya tiene información personal
            if ($user->person) {
                // Actualizar información existente
                $user->person->update($personalData);
                $message = '¡Información personal actualizada exitosamente!';
            } else {
                // Crear nueva información personal
                $personalData['user_id'] = $user->id;
                $user->person()->create($personalData);
                $message = '¡Información personal creada exitosamente!';
            }

            return back()->with('success_personal', $message);
        } catch (\Exception $e) {
            return back()->withErrors([
                'personal_info' => 'Ocurrió un error al actualizar la información personal. Por favor, inténtalo de nuevo. Error: ' . $e->getMessage()
            ]);
        }
    }
}