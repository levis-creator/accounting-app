<?php

namespace App\Livewire\tables;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TransactionTable extends PowerGridComponent
{
    public string $tableName = 'transaction-table';

    protected TransactionService $service;

    public function boot(TransactionService $service): void
    {
        $this->service = $service;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Transaction::query()
            ->with('account', 'category')
            ->where('user_id', Auth::id()) // ensures only own data is queried
            ->orderBy('transaction_date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('label')
            ->add('type')
            ->add('amount')
            ->add('account', fn(Transaction $model) => $model->account->name)
            ->add('category', fn(Transaction $model) => $model->category->name)
            ->add('description')
            ->add('transaction_date_formatted', fn(Transaction $model) => Carbon::parse($model->transaction_date)->format('d/m/Y'))
            ->add('created_at_formatted', fn(Transaction $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Label', 'label')->sortable()->searchable(),
            Column::make('Type', 'type')->sortable()->searchable(),
            Column::make('Amount', 'amount')->sortable()->searchable(),
            Column::make('Account', 'account')->sortable()->searchable(),
            Column::make('Category', 'category')->sortable()->searchable(),
            Column::make('Description', 'description')->sortable()->searchable(),
            Column::make('Transaction Date', 'transaction_date_formatted', 'transaction_date')->sortable(),
            Column::make('Created At', 'created_at_formatted', 'created_at')->hidden(true)->sortable(),
            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('transaction_date'),
        ];
    }

    #[\Livewire\Attributes\On('view')]
    public function view($row): void
    {
        $transaction = Transaction::findOrFail($row['id']);

        if (!Gate::allows('view', $transaction)) {
            abort(403, 'Unauthorized');
        }

        $this->js("Livewire.navigate(`/transactions/{$transaction->id}`);");
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($row): void
    {
        $transaction = Transaction::findOrFail($row['id']);

        if (!Gate::allows('update', $transaction)) {
            abort(403, 'Unauthorized');
        }

        $this->js("Livewire.navigate(`/transactions/{$transaction->id}/edit`);");
        $this->dispatch('editTransaction', ['rowId' => $transaction->id]);
    }

    #[\Livewire\Attributes\On('deleteTransaction')]
    public function delete($row): void
    {
        $transaction = Transaction::findOrFail($row['id']);

        if (!Gate::allows('delete', $transaction)) {
            abort(403, 'Unauthorized');
        }

        $this->service->delete($transaction);
        $this->dispatch('pg:deleted', [
            'title' => 'Deleted',
            'description' => 'The transaction has been deleted.',
        ]);
    }

    public function actions(Transaction $row): array
    {
        $actions = [];

        if (Gate::allows('view', $row)) {
            $actions[] = Button::add('view')
                ->slot('View')
                ->id()
                ->class('pg-btn-white text-blue-500 hover:text-white hover:bg-blue-500 border border-blue-500')
                ->dispatch('view', ['row' => $row]);
        }

        if (Gate::allows('update', $row)) {
            $actions[] = Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('pg-btn-white text-yellow-500 hover:text-white hover:bg-yellow-500 border border-yellow-500')
                ->dispatch('edit', ['row' => $row]);
        }

        if (Gate::allows('delete', $row)) {
            $actions[] = Button::add('delete')
                ->slot('Delete')
                ->id()
                ->class('pg-btn-white text-red-500 hover:text-white hover:bg-red-500 border border-red-500')
                ->dispatch('deleteTransaction', ['row' => $row]);
        }

        return $actions;
    }
}
