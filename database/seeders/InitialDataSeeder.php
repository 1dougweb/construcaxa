<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        // Criar permissões
        $permissions = [
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'delete service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view reports',
            'manage stock',
            'view attendance',
            'manage attendance',
            'view permissions',
            'manage permissions',
            // Projetos/Obras
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'post project-updates',
            'view client-projects',
            'view budgets',
            'manage budgets',
            'manage finances',
            'manage services',
            // Clientes
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            // Contratos
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            // Vistorias
            'view inspections',
            'create inspections',
            'edit inspections',
            'delete inspections',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Criar papéis
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // Atribuir permissões aos papéis
        $adminRole->givePermissionTo($permissions);
        
        $managerRole->givePermissionTo([
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'view categories',
            'create categories',
            'edit categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'view employees',
            'create employees',
            'edit employees',
            'view reports',
            'manage stock',
            'view attendance',
            'manage attendance',
            'view permissions',
            'manage permissions',
            // Projetos/Obras
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'post project-updates',
            'view client-projects',
            'view budgets',
            'manage budgets',
            'manage finances',
            'manage services',
            // Clientes
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            // Contratos
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            // Vistorias
            'view inspections',
            'create inspections',
            'edit inspections',
            'delete inspections',
        ]);

        $employeeRole->givePermissionTo([
            'view dashboard',
            'view products',
            'view categories',
            'view service-orders',
            'create service-orders',
            'view suppliers',
            'manage stock',
            'view attendance',
            // Projetos/Obras
            'view projects',
            'post project-updates',
        ]);

        // Cliente: permissões mínimas
        $clientRole->syncPermissions([
            'view client-projects',
            'view contracts', // Cliente pode ver seus próprios contratos
        ]);

        // Criar usuário admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Reset cache again after changes
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Criar categorias iniciais para empresa de engenharia
        $categories = [
            // CIMENTOS E ARGAMASSAS
            [
                'name' => 'Cimento',
                'description' => 'Cimentos para construção civil',
                'sku_prefix' => 'CIM',
                'attributes_schema' => json_encode([
                    'tipo' => ['CP I', 'CP II', 'CP III', 'CP IV', 'CP V-ARI'],
                    'embalagem' => ['Saco 50kg', 'Big Bag 1t'],
                    'marca' => ['Votorantim', 'Cimpor', 'Itambé', 'Outra'],
                ]),
            ],
            [
                'name' => 'Argamassas e Rejunte',
                'description' => 'Argamassas colantes, rejuntes e impermeabilizantes',
                'sku_prefix' => 'ARG',
                'attributes_schema' => json_encode([
                    'tipo' => ['Colante', 'Rejunte', 'Impermeabilizante', 'Chapisco'],
                    'aplicacao' => ['Piso', 'Parede', 'Fachada', 'Piscina'],
                    'embalagem' => ['Saco 20kg', 'Balde 20kg', 'Saco 40kg'],
                ]),
            ],
            [
                'name' => 'Cal e Gesso',
                'description' => 'Cal hidratada e gesso para acabamento',
                'sku_prefix' => 'CGE',
                'attributes_schema' => json_encode([
                    'tipo' => ['Cal Hidratada', 'Cal Virgem', 'Gesso'],
                    'embalagem' => ['Saco 20kg', 'Saco 40kg'],
                ]),
            ],
            
            // FERRO E AÇO
            [
                'name' => 'Ferro para Construção',
                'description' => 'Barras de ferro e vergalhões para estruturas',
                'sku_prefix' => 'FER',
                'attributes_schema' => json_encode([
                    'bitola' => ['5mm', '6mm', '8mm', '10mm', '12.5mm', '16mm', '20mm', '25mm'],
                    'comprimento' => ['6m', '12m'],
                    'tipo' => ['CA-50', 'CA-60'],
                ]),
            ],
            [
                'name' => 'Arame e Tela',
                'description' => 'Arames e telas para construção',
                'sku_prefix' => 'ARA',
                'attributes_schema' => json_encode([
                    'tipo' => ['Arame Farpado', 'Arame Galvanizado', 'Tela Soldada', 'Tela de Alambrado'],
                    'bitola' => ['16', '18', '20', '22'],
                ]),
            ],
            [
                'name' => 'Estruturas Metálicas',
                'description' => 'Perfis metálicos, vigas e estruturas em aço',
                'sku_prefix' => 'EST',
                'attributes_schema' => json_encode([
                    'tipo' => ['Viga I', 'Perfil U', 'Perfil L', 'Perfil Tubular'],
                    'material' => ['Aço Carbono', 'Aço Galvanizado', 'Aço Inox'],
                ]),
            ],
            
            // TIJOLOS E BLOCOS
            [
                'name' => 'Tijolos',
                'description' => 'Tijolos cerâmicos e maciços',
                'sku_prefix' => 'TIJ',
                'attributes_schema' => json_encode([
                    'tipo' => ['Baiano', 'Macico', 'Furrado', 'Modular'],
                    'dimensao' => ['9x19x19cm', '11x19x19cm', 'Outra'],
                ]),
            ],
            [
                'name' => 'Blocos de Concreto',
                'description' => 'Blocos de concreto para alvenaria estrutural',
                'sku_prefix' => 'BLO',
                'attributes_schema' => json_encode([
                    'tipo' => ['Estrutural', 'Vedação'],
                    'dimensao' => ['14x19x39cm', '19x19x39cm', 'Outra'],
                ]),
            ],
            [
                'name' => 'Blocos de Vidro',
                'description' => 'Blocos de vidro para iluminação natural',
                'sku_prefix' => 'BLV',
                'attributes_schema' => json_encode([
                    'dimensao' => ['19x19cm', '14x19cm'],
                    'cor' => ['Transparente', 'Fosco', 'Colorido'],
                ]),
            ],
            
            // AREIA E PEDRA
            [
                'name' => 'Areia',
                'description' => 'Areia para construção',
                'sku_prefix' => 'ARE',
                'attributes_schema' => json_encode([
                    'tipo' => ['Fina', 'Média', 'Grossa', 'Lavada'],
                    'embalagem' => ['m³', 'Saco 20kg'],
                ]),
            ],
            [
                'name' => 'Pedra e Brita',
                'description' => 'Pedras e britas para construção',
                'sku_prefix' => 'PED',
                'attributes_schema' => json_encode([
                    'tipo' => ['Brita 0', 'Brita 1', 'Brita 2', 'Pedra Rachão', 'Pó de Pedra'],
                    'embalagem' => ['m³', 'Saco 20kg'],
                ]),
            ],
            
            // TELHAS E COBERTURAS
            [
                'name' => 'Telhas',
                'description' => 'Telhas para cobertura',
                'sku_prefix' => 'TEL',
                'attributes_schema' => json_encode([
                    'tipo' => ['Cerâmica', 'Metálica', 'Fibrocimento', 'PVC'],
                    'modelo' => ['Portuguesa', 'Romana', 'Colonial', 'Americana'],
                    'cor' => ['Vermelha', 'Marrom', 'Verde', 'Cinza'],
                ]),
            ],
            [
                'name' => 'Calhas e Rufos',
                'description' => 'Calhas, rufos e peças para cobertura',
                'sku_prefix' => 'CRU',
                'attributes_schema' => json_encode([
                    'tipo' => ['Calha', 'Rufo', 'Tirante', 'Colete'],
                    'material' => ['Galvanizado', 'Alumínio', 'PVC'],
                ]),
            ],
            
            // PISOS E REVESTIMENTOS
            [
                'name' => 'Cerâmicas e Porcelanatos',
                'description' => 'Pisos e revestimentos cerâmicos',
                'sku_prefix' => 'CER',
                'attributes_schema' => json_encode([
                    'tipo' => ['Cerâmica', 'Porcelanato', 'Pastilha'],
                    'dimensao' => ['30x30cm', '45x45cm', '60x60cm', '60x120cm'],
                    'acabamento' => ['Polido', 'Acetinado', 'Esmaltado'],
                ]),
            ],
            [
                'name' => 'Pedras Naturais',
                'description' => 'Pedras naturais para revestimento',
                'sku_prefix' => 'PNT',
                'attributes_schema' => json_encode([
                    'tipo' => ['Granito', 'Mármore', 'Quartzito', 'Ardósia', 'Calcário'],
                    'acabamento' => ['Polido', 'Bruto', 'Escovado'],
                ]),
            ],
            [
                'name' => 'Pisos Laminados e Vinílicos',
                'description' => 'Pisos sintéticos e laminados',
                'sku_prefix' => 'PLV',
                'attributes_schema' => json_encode([
                    'tipo' => ['Laminado', 'Vinílico', 'PVC'],
                    'classe_uso' => ['AC3', 'AC4', 'AC5'],
                ]),
            ],
            
            // TINTAS E VERNIZES
            [
                'name' => 'Tintas',
                'description' => 'Tintas para parede, madeira e metal',
                'sku_prefix' => 'TIN',
                'attributes_schema' => json_encode([
                    'tipo' => ['Acrílica', 'PVA', 'Esmalte', 'Epóxi'],
                    'aplicacao' => ['Parede', 'Madeira', 'Metal', 'Piscina'],
                    'embalagem' => ['3,6L', '18L', '200L'],
                ]),
            ],
            [
                'name' => 'Vernizes e Impermeabilizantes',
                'description' => 'Vernizes, seladores e impermeabilizantes',
                'sku_prefix' => 'VER',
                'attributes_schema' => json_encode([
                    'tipo' => ['Verniz', 'Sela dor', 'Impermeabilizante', 'Massa Corrida'],
                    'aplicacao' => ['Madeira', 'Concreto', 'Terraço', 'Laje'],
                ]),
            ],
            
            // INSTALAÇÕES ELÉTRICAS
            [
                'name' => 'Fios e Cabos Elétricos',
                'description' => 'Fios e cabos para instalação elétrica',
                'sku_prefix' => 'FIO',
                'attributes_schema' => json_encode([
                    'bitola' => ['1.5mm²', '2.5mm²', '4mm²', '6mm²', '10mm²'],
                    'tipo' => ['Flexível', 'Rígido'],
                    'tensao' => ['750V', '1000V'],
                ]),
            ],
            [
                'name' => 'Disjuntores e Quadros',
                'description' => 'Disjuntores, quadros e componentes elétricos',
                'sku_prefix' => 'DIS',
                'attributes_schema' => json_encode([
                    'tipo' => ['Disjuntor Monopolar', 'Disjuntor Bipolar', 'Quadro', 'IDR'],
                    'corrente' => ['10A', '16A', '20A', '25A', '32A'],
                ]),
            ],
            [
                'name' => 'Tomadas e Interruptores',
                'description' => 'Tomadas, interruptores e acessórios elétricos',
                'sku_prefix' => 'TOM',
                'attributes_schema' => json_encode([
                    'tipo' => ['Tomada', 'Interruptor', 'Dimmer', 'Campainha'],
                    'tensao' => ['110V', '220V', 'Bivolt'],
                ]),
            ],
            
            // INSTALAÇÕES HIDRÁULICAS
            [
                'name' => 'Tubos e Conexões PVC',
                'description' => 'Tubos e conexões de PVC para esgoto',
                'sku_prefix' => 'PVC',
                'attributes_schema' => json_encode([
                    'diametro' => ['50mm', '75mm', '100mm', '150mm'],
                    'tipo' => ['Esgoto', 'Água', 'Ventilação'],
                ]),
            ],
            [
                'name' => 'Tubos e Conexões PPR',
                'description' => 'Tubos e conexões PPR para água',
                'sku_prefix' => 'PPR',
                'attributes_schema' => json_encode([
                    'diametro' => ['20mm', '25mm', '32mm', '40mm', '50mm'],
                    'pressao' => ['6 bar', '10 bar'],
                ]),
            ],
            [
                'name' => 'Tubos e Conexões Metálicas',
                'description' => 'Tubos e conexões de cobre, galvanizado e inox',
                'sku_prefix' => 'TUB',
                'attributes_schema' => json_encode([
                    'material' => ['Cobre', 'Galvanizado', 'Inox'],
                    'diametro' => ['½"', '¾"', '1"', '1¼"', '1½"'],
                ]),
            ],
            [
                'name' => 'Louças e Metais',
                'description' => 'Louças sanitárias e metais para banheiro',
                'sku_prefix' => 'LOU',
                'attributes_schema' => json_encode([
                    'tipo' => ['Vaso', 'Caixa Acoplada', 'Bacia', 'Chuveiro', 'Torneira'],
                    'material' => ['Inox', 'Cromado', 'Porcelana'],
                ]),
            ],
            
            // MADEIRA
            [
                'name' => 'Madeiras Brutas',
                'description' => 'Madeiras em toras e serradas',
                'sku_prefix' => 'MAD',
                'attributes_schema' => json_encode([
                    'tipo' => ['Eucalipto', 'Pinus', 'Cedro', 'Ipê', 'Peroba'],
                    'dimensao' => ['2x4cm', '3x6cm', '5x10cm', 'Tora'],
                ]),
            ],
            [
                'name' => 'Chapas de Madeira',
                'description' => 'Compensados, MDF, MDP e OSB',
                'sku_prefix' => 'CHP',
                'attributes_schema' => json_encode([
                    'tipo' => ['Compensado', 'MDF', 'MDP', 'OSB', 'Aglo merado'],
                    'dimensao' => ['122x244cm', '152x244cm'],
                    'espessura' => ['3mm', '6mm', '9mm', '12mm', '15mm', '18mm'],
                ]),
            ],
            [
                'name' => 'Portas e Janelas',
                'description' => 'Portas, janelas e esquadrias',
                'sku_prefix' => 'POR',
                'attributes_schema' => json_encode([
                    'tipo' => ['Porta', 'Janela', 'Basculante', 'Correr'],
                    'material' => ['Madeira', 'Alumínio', 'PVC', 'Ferro'],
                ]),
            ],
            
            // FERRAMENTAS E EQUIPAMENTOS
            [
                'name' => 'Ferramentas Manuais',
                'description' => 'Ferramentas manuais para construção',
                'sku_prefix' => 'FMA',
                'attributes_schema' => json_encode([
                    'tipo' => ['Marreta', 'Enxada', 'Pá', 'Enxó', 'Colher de Pedreiro'],
                    'material' => ['Aço', 'Fibra de Vidro'],
                ]),
            ],
            [
                'name' => 'Ferramentas Elétricas',
                'description' => 'Ferramentas elétricas e pneumáticas',
                'sku_prefix' => 'FEI',
                'attributes_schema' => json_encode([
                    'tipo' => ['Furadeira', 'Parafusadeira', 'Serra', 'Esmerilhadeira'],
                    'voltagem' => ['110V', '220V', 'Bateria'],
                ]),
            ],
            
            // SEGURANÇA E PROTEÇÃO
            [
                'name' => 'EPIs',
                'description' => 'Equipamentos de proteção individual',
                'sku_prefix' => 'EPI',
                'attributes_schema' => json_encode([
                    'tipo' => ['Capacete', 'Óculos', 'Luvas', 'Botas', 'Protetor Auricular'],
                    'tamanho' => ['P', 'M', 'G', 'GG', 'Único'],
                ]),
            ],
            [
                'name' => 'Sinalização e Segurança',
                'description' => 'Placas, fitas e equipamentos de sinalização',
                'sku_prefix' => 'SIG',
                'attributes_schema' => json_encode([
                    'tipo' => ['Placa', 'Fita', 'Cone', 'Cavalete', 'Barreira'],
                ]),
            ],
            
            // DIVERSOS
            [
                'name' => 'Vedação e Isolamento',
                'description' => 'Espumas, silicones e materiais de vedação',
                'sku_prefix' => 'VED',
                'attributes_schema' => json_encode([
                    'tipo' => ['Espuma Expansiva', 'Silicone', 'Lã de Vidro', 'Manta Asfáltica'],
                    'aplicacao' => ['Janelas', 'Portas', 'Telhado', 'Paredes'],
                ]),
            ],
            [
                'name' => 'Formas e Escoramentos',
                'description' => 'Formas de madeira e escoramentos metálicos',
                'sku_prefix' => 'FES',
                'attributes_schema' => json_encode([
                    'tipo' => ['Tábua', 'Sarrafos', 'Escoramento', 'Cimbramento'],
                ]),
            ],
            [
                'name' => 'Forros e Drywall',
                'description' => 'Chapas para forro e divisórias',
                'sku_prefix' => 'FRY',
                'attributes_schema' => json_encode([
                    'tipo' => ['Drywall', 'Lã de Rocha', 'PVC', 'Madeira'],
                    'espessura' => ['6mm', '9mm', '12mm', '15mm'],
                ]),
            ],
            [
                'name' => 'Impermeabilizantes',
                'description' => 'Membranas e produtos para impermeabilização',
                'sku_prefix' => 'IMP',
                'attributes_schema' => json_encode([
                    'tipo' => ['Membrana', 'Tinta', 'Manta', 'Betume'],
                    'aplicacao' => ['Laje', 'Piscina', 'Fundação', 'Cobertura'],
                ]),
            ],
            [
                'name' => 'Vidros e Espelhos',
                'description' => 'Vidros temperados, laminados e espelhos',
                'sku_prefix' => 'VID',
                'attributes_schema' => json_encode([
                    'tipo' => ['Temperado', 'Laminado', 'Comum', 'Espelho'],
                    'espessura' => ['3mm', '4mm', '5mm', '6mm', '8mm', '10mm'],
                ]),
            ],
            [
                'name' => 'Componentes de Concreto',
                'description' => 'Aditivos, fibras e componentes para concreto',
                'sku_prefix' => 'ADT',
                'attributes_schema' => json_encode([
                    'tipo' => ['Plastificante', 'Acelerador', 'Retardador', 'Fibras'],
                    'embalagem' => ['Litro', 'Saco 1kg', 'Saco 5kg'],
                ]),
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
