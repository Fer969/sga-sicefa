<?php

namespace Modules\SGA\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Person;

class PeopleUsersController extends Controller
{
    public function indexFilteredUsers()
    {
        // 1. Obtener los IDs de roles según los slugs
        $roleIds = DB::table('roles')
            ->whereIn('slug', ['sga.staff', 'sga.apprentice'])
            ->pluck('id');

        // 2. Obtener los IDs de los usuarios con esos roles
        $userIds = DB::table('role_user')
            ->whereIn('role_id', $roleIds)
            ->pluck('user_id');

        // 3. Consultar los usuarios con relación a 'person'
        $users = User::with('person')
            ->whereIn('id', $userIds)
            ->get();

        return response()->json($users);
    }
}
