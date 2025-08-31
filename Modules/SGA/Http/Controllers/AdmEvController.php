<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdmEvController extends Controller 
{
    public function index()
    {
        $titlePage = trans("sga::menu.ev-history");
        $titleView = trans("sga::menu.ev-history");

        // Consultar convocatorias de Apoyo de Alimentación con el número de postulados
        $convocatories = DB::table('convocatories')
            ->leftJoin('calls_applications', 'convocatories.id', '=', 'calls_applications.convocatory_selected')
            ->where('convocatories.types_convocatories_id', 4) // Filtrar solo Apoyo de Alimentación
            ->select(
                'convocatories.id',
                'convocatories.name',
                'convocatories.quarter',
                'convocatories.year',
                'convocatories.status',
                'convocatories.coups',
                'convocatories.registration_start_date',
                'convocatories.registration_deadline',
                DB::raw('COUNT(calls_applications.id) as postulados')
            )
            ->groupBy(
                'convocatories.id',
                'convocatories.name',
                'convocatories.quarter',
                'convocatories.year',
                'convocatories.status',
                'convocatories.coups',
                'convocatories.registration_start_date',
                'convocatories.registration_deadline'
            )
            ->orderBy('convocatories.registration_start_date', 'desc')
            ->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatories' => $convocatories
        ];

        return view('sga::admin.ev-history', $data);
    }
}