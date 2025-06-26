<?php

namespace App\Livewire\Forms;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GenericForm extends Form
{
    public ?Model $model = null;

    public function setModel(Model $model, array $attributes = []): void
    {
        $this->model = $model;
        $this->fill($attributes ?: $model->getAttributes());
    }

    public function store(array $data, string $modelClass): void
    {
        $this->model = $modelClass::create($data);
        $this->resetForm();
    }

    public function update(array $data): void
    {
        $this->model?->update($data);
        $this->resetForm();
    }

    public function delete(Model|string|null $model = null): void
    {
        $target = $model instanceof Model ? $model
            : ($model ? $this->model::findOrFail($model) : $this->model);

        $target?->delete();
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset();
        $this->model = null;
    }

    public function mountModel(string $modelClass, string|int|null $id, array $attributes = []): void
    {
        if ($id) {
            $this->model = $modelClass::findOrFail($id);
            $this->fill($attributes ?: $this->model->getAttributes());
        }
    }
}
