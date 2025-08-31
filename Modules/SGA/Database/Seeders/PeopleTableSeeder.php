<?php

namespace Modules\SGA\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SICA\Entities\EPS;
use Modules\SICA\Entities\PensionEntity;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\PopulationGroup;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $population_group = PopulationGroup::firstOrCreate(['name' => 'NINGUNA']); // Consultar o registrar Grupo Poblacional
        $eps = EPS::firstOrCreate(['name' => 'NO REGISTRA']); // Consultar o registrar EPS
        $pension_entity = PensionEntity::firstOrCreate(['name' => 'NO REGISTRA']); // Consultar o registrar Entidad de pensiones

        // Consulta o registro de datos (Administrador)
        Person::firstOrCreate(['document_number' => 1077723461], [ // Consultar o registrar Persona
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'BREINER ANDRES',
            'first_last_name' => 'RODRIGUEZ',
            'second_last_name' => 'BAEZ',
            'eps_id' => $eps->id,
            'population_group_id' => $population_group->id,
            'pension_entity_id' => $pension_entity->id
        ]);

        // Consulta o registro de datos (Funcionario)
        Person::firstOrCreate(['document_number' => 1029881036], [ // Consultar o registrar Persona
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'RENE',
            'first_last_name' => 'CABRERA',
            'second_last_name' => 'CERON',
            'eps_id' => $eps->id,
            'population_group_id' => $population_group->id,
            'pension_entity_id' => $pension_entity->id
        ]);

        // Consulta o registro de datos (Aprendiz)
        Person::firstOrCreate(['document_number' => 1083838175], [ // Consultar o registrar Persona
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'LUISFER',
            'first_last_name' => 'FUENTES',
            'second_last_name' => 'MONTOYA',
            'eps_id' => $eps->id,
            'population_group_id' => $population_group->id,
            'pension_entity_id' => $pension_entity->id
        ]);

        // Consulta o registro de datos (Superadministrador)
        Person::firstOrCreate(['document_number' => 100000001], [ // Consultar o registrar Persona
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'SUPERADMIN',
            'first_last_name' => 'ADMIN',
            'second_last_name' => 'ADMIN',
            'eps_id' => $eps->id,
            'population_group_id' => $population_group->id,
            'pension_entity_id' => $pension_entity->id
        ]);

        // Consulta o registro de datos (SGA persona externa)
        Person::firstOrCreate(['document_number' => 000000000], [ // Consultar o registrar Persona
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'SGA',
            'first_last_name' => 'PERSONA',
            'second_last_name' => 'EXTERNA',
            'eps_id' => $eps->id,
            'population_group_id' => $population_group->id,
            'pension_entity_id' => $pension_entity->id
        ]);
    }
}
