<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $titlePage = trans("sica::menu.Home");
        $titleView = trans("sica::menu.Home");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::admin.index', $data);
    }
}