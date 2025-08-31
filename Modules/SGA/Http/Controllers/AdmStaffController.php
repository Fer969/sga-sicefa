<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Modules\SICA\Entities\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;    
use Illuminate\Support\Facades\Log;

class AdmStaffController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.Staff");
        $titleView = trans("sga::menu.Staff");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        $staffRole = Role::where('slug', 'sga.staff')->first();
        
        if (!$staffRole) {
            return redirect()->back()->with('error', 'Rol de staff no encontrado');
        }

        $staffUsers = User::select('users.*', 'people.document_type', 'people.document_number', 
                                  'people.first_name', 'people.first_last_name', 'people.second_last_name')
                         ->join('role_user', 'users.id', '=', 'role_user.user_id')
                         ->join('people', 'users.person_id', '=', 'people.id')
                         ->where('role_user.role_id', $staffRole->id)
                         ->paginate(20);

        return view('sga::admin.staff', $data, compact('staffUsers'));
    }

    public function show(User $user)
    {
        $user->load('person');
        
        $hasStaffRole = DB::table('role_user')
                         ->join('roles', 'role_user.role_id', '=', 'roles.id')
                         ->where('role_user.user_id', $user->id)
                         ->where('roles.slug', 'sga.staff')
                         ->exists();

        if (!$hasStaffRole) {
            return redirect()->route('cefa.sga.admin.staff')->with('error', 'Usuario no tiene rol de staff');
        }

        return response()->json([
            'user' => $user,
            'person' => $user->person
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        try {
            // Verificar que el usuario tiene rol de staff
            $hasStaffRole = DB::table('role_user')
                             ->join('roles', 'role_user.role_id', '=', 'roles.id')
                             ->where('role_user.user_id', $user->id)
                             ->where('roles.slug', 'sga.staff')
                             ->exists();

            if (!$hasStaffRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no tiene rol de staff'
                ], 403);
            }

            // Validar los datos
            $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
                'new_password_confirmation' => ['required', 'string', 'min:8'],
            ], [
                'current_password.required' => 'La contraseña actual es obligatoria.',
                'new_password.required' => 'La nueva contraseña es obligatoria.',
                'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
                'new_password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
            ]);

            // Verificar que la contraseña actual sea correcta
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual no es correcta.'
                ], 422);
            }

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nueva contraseña debe ser diferente a la actual.'
                ], 422);
            }

            // Actualizar la contraseña
            $user->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now()
            ]);

            // Log de la acción (opcional)
            \Log::info('Contraseña actualizada para usuario staff', [
                'user_id' => $user->id,
                'email' => $user->email,
                'updated_by' => Auth::id(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar contraseña de usuario staff', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor, intente de nuevo.'
            ], 500);
        }
    }

    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function showPasswordForm(User $user)
    {
        $hasStaffRole = DB::table('role_user')
                         ->join('roles', 'role_user.role_id', '=', 'roles.id')
                         ->where('role_user.user_id', $user->id)
                         ->where('roles.slug', 'sga.staff')
                         ->exists();

        if (!$hasStaffRole) {
            return redirect()->route('cefa.sga.admin.staff')->with('error', 'Usuario no tiene rol de staff');
        }

        return response()->json([
            'user' => $user->load('person')
        ]);
    }
}