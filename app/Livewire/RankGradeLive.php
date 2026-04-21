<?php

namespace App\Livewire;

use App\Models\RankGrade;
use Livewire\Component;
use Livewire\WithPagination;

class RankGradeLive extends Component
{
    use WithPagination;

    public $gradeId;
    public $grade_code;
    public $rank_name;
    public $isEdit = false;
    public $showModal = false;

    public function rules()
    {
        return [
            'grade_code' => 'required|string|max:255|unique:rank_grades,grade_code,' . $this->gradeId,
            'rank_name'  => 'nullable|string|max:255',
        ];
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

        $grade = RankGrade::findOrFail($id);

        $this->gradeId = $grade->id;
        $this->grade_code = $grade->grade_code;
        $this->rank_name = $grade->rank_name;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            RankGrade::findOrFail($this->gradeId)
                ->update([
                    'grade_code' => $this->grade_code,
                    'rank_name' => $this->rank_name
                ]);
        } else {
            RankGrade::create([
                'grade_code' => $this->grade_code,
                'rank_name' => $this->rank_name
            ]);
        }

        session()->flash('success', $this->isEdit ? 'Data Pangkat/Golongan berhasil diperbarui!' : 'Data berhasil ditambahkan!');

        $this->closeModal();
    }

    public function delete($id)
    {
        $grade = RankGrade::withCount('employees')->findOrFail($id);

        if ($grade->employees_count > 0) {
            session()->flash('error', "Gagal! Golongan {$grade->grade_code} tidak bisa dihapus karena masih digunakan oleh {$grade->employees_count} pegawai.");
            return;
        }
        $grade->delete();
        session()->flash('success', 'Data Pangkat/Golongan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['gradeId', 'grade_code', 'rank_name', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        $ranks = RankGrade::withCount('employees')
            ->latest()
            ->paginate(10);
        return view('admin.pangkat.index', [
            'ranks' => $ranks
        ]);
    }
}
