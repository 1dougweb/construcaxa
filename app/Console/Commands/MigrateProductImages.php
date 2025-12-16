<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Equipment;
use App\Models\InspectionItemPhoto;

class MigrateProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:migrate-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra todas as imagens de public/images/ para storage/app/public/ (produtos, equipamentos e vistorias)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando migração de todas as imagens...');

        // Garantir que o link simbólico do storage esteja criado
        $this->info('Verificando link simbólico do storage...');
        if (!File::exists(public_path('storage'))) {
            $this->info('Criando link simbólico do storage...');
            $this->call('storage:link');
        } else {
            $this->info('Link simbólico já existe.');
        }

        // Garantir que os diretórios de destino existem
        foreach (['products', 'equipment', 'inspections'] as $dir) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
                $this->info("Diretório storage/app/public/{$dir} criado.");
            }
        }

        $totalMigrated = 0;
        $totalSkipped = 0;
        $totalErrors = 0;

        // Migrar produtos
        $this->newLine();
        $this->info('=== MIGRANDO PRODUTOS ===');
        list($migrated, $skipped, $errors) = $this->migrateProducts();
        $totalMigrated += $migrated;
        $totalSkipped += $skipped;
        $totalErrors += $errors;

        // Migrar equipamentos
        $this->newLine();
        $this->info('=== MIGRANDO EQUIPAMENTOS ===');
        list($migrated, $skipped, $errors) = $this->migrateEquipment();
        $totalMigrated += $migrated;
        $totalSkipped += $skipped;
        $totalErrors += $errors;

        // Migrar vistorias
        $this->newLine();
        $this->info('=== MIGRANDO VISTORIAS ===');
        list($migrated, $skipped, $errors) = $this->migrateInspections();
        $totalMigrated += $migrated;
        $totalSkipped += $skipped;
        $totalErrors += $errors;

        $this->newLine();
        $this->info("=== MIGRAÇÃO CONCLUÍDA ===");
        $this->info("  - Total migrados: {$totalMigrated}");
        $this->info("  - Total ignorados: {$totalSkipped}");
        $this->info("  - Total erros: {$totalErrors}");

        $this->newLine();
        $this->warn('IMPORTANTE: Após verificar que tudo está funcionando, você pode deletar manualmente os diretórios public/images/ se não houver mais imagens lá.');

        return Command::SUCCESS;
    }

    private function migrateProducts()
    {
        $products = Product::whereNotNull('photos')->get();
        $this->info("Encontrados {$products->count()} produtos com fotos.");

        return $this->migrateArrayPhotos($products, 'photos', 'images/products/', 'products/');
    }

    private function migrateEquipment()
    {
        $equipment = Equipment::whereNotNull('photos')->get();
        $this->info("Encontrados {$equipment->count()} equipamentos com fotos.");

        return $this->migrateArrayPhotos($equipment, 'photos', 'images/equipment/', 'equipment/');
    }

    private function migrateInspections()
    {
        $photos = InspectionItemPhoto::where('photo_path', 'like', 'images/inspections/%')->get();
        $this->info("Encontradas {$photos->count()} fotos de vistorias para migrar.");

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($photos as $photo) {
            $oldPath = $photo->photo_path;
            
            if (strpos($oldPath, 'images/inspections/') === 0) {
                $filePath = public_path($oldPath);
                
                if (File::exists($filePath)) {
                    $filename = basename($oldPath);
                    $newPath = 'inspections/' . $filename;
                    
                    try {
                        $content = File::get($filePath);
                        Storage::disk('public')->put($newPath, $content);
                        $photo->update(['photo_path' => $newPath]);
                        $migrated++;
                        $this->line("  ✓ Migrado: {$oldPath} -> {$newPath}");
                    } catch (\Exception $e) {
                        $this->error("  ✗ Erro ao migrar {$oldPath}: " . $e->getMessage());
                        $errors++;
                    }
                } else {
                    $this->warn("  ⚠ Arquivo não encontrado: {$oldPath}");
                    $skipped++;
                }
            }
        }

        return [$migrated, $skipped, $errors];
    }

    private function migrateArrayPhotos($models, $attribute, $oldPrefix, $newPrefix)
    {
        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($models as $model) {
            $photos = $model->$attribute;
            if (!$photos || !is_array($photos)) {
                continue;
            }

            $updatedPhotos = [];
            $needsUpdate = false;

            foreach ($photos as $photoPath) {
                if (empty($photoPath)) {
                    continue;
                }

                // Se já está no storage, manter
                if (strpos($photoPath, $newPrefix) === 0 && !strpos($photoPath, 'images/')) {
                    $updatedPhotos[] = $photoPath;
                    continue;
                }

                // Se é caminho antigo, migrar
                if (strpos($photoPath, $oldPrefix) === 0) {
                    $oldPath = public_path($photoPath);
                    
                    if (File::exists($oldPath)) {
                        $filename = basename($photoPath);
                        $newPath = $newPrefix . $filename;
                        
                        try {
                            $content = File::get($oldPath);
                            Storage::disk('public')->put($newPath, $content);
                            $updatedPhotos[] = $newPath;
                            $needsUpdate = true;
                            $migrated++;
                            $this->line("  ✓ Migrado: {$photoPath} -> {$newPath}");
                        } catch (\Exception $e) {
                            $this->error("  ✗ Erro ao migrar {$photoPath}: " . $e->getMessage());
                            $errors++;
                            $updatedPhotos[] = $photoPath;
                        }
                    } else {
                        $this->warn("  ⚠ Arquivo não encontrado: {$photoPath}");
                        $skipped++;
                    }
                } else {
                    $updatedPhotos[] = $photoPath;
                }
            }

            if ($needsUpdate) {
                $model->update([$attribute => array_values(array_filter($updatedPhotos))]);
            }
        }

        return [$migrated, $skipped, $errors];
    }
}
