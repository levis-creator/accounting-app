<?php
use Livewire\Volt\Component;

new class extends Component {
    public array $chartData = [];
};
?>

<div
    x-data="{
        chartData: @js($chartData),
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
                    type: 'line',
                    height: '100%',
                    width: '100%',
                    toolbar: { show: false },
                    foreColor: isDark ? '#e2e8f0' : '#334155',
                    background: 'transparent',
                    zoom: {
                        enabled: false // Disable zooming
                    }
                },
                title: {
                    text: 'Income vs Expenses - Last 30 Days',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: isDark ? '#e2e8f0' : '#334155'
                    }
                },
                series: [
                    { name: 'Income', data: this.chartData.income },
                    { name: 'Expenses', data: this.chartData.expenses }
                ],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                colors: isDark ? ['#38bdf8', '#f87171'] : ['#2563eb', '#dc2626'],
                xaxis: {
                    type: 'datetime',
                    categories: this.chartData.categories,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val.toLocaleString('en-US');
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    y: {
                        formatter: function(val) {
                            return 'KES ' + val.toFixed(2);
                        }
                    },
                    marker: {
                        show: true
                    }
                },
                grid: {
                    borderColor: isDark ? '#374151' : '#e5e7eb'
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
                        foreColor: isDark ? '#e2e8f0' : '#334155',
                        zoom: {
                            enabled: false // Keep zoom disabled on theme change
                        }
                    },
                    title: {
                        style: {
                            color: isDark ? '#e2e8f0' : '#334155'
                        }
                    },
                    colors: isDark ? ['#38bdf8', '#f87171'] : ['#2563eb', '#dc2626'],
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    },
                    grid: {
                        borderColor: isDark ? '#374151' : '#e5e7eb'
                    }
                });
            });
        }
    }"
    x-ref="chart"
    wire:ignore
    class="w-full h-full min-h-[400px]"
></div>
