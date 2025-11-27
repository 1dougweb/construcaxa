<?php

namespace Database\Seeders;

use App\Models\InspectionEnvironmentTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectionEnvironmentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Sala',
                'description' => 'Ambiente de estar e recepção',
                'is_active' => true,
            ],
            [
                'name' => 'Cozinha',
                'description' => 'Ambiente de preparo de alimentos',
                'is_active' => true,
            ],
            [
                'name' => 'Banheiro',
                'description' => 'Ambiente sanitário',
                'is_active' => true,
            ],
            [
                'name' => 'Quarto',
                'description' => 'Ambiente de dormitório',
                'is_active' => true,
            ],
            [
                'name' => 'Área Externa',
                'description' => 'Áreas externas, jardim, quintal',
                'is_active' => true,
            ],
            [
                'name' => 'Garagem',
                'description' => 'Área de estacionamento',
                'is_active' => true,
            ],
            [
                'name' => 'Lavanderia',
                'description' => 'Área de lavagem e serviços',
                'is_active' => true,
            ],
            [
                'name' => 'Escritório',
                'description' => 'Ambiente de trabalho',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            InspectionEnvironmentTemplate::firstOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
