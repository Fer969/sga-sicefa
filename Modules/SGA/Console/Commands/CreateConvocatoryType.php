<?php

namespace Modules\SGA\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateConvocatoryType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sga:create-convocatory-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the required convocatory type for SGA module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creando tipo de convocatoria "Apoyo de Alimentación"...');
        
        try {
            // Verificar si existe la tabla types_convocatories
            if (!DB::getSchemaBuilder()->hasTable('types_convocatories')) {
                $this->error('La tabla types_convocatories no existe!');
                return 1;
            }

            // Verificar si ya existe el tipo
            $existingType = DB::table('types_convocatories')
                ->where('name', 'Apoyo de Alimentación')
                ->first();

            if ($existingType) {
                $this->info('✅ El tipo "Apoyo de Alimentación" ya existe con ID: ' . $existingType->id);
                return 0;
            }

            // Crear el tipo de convocatoria
            $typeId = DB::table('types_convocatories')->insertGetId([
                'name' => 'Apoyo de Alimentación',
                'description' => 'Convocatoria para el programa de alimentación del SENA',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->info('✅ Tipo de convocatoria "Apoyo de Alimentación" creado exitosamente con ID: ' . $typeId);
            
            // Mostrar todos los tipos disponibles
            $this->info('Tipos de convocatoria disponibles:');
            $tipos = DB::table('types_convocatories')->get();
            foreach ($tipos as $tipo) {
                $this->line("- ID: {$tipo->id}, Nombre: {$tipo->name}");
            }

            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al crear el tipo de convocatoria: ' . $e->getMessage());
            return 1;
        }
    }
}
