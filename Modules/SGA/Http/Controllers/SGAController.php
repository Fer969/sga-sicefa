<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;

class SGAController extends Controller
{
    public function index()
    {
        $titlePage = trans("sica::menu.Home");
        $titleView = trans("sica::menu.Home");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::index', $data);
    }

    public function developers()
    {
        $titlePage = trans("sica::menu.Developers");
        $titleView = trans("sica::menu.Developers");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::developers', $data);
    }

    public function admin()
    {
        $titlePage = trans("sica::menu.Admin");
        $titleView = trans("sica::menu.Admin");

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::admin.index', $data);
    }

    public function manual()
    {
        $titlePage = 'Manual de Usuario SGA';
        $titleView = 'Manual de Usuario SGA';

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::manual', $data);
    }
}
