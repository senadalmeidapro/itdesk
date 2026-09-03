<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DepartmentManager extends Component
{
    public array $departments = [];

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public function mount(): void
    {
        $this->authorize('manage-settings');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,'.$this->editingId],
        ];
    }

    public function edit(int $id): void
    {
        $department = Department::findOrFail($id);
        $this->editingId = $department->id;
        $this->name = $department->name;
        $this->code = $department->code;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Department::updateOrCreate(['id' => $this->editingId], $validated);

        $this->reset(['editingId', 'name', 'code']);
        session()->flash('success', 'Department saved.');
    }

    public function delete(int $id): void
    {
        Department::findOrFail($id)->delete();
        session()->flash('success', 'Department deleted.');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'name', 'code']);
    }

    public function render(): View
    {
        return view('livewire.admin.department-manager', [
            'departments' => Department::withCount('users')->orderBy('name')->get(),
        ]);
    }
}