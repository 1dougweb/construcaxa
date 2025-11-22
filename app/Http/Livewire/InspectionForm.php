<?php

namespace App\Http\Livewire;

use App\Models\Inspection;
use App\Models\Client;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class InspectionForm extends Component
{
    use WithFileUploads;

    public $inspection;
    public $client_id;
    public $inspection_date;
    public $address;
    public $description;
    public $inspector_id;
    public $status = 'draft';
    public $notes;
    public $photos = [];
    public $tempPhotos = [];
    public $clients;
    public $inspectors;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'inspection_date' => 'required|date',
        'address' => 'nullable|string|max:500',
        'description' => 'nullable|string',
        'inspector_id' => 'required|exists:users,id',
        'status' => 'required|in:draft,pending,approved,rejected',
        'notes' => 'nullable|string',
        'tempPhotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ];

    protected $messages = [
        'client_id.required' => 'É necessário selecionar um cliente.',
        'client_id.exists' => 'O cliente selecionado não é válido.',
        'inspection_date.required' => 'A data da vistoria é obrigatória.',
        'inspection_date.date' => 'A data da vistoria deve ser uma data válida.',
        'inspector_id.required' => 'É necessário selecionar um responsável pela vistoria.',
        'inspector_id.exists' => 'O responsável selecionado não é válido.',
        'status.required' => 'O status é obrigatório.',
        'status.in' => 'O status selecionado não é válido.',
    ];

    public function mount($inspection = null)
    {
        $this->clients = Client::active()->orderBy('name')->get();
        $this->inspectors = User::orderBy('name')->get();

        if ($inspection) {
            $this->inspection = $inspection;
            $this->client_id = $inspection->client_id;
            $this->inspection_date = $inspection->inspection_date->format('Y-m-d');
            $this->address = $inspection->address;
            $this->description = $inspection->description;
            $this->inspector_id = $inspection->inspector_id;
            $this->status = $inspection->status;
            $this->notes = $inspection->notes;
            $this->photos = $inspection->photos ?? [];
        } else {
            $this->inspection_date = now()->format('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Processar fotos
            $uploadedPhotos = [];
            foreach ($this->tempPhotos as $tempPhoto) {
                $path = $tempPhoto->store('inspections/photos', 'public');
                $uploadedPhotos[] = $path;
            }
            $allPhotos = array_merge($this->photos, $uploadedPhotos);

            // Calcular versão se for nova vistoria
            $version = 1;
            if (!$this->inspection) {
                $lastVersion = Inspection::where('client_id', $this->client_id)->max('version');
                $version = ($lastVersion ?? 0) + 1;
            } else {
                $version = $this->inspection->version;
            }

            $data = [
                'client_id' => $this->client_id,
                'inspection_date' => $this->inspection_date,
                'address' => $this->address,
                'description' => $this->description,
                'inspector_id' => $this->inspector_id,
                'status' => $this->status,
                'notes' => $this->notes,
                'photos' => !empty($allPhotos) ? $allPhotos : null,
            ];

            if ($this->inspection) {
                $this->inspection->update($data);
                $inspection = $this->inspection;
                $message = 'Vistoria atualizada com sucesso!';
            } else {
                $data['number'] = Inspection::generateNumber();
                $data['version'] = $version;
                $inspection = Inspection::create($data);
                $message = 'Vistoria criada com sucesso!';
            }

            DB::commit();

            session()->flash('success', $message);
            return redirect()->route('inspections.show', $inspection);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar vistoria: ' . $e->getMessage());
        }
    }

    public function removePhoto($index)
    {
        if ($index < count($this->photos)) {
            // Foto existente
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        } else {
            // Foto temporária
            $tempIndex = $index - count($this->photos);
            unset($this->tempPhotos[$tempIndex]);
            $this->tempPhotos = array_values($this->tempPhotos);
        }
    }

    public function render()
    {
        return view('livewire.inspection-form');
    }
}
