<?php

namespace App\Livewire;

use App\Models\Grade as GradeModel;
use Livewire\Component;
use Livewire\WithPagination;

class Grade extends Component
{
    use WithPagination;

    public $showModal = false;
    public $grade_code;
    public $gradeId;
    public $isEdit = false;

    protected function rules() {
        return [
            'grade_code' => 'required|string|max:255'
        ];
    }

    public function anjay() {
        dd("anjay");
    }

     public function open()
    {
        $this->resetValidation();
        $this->showModal = true;
        }
        
    public function close() {
        
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->reset(['grade_code', 'gradeId', 'isEdit']);
    }

    public function store()
    {
        $this->validate();

        GradeModel::create([
            'grade_code' => $this->grade_code,
        ]);

        $this->resetForm();
    }

    public function edit($id)
    {
        $grade = GradeModel::findOrFail($id);

        $this->gradeId = $grade->id;
        $this->grade_code = $grade->grade_code;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        GradeModel::findOrFail($this->grade_code)->update([
            'grade_code' => $this->grade_code,
        ]);

        $this->resetForm();
    }

    public function delete($id)
    {
        GradeModel::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('admin.golongan.index', [
            'grades' => GradeModel::latest()->paginate(10),
        ]);
    }
}
