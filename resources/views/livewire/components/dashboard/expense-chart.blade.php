<?php

use Livewire\Volt\Component;
use App\Livewire\Components\Dashboard\Support\FinancialMetricsCalculator;

new class extends Component {
    public array $expenseChartData = [];

    public function mount()
    {
        $this->expenseChartData = FinancialMetricsCalculator::generateExpenseBreakdownByCategory();
    }
};
?>

<div
    x-data="{
        chartData: @js($expenseChartData),
        chart: null,
        init() {
            this.initChart();
            this.setupThemeWatcher();
        },
        initChart() {
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not loaded');
                return;
            }

            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            const options = {
                chart: {
                    type: 'donut',
                    height: '100%',
                    width: '100%',
                    foreColor: isDark ? '#e2e8f0' : '#334155',
                    background: 'transparent',
                },
                title: {
                    text: 'Expense Breakdown (30d)',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: isDark ? '#e2e8f0' : '#334155'
                    }
                },
                labels: this.chartData.labels,
                series: this.chartData.series,
                colors: [
                    '#f87171', '#fbbf24', '#34d399', '#60a5fa',
                    '#a78bfa', '#f472b6', '#fb923c', '#2dd4bf'
                ],
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: val => 'KES ' + val.toFixed(2)
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };

            if (this.chart) this.chart.destroy();

            this.chart = new ApexCharts(this.$refs.chart, options);
            this.chart.render();
        },
        setupThemeWatcher() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                const isDark = e.matches;
                this.chart?.updateOptions({
                    chart: {
                        foreColor: isDark ? '#e2e8f0' : '#334155'
                    },
                    title: {
                        style: {
                            color: isDark ? '#e2e8f0' : '#334155'
                        }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    }
                });
            });
        }
    }"
    x-ref="chart"
    wire:ignore
    class="w-full h-full min-h-[400px]"
></div>
