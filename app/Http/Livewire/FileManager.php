<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManager extends Component
{
    use WithFileUploads;

    public $baseDirectory = 'images';
    public $currentPath = 'images';
    public $directories = [];
    public $files = [];
    
    // Para Upload
    public $uploads = [];
    
    // Para Modal
    public $showCreateModal = false;
    public $newDirectoryName = '';
    
    // Modo Seletor
    public $pickerMode = false;
    public $targetModel = null;
    public $multiple = false;
    
    // Status e avisos
    public $disk = 'public'; // Usar o disco 'public' que aponta para storage/app/public em vez do root de public

    protected $listeners = [
        'refresh-file-manager' => 'loadDirectory',
    ];

    public function mount($pickerMode = false, $targetModel = null, $multiple = false)
    {
        $this->pickerMode = $pickerMode;
        $this->targetModel = $targetModel;
        $this->multiple = $multiple;

        // Certificar que o baseDirectory existe
        if (!Storage::disk($this->disk)->exists($this->baseDirectory)) {
            Storage::disk($this->disk)->makeDirectory($this->baseDirectory);
        }
        $this->loadDirectory();
    }

    public function render()
    {
        // Quando usado como componente inline (picker mode ou embed), não aplicar layout
        if ($this->pickerMode) {
            return view('livewire.file-manager');
        }
        return view('livewire.file-manager')
            ->layout('components.app-layout');
    }

    public function loadDirectory()
    {
        try {
            // Segurança contra diretórios forjados
            if (strpos($this->currentPath, '..') !== false) {
                $this->currentPath = $this->baseDirectory;
            }

            // Listar pastas
            $dirs = Storage::disk($this->disk)->directories($this->currentPath);
            $this->directories = array_map(function($dir) {
                return [
                    'name' => basename($dir),
                    'path' => $dir,
                    'isEditing' => false
                ];
            }, $dirs);

            // Listar arquivos
            $files = Storage::disk($this->disk)->files($this->currentPath);
            $this->files = array_map(function($file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'extension' => strtolower($extension),
                    'size' => $this->formatSize(Storage::disk($this->disk)->size($file)),
                    'last_modified' => date('d/m/Y H:i', Storage::disk($this->disk)->lastModified($file)),
                    'url' => '/storage/' . $file,
                    'is_image' => in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'])
                ];
            }, $files);
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar diretório no FileManager: ' . $e->getMessage());
            $this->directories = [];
            $this->files = [];
            
            if (config('app.debug')) {
                $this->dispatch('notification', [
                    'type' => 'error',
                    'message' => 'Erro de sistema: ' . $e->getMessage()
                ]);
            }
        }
    }

    public function enterDirectory($name)
    {
        $this->currentPath = rtrim($this->currentPath, '/') . '/' . $name;
        $this->loadDirectory();
    }

    public function upDirectory()
    {
        if ($this->currentPath !== $this->baseDirectory) {
            $parts = explode('/', $this->currentPath);
            array_pop($parts);
            // Nunca deixa passar da base
            if (empty($parts) || !in_array($this->baseDirectory, $parts)) {
                $this->currentPath = $this->baseDirectory;
            } else {
                $this->currentPath = implode('/', $parts);
            }
            $this->loadDirectory();
        }
    }

    public function getBreadcrumbsProperty()
    {
        $parts = array_filter(explode('/', $this->currentPath));
        $breadcrumbs = [];
        $accumulated = '';

        foreach ($parts as $part) {
            $accumulated = $accumulated ? $accumulated . '/' . $part : $part;
            
            $name = $part;
            if ($part === 'images') $name = 'Galeria';
            if ($part === 'storage') $name = 'Arquivos';

            $breadcrumbs[] = [
                'name' => $name,
                'path' => $accumulated
            ];
        }

        return $breadcrumbs;
    }

    public function switchRoot($newRoot)
    {
        if (!in_array($newRoot, ['images', 'storage'])) return;

        // Se for storage, apontar para a raiz do disco ''
        $this->baseDirectory = $newRoot === 'storage' ? '' : $newRoot;
        $this->currentPath = $this->baseDirectory;

        // Certificar que o diretório existe (se não for a raiz)
        if ($this->baseDirectory !== '' && !Storage::disk($this->disk)->exists($this->baseDirectory)) {
            Storage::disk($this->disk)->makeDirectory($this->baseDirectory);
        }

        $this->loadDirectory();
    }

    public function navigateTo($path)
    {
        $this->currentPath = $path;
        $this->loadDirectory();
    }

    public function selectMedia($path, $url)
    {
        if ($this->pickerMode) {
            // Em Livewire 3, dispatch com array envia os dados como único argumento
            // O listener recebe como: handleMediaSelected($path, $url, $targetModel)
            $this->dispatch('media-selected', $path, $url, $this->targetModel);
        }
    }

    // --- Upload ---
    
    public function updatedUploads()
    {
        $this->validate([
            'uploads.*' => 'required|max:10240', // Max 10MB por arquivo
        ]);

        foreach ($this->uploads as $file) {
            // Preservar o nome original
            $filename = $file->getClientOriginalName();
            
            // Garantir arquivo único
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            $finalName = $filename;
            $counter = 1;
            while (Storage::disk($this->disk)->exists($this->currentPath . '/' . $finalName)) {
                $finalName = $baseName . '-' . $counter . '.' . $extension;
                $counter++;
            }

            $file->storeAs($this->currentPath, $finalName, ['disk' => $this->disk]);
        }

        $this->uploads = []; // Limpar uploads
        $this->loadDirectory();
        
        $this->dispatch('notification', [
            'type' => 'success',
            'message' => 'Upload realizado com sucesso!'
        ]);
    }

    // --- Criar Pasta ---

    public function createDirectory()
    {
        $this->validate([
            'newDirectoryName' => 'required|min:1|max:50|regex:/^[a-zA-Z0-9_\-]+$/'
        ]);

        $newPath = $this->currentPath . '/' . $this->newDirectoryName;

        if (Storage::disk($this->disk)->exists($newPath)) {
            $this->addError('newDirectoryName', 'Uma pasta com este nome já existe.');
            return;
        }

        Storage::disk($this->disk)->makeDirectory($newPath);
        $this->showCreateModal = false;
        $this->newDirectoryName = '';
        $this->loadDirectory();
        
        $this->dispatch('notification', [
            'type' => 'success',
            'message' => 'Pasta criada com sucesso!'
        ]);
    }

    // --- Download ---

    public function downloadDirectory($path)
    {
        $folderName = basename($path);
        $zipName = $folderName . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'zip') . '.zip';
        
        $zip = new \ZipArchive();
        if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = Storage::disk($this->disk)->allFiles($path);
            
            foreach ($files as $file) {
                // Caminho relativo dentro do ZIP
                $relativePath = substr($file, strlen($path) + 1);
                $zip->addFromString($relativePath, Storage::disk($this->disk)->get($file));
            }
            
            $zip->close();
            
            return response()->download($tempFile, $zipName)->deleteFileAfterSend(true);
        }
        
        $this->dispatch('notification', [
            'type' => 'error',
            'message' => 'Erro ao gerar o arquivo ZIP.'
        ]);
        return null;
    }

    public function downloadFile($path, $name)
    {
        return Storage::disk($this->disk)->download($path, $name);
    }

    // --- Deletar ---

    public function deleteItem($path, $isDir = false)
    {
        // Proteção contra deletar a pasta base
        if ($path === $this->baseDirectory) return;
        if (!$path || strpos($path, '..') !== false) return;

        if ($isDir) {
            // Deletar pasta e todo o conteúdo recursivamente
            Storage::disk($this->disk)->deleteDirectory($path);
        } else {
            Storage::disk($this->disk)->delete($path);
        }

        $this->loadDirectory();
        
        $this->dispatch('notification', [
            'type' => 'success',
            'message' => $isDir ? 'Pasta e todo seu conteúdo foram excluídos.' : 'Arquivo excluído.'
        ]);
    }

    public function moveItem($sourcePath, $destinationPath)
    {
        // Proteger contra mover algo que não existe ou para diretórios pais inválidos
        if (!Storage::disk($this->disk)->exists($sourcePath)) return;
        
        if (strpos($destinationPath, '..') !== false) {
            $destinationPath = $this->baseDirectory;
        }

        $filename = basename($sourcePath);
        $newPath = $destinationPath . '/' . $filename;
        
        // Se a pasta de destino é a mesma de origem, ignorar
        if (dirname($sourcePath) === $destinationPath) return;

        // Anti-sobrescrita
        if (Storage::disk($this->disk)->exists($newPath)) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => 'Um item com esse nome já existe no destino.'
            ]);
            return;
        }

        Storage::disk($this->disk)->move($sourcePath, $newPath);
        $this->loadDirectory();
        
        $this->dispatch('notification', [
            'type' => 'success',
            'message' => 'Item movido com sucesso!'
        ]);
    }

    // --- Utils ---

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
