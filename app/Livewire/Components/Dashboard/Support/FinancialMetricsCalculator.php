<?php

namespace App\Livewire\Components\Dashboard\Support;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FinancialMetricsCalculator
{
    protected const REPORT_PERIOD_DAYS = 30;
    protected const CURRENCY = 'KES';

    public static function calculateStats(): array
    {
        $currentPeriod = DateRangeHelper::getLastNDays(self::REPORT_PERIOD_DAYS);
        $previousPeriod = DateRangeHelper::getPreviousPeriod($currentPeriod['start'], $currentPeriod['end']);

        $currentData = self::getAggregatedData($currentPeriod['start'], $currentPeriod['end']);
        $previousData = self::getAggregatedData($previousPeriod['start'], $previousPeriod['end']);

        $revenueChange = self::calculatePercentageChange($previousData['revenue'], $currentData['revenue']);
        $expenseChange = self::calculatePercentageChange($previousData['expenses'], $currentData['expenses']);

        $netSavings = $currentData['revenue'] - $currentData['expenses'];
        $previousNetSavings = $previousData['revenue'] - $previousData['expenses'];
        $savingsChange = self::calculatePercentageChange($previousNetSavings, $netSavings);

        $topCategory = self::getTopExpenseCategory($currentPeriod['start'], $currentPeriod['end']);

        return [
            self::createStatCardData(
                'Total Revenue (30d)',
                $currentData['revenue'],
                'wallet',
                $revenueChange,
                $currentData['revenue'] >= $previousData['revenue']
            ),
            self::createStatCardData(
                'Total Expenses (30d)',
                $currentData['expenses'],
                'credit-card',
                $expenseChange,
                $currentData['expenses'] >= $previousData['expenses']
            ),
            self::createStatCardData(
                'Net Savings (30d)',
                $netSavings,
                'banknotes',
                $savingsChange,
                $netSavings >= $previousNetSavings
            ),
            [
                'title' => "Top Category: {$topCategory['name']}",
                'value' => self::CURRENCY . ' ' . number_format($topCategory['amount'], 2),
                'icon' => 'trophy',
                'percentage' => null,
                'increased' => true,
            ],
        ];
    }

    public static function generateChartData(): array
    {
        $dateRange = DateRangeHelper::getLastNDays(self::REPORT_PERIOD_DAYS);
        $dates = DateRangeHelper::generateDateSeries($dateRange['start'], $dateRange['end']);

        $incomeSeries = [];
        $expenseSeries = [];

        foreach ($dates as $date) {
            $incomeSeries[] = (float) Transaction::where('type', 'income')
                ->where('user_id', Auth::id())
                ->whereDate('transaction_date', $date)
                ->sum('amount');

            $expenseSeries[] = (float) Transaction::where('type', 'expense')
                ->whereDate('transaction_date', $date)
                ->where('user_id', Auth::id())
                ->sum('amount');
        }

        return [
            'categories' => $dates,
            'income' => $incomeSeries,
            'expenses' => $expenseSeries,
        ];
    }

    protected static function getAggregatedData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'revenue' => Transaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->where('user_id', Auth::id())
                ->sum('amount'),
            'expenses' => Transaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->where('user_id', Auth::id())
                ->sum('amount'),
        ];
    }

    protected static function calculatePercentageChange(float $previous, float $current): float
    {
        if ($previous == 0 && $current == 0) {
            return 0;
        }

        if ($previous == 0) {
            return 100;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
    protected static function createStatCardData(
        string $title,
        float $value,
        string $icon,
        float $percentage,
        bool $increased
    ): array {
        return [
            'title' => $title,
            'value' => self::CURRENCY . ' ' . number_format($value, 2),
            'icon' => $icon,
            'percentage' => $percentage,
            'increased' => $increased,
        ];
    }
    protected static function getTopExpenseCategory(Carbon $startDate, Carbon $endDate): array
    {
        $topCategory = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->where('type', 'expense')
            ->where('user_id', Auth::id())
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->first();

        return [
            'name' => $topCategory?->category->name ?? 'N/A',
            'amount' => $topCategory?->total ?? 0,
        ];
    }
    public static function generateExpenseBreakdownByCategory(): array
    {
        $dateRange = DateRangeHelper::getLastNDays(self::REPORT_PERIOD_DAYS);

        $results = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->where('type', 'expense')
            ->where('user_id', Auth::id())
            ->whereBetween('transaction_date', [$dateRange['start'], $dateRange['end']])
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $labels = [];
        $series = [];

        foreach ($results as $row) {
            $labels[] = $row->category->name ?? 'Uncategorized';
            $series[] = round($row->total, 2);
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }
}
