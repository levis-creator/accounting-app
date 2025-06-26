<?php

namespace App\Livewire\tables;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class DashboardRecentTransactionTable extends PowerGridComponent
{
    public string $tableName = 'dashboard-recent-transaction-table';
    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }


    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showToggleColumns(false)
                ->showSearchInput(false),
            PowerGrid::footer()
                ->showPerPage(false)
                ->showRecordCount(true),
        ];
    }

    public function datasource(): Builder
    {
        return Transaction::query()
            ->with(['category', 'account'])
            ->where('user_id', Auth::id())
            ->where('transaction_date', '>=', now()->subDays(30))
            ->orderByDesc('transaction_date');
    }

    public function relationSearch(): array
    {
        return [
            'category' => ['name'],
            'account' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('category_id')
            ->add('category_name', fn(Transaction $model) => $model->category->name ?? '-')
            ->add('account_name', fn(Transaction $model) => $model->account->name ?? '-')
            ->add('type')
            ->add('amount', fn(Transaction $model) => number_format($model->amount, 2))
            ->add('description', fn(Transaction $model) => Str::limit($model->description, 30))
            ->add('transaction_date')
            ->add('transaction_date_formatted', fn(Transaction $model) => Carbon::parse($model->transaction_date)->format('d M'))
            ->add('created_at')
            ->add('created_at_formatted', fn(Transaction $model) => Carbon::parse($model->created_at)->format('d M, H:i'));
    }

    public function columns(): array
    {
        return [


            Column::make('Account', 'account_name')
                ->sortable()
                ->searchable(),

            Column::make('Category', 'category_name')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Amount', 'amount')
                ->sortable()
                ->bodyAttribute('text-right font-semibold text-green-600 dark:text-green-400'),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Date', 'transaction_date_formatted', 'transaction_date')
                ->sortable(),

            Column::action('Actions'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('openEditModal', ['id' => $rowId]);
    }

    public function actions(Transaction $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('text-blue-600 hover:text-blue-800 transition')
                ->dispatch('edit', ['rowId' => $row->id]),
        ];
    }
}
