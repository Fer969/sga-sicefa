<?php

namespace Modules\SGA\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\SICA\Entities\Person;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // Registrar o actualizar usuario
        $person = Person::where('document_number', 1077723461)->first(); // Consultar Persona
        User::updateOrCreate(['nickname' => 'BAROD461'], [ // Actualizar o crear usuario
            'person_id' => $person->id,
            'email' => 'baezandres749@gmail.com'
        ]);

        // Registrar o actualizar usuario
        $person = Person::where('document_number', 1029881036)->first(); // Consultar Persona
        User::updateOrCreate(['nickname' => 'RCCER036'], [ // Actualizar o crear usuario
            'person_id' => $person->id,
            'email' => 'reneceron456@gmail.com'
        ]);

        // Registrar o actualizar usuario
        $person = Person::where('document_number', 1083838175)->first(); // Consultar Persona
        User::updateOrCreate(['nickname' => 'LFMON175'], [ // Actualizar o crear usuario
            'person_id' => $person->id,
            'email' => 'luisferfuentesmontoya@gmail.com'
        ]);
    }
}
