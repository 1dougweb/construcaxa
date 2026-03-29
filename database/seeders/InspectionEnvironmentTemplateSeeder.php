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
            ['name' => 'Sala', 'icon' => 'bi bi-tv', 'description' => 'Ambiente de estar e recepção', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Iluminação']],
            ['name' => 'Cozinha', 'icon' => 'bi bi-cup-hot', 'description' => 'Ambiente de preparo de alimentos', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Bancadas', 'Pia/Torneira', 'Armários', 'Janelas', 'Portas']],
            ['name' => 'Banheiro', 'icon' => 'bi bi-droplet', 'description' => 'Ambiente sanitário', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Vaso Sanitário', 'Pia/Torneira', 'Chuveiro/Box', 'Acessórios', 'Janelas', 'Portas']],
            ['name' => 'Quarto', 'icon' => 'bi bi-bed', 'description' => 'Ambiente de dormitório', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Iluminação']],
            ['name' => 'Área Externa', 'icon' => 'bi bi-sun', 'description' => 'Áreas externas, jardim, quintal', 'is_active' => true, 'default_elements' => ['Muros/Cercas', 'Piso/Pavimentação', 'Portão', 'Iluminação']],
            ['name' => 'Garagem', 'icon' => 'bi bi-car-front', 'description' => 'Área de estacionamento', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Portão', 'Iluminação']],
            ['name' => 'Lavanderia', 'icon' => 'bi bi-water', 'description' => 'Área de lavagem e serviços', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Tanque', 'Pia/Torneira', 'Janelas', 'Portas']],
            ['name' => 'Escritório', 'icon' => 'bi bi-briefcase', 'description' => 'Ambiente de trabalho', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Iluminação', 'Ar Condicionado']],
            ['name' => 'Varanda', 'icon' => 'bi bi-brightness-high', 'description' => 'Área de varanda ou sacada', 'is_active' => true, 'default_elements' => ['Piso', 'Guarda-corpo', 'Teto', 'Iluminação']],
            ['name' => 'Sala de Jantar', 'icon' => 'bi bi-cup', 'description' => 'Ambiente para refeições', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Iluminação']],
            ['name' => 'Área de Serviço', 'icon' => 'bi bi-tools', 'description' => 'Área técnica e de serviços', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Tanque', 'Janelas', 'Portas']],
            ['name' => 'Hall/Corredor', 'icon' => 'bi bi-door-open', 'description' => 'Corredores e áreas de circulação', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Iluminação']],
            ['name' => 'Sala de Estar', 'icon' => 'bi bi-house', 'description' => 'Ambiente de convivência', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Iluminação']],
            ['name' => 'Quintal', 'icon' => 'bi bi-flower1', 'description' => 'Área externa do quintal', 'is_active' => true, 'default_elements' => ['Muros', 'Piso', 'Portão']],
            ['name' => 'Depósito', 'icon' => 'bi bi-box-seam', 'description' => 'Área de armazenamento', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Prateleiras', 'Portas']],
            ['name' => 'Área de Lazer', 'icon' => 'bi bi-controller', 'description' => 'Espaço de recreação e lazer', 'is_active' => true, 'default_elements' => ['Geral']],
            ['name' => 'Piscina', 'icon' => 'bi bi-water', 'description' => 'Área de piscina', 'is_active' => true, 'default_elements' => ['Borda', 'Revestimento Interno', 'Deck', 'Equipamentos']],
            ['name' => 'Churrasqueira', 'icon' => 'bi bi-fire', 'description' => 'Área de churrasqueira', 'is_active' => true, 'default_elements' => ['Churrasqueira', 'Bancada', 'Pia/Torneira', 'Piso', 'Teto']],
            ['name' => 'Terraço', 'icon' => 'bi bi-cloud-sun', 'description' => 'Área de terraço', 'is_active' => true, 'default_elements' => ['Piso', 'Guarda-corpo', 'Iluminação']],
            ['name' => 'Sótão', 'icon' => 'bi bi-house-up', 'description' => 'Área de sótão', 'is_active' => true, 'default_elements' => ['Piso', 'Telhado', 'Acesso']],
            ['name' => 'Porão', 'icon' => 'bi bi-house-down', 'description' => 'Área de porão', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto']],
            ['name' => 'Lavabo', 'icon' => 'bi bi-droplet-half', 'description' => 'Banheiro social', 'is_active' => true, 'default_elements' => ['Pia/Torneira', 'Vaso Sanitário', 'Espelho', 'Acessórios']],
            ['name' => 'Suíte', 'icon' => 'bi bi-moon-stars', 'description' => 'Quarto com banheiro', 'is_active' => true, 'default_elements' => ['Paredes', 'Piso', 'Teto', 'Janelas', 'Portas', 'Banheiro Suíte']],
            ['name' => 'Closet', 'icon' => 'bi bi-handbag', 'description' => 'Área de guarda-roupas', 'is_active' => true, 'default_elements' => ['Armários/Prateleiras', 'Piso', 'Iluminação']],
            ['name' => 'Home Office', 'icon' => 'bi bi-laptop', 'description' => 'Escritório doméstico', 'is_active' => true, 'default_elements' => ['Bancada', 'Iluminação', 'Pontos Elétricos/Rede']],
        ];

        foreach ($templates as $template) {
            InspectionEnvironmentTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
