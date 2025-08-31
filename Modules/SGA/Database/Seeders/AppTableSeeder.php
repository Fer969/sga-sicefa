<?php

namespace Modules\SGA\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Database\Eloquent\Model;
use Modules\SICA\Entities\App;

class AppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Registro o actualización de la nueva aplicación para Sistema de Gestión De Almuerzos */
        $app = App::updateOrCreate(['name' => 'SGA'], [
            'url' => '/sga/index',
            'color' => '#76250C',
            'icon' => 'fas fa-utensils',
            'description' => 'Sistema de Gestión de Almuerzos CEFA',
            'description_english' => 'CEFA Lunch Management System',
        ]);
    }
}
