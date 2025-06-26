<?php

namespace App\Livewire\tables;

use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'category-table-thvyy5-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query()->where('user_id', Auth::id());
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('type')
            ->add('created_at_formatted', fn(Category $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable()
                ->searchable(),

                Column::make('Created at', 'created_at_formatted', 'created_at')
                ->hidden(true) // Hide this column
                ->sortable(),
            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datetimepicker('created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($row): void
    {
        $rowId = (string)$row['id'];
        $this->js("Livewire.navigate(`/categories/{$rowId}/edit`);");
        $this->dispatch('editCategory', ['rowId' => $rowId]);
    }

    #[\Livewire\Attributes\On('view')]
    public function view($row): void
    {
        $rowId = (string)$row['id'];
        $this->js("Livewire.navigate(`/categories/{$rowId}`);");
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($row): void
    {
        $rowId = (string)$row['id'];

        $category = Category::findOrFail($rowId);

        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $category->delete();

        $this->dispatch('pg:deleted', [
            'title' => 'Deleted',
            'description' => 'The category has been deleted.',
        ]);
    }

    public function actions(Category $row): array
    {
        return [
            Button::add('view')
                ->slot('View')
                ->id()
                ->class('pg-btn-white text-blue-500 hover:text-white hover:bg-blue-500 border border-blue-500')
                ->dispatch('view', ['row' => $row]),

            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('pg-btn-white text-yellow-500 hover:text-white hover:bg-yellow-500 border border-yellow-500')
                ->dispatch('edit', ['row' => $row]),

            Button::add('delete')
                ->slot('Delete')
                ->id()
                ->class('pg-btn-white text-red-500 hover:text-white hover:bg-red-500 border border-red-500')
                ->dispatch('delete', ['row' => $row]),
        ];
    }
}
