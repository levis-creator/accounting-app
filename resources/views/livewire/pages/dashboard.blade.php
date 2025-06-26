<?php

namespace App\Livewire\Components\Dashboard;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Livewire\Components\Dashboard\Support\FinancialMetricsCalculator;
new class extends Component {
    public array $stats = [];
    public array $chartData = [];

    public function mount(): void
    {
        $this->stats = FinancialMetricsCalculator::calculateStats();
        $this->chartData = FinancialMetricsCalculator::generateChartData();
    }
};
?>
<x-slot:title>
    Dashboard
</x-slot:title>
<div>
    <livewire:components.page-header  title="Dashboard"  />

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Stats Cards --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            @foreach ($stats as $stat)
                <livewire:components.dashboard.stat-card :title="$stat['title']" :value="$stat['value']" :icon="$stat['icon']"
                    :percentage="$stat['percentage']" :increased="$stat['increased']" />
            @endforeach
        </div>

        {{-- Charts Section --}}
        <div class="grid gap-4 grid-cols-1 md:grid-cols-3 h-full">
            {{-- Income vs Expenses Chart --}}
            <div
                class="relative h-full col-span-2 flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:components.dashboard.income-expense-chart class="w-full h-full min-h-[400px]"
                    :chart-data="$chartData" />
            </div>

            {{-- Placeholder for additional chart --}}
            <div
                class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:components.dashboard.expense-chart class="w-full h-full min-h-[400px]" />
            </div>
        </div>

        {{-- Additional placeholder --}}
        {{-- <div class="relative h-fit flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <livewire:tables.dashboard-recent-transaction-table />
        </div> --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm p-4">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Recent Transactions</h2>
            <livewire:tables.dashboard-recent-transaction-table />
        </div>
    </div>
</div>
