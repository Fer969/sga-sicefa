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
use Modules\SGA\Http\Requests\StaffPasswordRequest;

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

    public function updatePassword(StaffPasswordRequest $request, User $user)
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

            $data = $request->validated();

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($data['new_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nueva contraseña debe ser diferente a la actual.'
                ], 422);
            }

            // Actualizar la contraseña con hash seguro
            $user->password = Hash::make($data['new_password']);
            $user->updated_at = now();
            $user->save();

            // Log de la acción
            Log::info('Contraseña establecida para usuario staff', [
                'user_id' => $user->id,
                'email' => $user->email,
                'updated_by' => Auth::id(),
                'timestamp' => now(),
                'password_set' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña establecida correctamente.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar contraseña de usuario staff', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al establecer la contraseña. Por favor, intente de nuevo.'
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