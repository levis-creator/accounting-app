<?php

namespace App\Livewire\tables;

use App\Models\Account;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AccountsTable extends PowerGridComponent
{
    public string $tableName = 'accounts-table-d0vbp1-table';

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
        return Account::query()->where('user_id', Auth::id());
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
            ->add('balance')
            ->add('created_at_formatted', fn(Account $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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

            Column::make('Balance', 'balance')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->hidden(true)
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($row): void
    {

        $rowId = (string)$row['id'];
        $this->js("Livewire.navigate(`/accounts/{$rowId}/edit`);");
        $this->dispatch('editAccount', ['rowId' => $rowId]);
    }
    #[\Livewire\Attributes\On('view')]
    public function view($row): void
    {
        $rowId = (string) $row['id'];
        $this->js("Livewire.navigate(`/accounts/{$rowId}`);");
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($row): void
    {
        $rowId = (string) $row['id'];

        $account = Account::findOrFail($rowId);

        // Optional: Check if the user owns this account
        if ($account->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $account->delete();

        $this->dispatch('pg:deleted', [
            'title' => 'Deleted',
            'description' => 'The account has been deleted.',
        ]);
    }

    public function actions(Account $row): array
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
                ->dispatch('delete', ['row' => $row])

        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
