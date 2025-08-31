<?php

namespace Modules\SGA\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckConvocatoryTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sga:check-convocatory-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the required convocatory types exist in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Verificando tipos de convocatorias en la base de datos...');
        
        try {
            // Verificar si existe la tabla types_convocatories
            if (!DB::getSchemaBuilder()->hasTable('types_convocatories')) {
                $this->error('La tabla types_convocatories no existe!');
                return 1;
            }

            // Verificar si existe el tipo "Apoyo de Alimentación"
            $tipoAlimentacion = DB::table('types_convocatories')
                ->where('name', 'Apoyo de Alimentación')
                ->first();

            if (!$tipoAlimentacion) {
                $this->error('No existe el tipo de convocatoria "Apoyo de Alimentación"');
                $this->info('Tipos disponibles:');
                
                $tipos = DB::table('types_convocatories')->get();
                foreach ($tipos as $tipo) {
                    $this->line("- ID: {$tipo->id}, Nombre: {$tipo->name}");
                }
                
                $this->info('Para crear el tipo requerido, ejecuta:');
                $this->line('INSERT INTO types_convocatories (name, description, created_at, updated_at) VALUES ("Apoyo de Alimentación", "Convocatoria para el programa de alimentación del SENA", NOW(), NOW());');
                
                return 1;
            }

            $this->info('✅ Tipo de convocatoria "Apoyo de Alimentación" encontrado');
            $this->info("ID: {$tipoAlimentacion->id}");
            $this->info("Nombre: {$tipoAlimentacion->name}");
            
            // Verificar si existe la tabla convocatories
            if (!DB::getSchemaBuilder()->hasTable('convocatories')) {
                $this->error('La tabla convocatories no existe!');
                return 1;
            }

            $this->info('✅ Tabla convocatories existe');
            
            // Mostrar estructura de la tabla convocatories
            $this->info('Estructura de la tabla convocatories:');
            $columns = DB::select('DESCRIBE convocatories');
            foreach ($columns as $column) {
                $this->line("- {$column->Field}: {$column->Type} {$column->Null} {$column->Key} {$column->Default}");
            }

            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al verificar tipos de convocatorias: ' . $e->getMessage());
            return 1;
        }
    }
}
