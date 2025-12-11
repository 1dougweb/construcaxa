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
                'icon' => 'fi fi-rr-room',
                'description' => 'Ambiente de estar e recepção',
                'is_active' => true,
            ],
            [
                'name' => 'Cozinha',
                'icon' => 'fi fi-rr-kitchen',
                'description' => 'Ambiente de preparo de alimentos',
                'is_active' => true,
            ],
            [
                'name' => 'Banheiro',
                'icon' => 'fi fi-rr-bath',
                'description' => 'Ambiente sanitário',
                'is_active' => true,
            ],
            [
                'name' => 'Quarto',
                'icon' => 'fi fi-rr-bed',
                'description' => 'Ambiente de dormitório',
                'is_active' => true,
            ],
            [
                'name' => 'Área Externa',
                'icon' => 'fi fi-rr-garden',
                'description' => 'Áreas externas, jardim, quintal',
                'is_active' => true,
            ],
            [
                'name' => 'Garagem',
                'icon' => 'fi fi-rr-garage',
                'description' => 'Área de estacionamento',
                'is_active' => true,
            ],
            [
                'name' => 'Lavanderia',
                'icon' => 'fi fi-rr-washing-machine',
                'description' => 'Área de lavagem e serviços',
                'is_active' => true,
            ],
            [
                'name' => 'Sala comercial',
                'icon' => 'fi fi-rr-office',
                'description' => 'Ambiente de trabalho',
                'is_active' => true,
            ],
            [
                'name' => 'Varanda',
                'icon' => 'fi fi-rr-window',
                'description' => 'Área de varanda ou sacada',
                'is_active' => true,
            ],
            [
                'name' => 'Sala de Jantar',
                'icon' => 'fi fi-rr-dining-table',
                'description' => 'Ambiente para refeições',
                'is_active' => true,
            ],
            [
                'name' => 'Área de Serviço',
                'icon' => 'fi fi-rr-tools',
                'description' => 'Área técnica e de serviços',
                'is_active' => true,
            ],
            [
                'name' => 'Hall/Corredor',
                'icon' => 'fi fi-rr-door-open',
                'description' => 'Corredores e áreas de circulação',
                'is_active' => true,
            ],
            [
                'name' => 'Sala de Estar',
                'icon' => 'fi fi-rr-sofa',
                'description' => 'Ambiente de convivência',
                'is_active' => true,
            ],
            [
                'name' => 'Quintal',
                'icon' => 'fi fi-rr-garden',
                'description' => 'Área externa do quintal',
                'is_active' => true,
            ],
            [
                'name' => 'Depósito',
                'icon' => 'fi fi-rr-warehouse',
                'description' => 'Área de armazenamento',
                'is_active' => true,
            ],
            [
                'name' => 'Área de Lazer',
                'icon' => 'fi fi-rr-playground',
                'description' => 'Espaço de recreação e lazer',
                'is_active' => true,
            ],
            [
                'name' => 'Piscina',
                'icon' => 'fi fi-rr-swimming-pool',
                'description' => 'Área de piscina',
                'is_active' => true,
            ],
            [
                'name' => 'Churrasqueira',
                'icon' => 'fi fi-rr-barbecue',
                'description' => 'Área de churrasqueira',
                'is_active' => true,
            ],
            [
                'name' => 'Terraço',
                'icon' => 'fi fi-rr-home',
                'description' => 'Área de terraço',
                'is_active' => true,
            ],
            [
                'name' => 'Sótão',
                'icon' => 'fi fi-rr-house',
                'description' => 'Área de sótão',
                'is_active' => true,
            ],
            [
                'name' => 'Porão',
                'icon' => 'fi fi-rr-basement',
                'description' => 'Área de porão',
                'is_active' => true,
            ],
            [
                'name' => 'Lavabo',
                'icon' => 'fi fi-rr-toilet',
                'description' => 'Banheiro social',
                'is_active' => true,
            ],
            [
                'name' => 'Suíte',
                'icon' => 'fi fi-rr-bed-alt',
                'description' => 'Quarto com banheiro',
                'is_active' => true,
            ],
            [
                'name' => 'Closet',
                'icon' => 'fi fi-rr-closet',
                'description' => 'Área de guarda-roupas',
                'is_active' => true,
            ],
            [
                'name' => 'Home Office',
                'icon' => 'fi fi-rr-laptop',
                'description' => 'Escritório doméstico',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            InspectionEnvironmentTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
