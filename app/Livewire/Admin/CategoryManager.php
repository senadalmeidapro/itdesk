<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Department;
use App\Models\SlaPolicy;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryManager extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public ?int $department_id = null;

    public ?int $default_sla_policy_id = null;

    public function mount(): void
    {
        $this->authorize('manage-settings');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'default_sla_policy_id' => ['nullable', 'exists:sla_policies,id'],
        ];
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->department_id = $category->department_id;
        $this->default_sla_policy_id = $category->default_sla_policy_id;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Category::updateOrCreate(['id' => $this->editingId], $validated);

        $this->reset(['editingId', 'name', 'department_id', 'default_sla_policy_id']);
        session()->flash('success', 'Category saved.');
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Category deleted.');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'name', 'department_id', 'default_sla_policy_id']);
    }

    public function render(): View
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::with(['department', 'defaultSlaPolicy'])->withCount('tickets')->orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'slaPolicies' => SlaPolicy::orderBy('name')->get(),
        ]);
    }
}