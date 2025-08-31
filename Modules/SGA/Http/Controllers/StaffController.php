<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StaffController extends Controller
{
    public function index()
    {
        $titlePage = trans("sica::menu.Home");
        $titleView = trans("sica::menu.Home");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::staff.index', $data);
    }

    public function opsReports()
    {
        $titlePage = trans("sica::menu.Reportes Operativos");
        $titleView = trans("sica::menu.Reportes Operativos");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::staff.ops-reports', $data);
    }

    public function recValidation()
    {
        $titlePage = trans("sica::menu.Registrar Asistencias");
        $titleView = trans("sica::menu.Registrar Asistencias");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::staff.rec-validation', $data);
    }

    public function profile()
    {
        $titlePage = trans("sica::menu.Perfil");
        $titleView = trans("sica::menu.Perfil");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::staff.profile', $data);
    }
}