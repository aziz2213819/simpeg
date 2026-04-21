<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionLive extends Component
{
    use WithPagination;

    public $positionId;
    public $position_name;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'position_name' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->resetForm();

        $position = Position::findOrFail($id);

        $this->positionId = $position->id;
        $this->position_name = $position->position_name;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            Position::findOrFail($this->positionId)
                ->update([
                    'position_name' => $this->position_name
                ]);
        } else {
            Position::create([
                'position_name' => $this->position_name
            ]);
        }

        session()->flash('success', $this->isEdit ? 'Data Jabatan berhasil diperbarui!' : 'Data berhasil ditambahkan!');

        $this->closeModal();
    }

    public function delete($id)
    {
        $position = Position::withCount('employees')->findOrFail($id);

        if ($position->employees_count > 0) {
            session()->flash('error', "Gagal! Jabatan {$position->position_name} tidak bisa dihapus karena masih digunakan oleh {$position->employees_count} pegawai.");
            return;
        }
        $position->delete();
        session()->flash('success', 'Data Jabatan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['positionId', 'position_name', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        // 3. Tambahkan kondisi when() untuk filter pencarian
        $positions = Position::withCount('employees')
            ->when($this->search, function ($query) {
                $query->where('position_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.jabatan.index', [
            'positions' => $positions
        ]);
    }
}