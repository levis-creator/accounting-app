<?php

use Livewire\Volt\Component;
use Illuminate\Support\Js;

new class extends Component {
    public array $config;
}; ?>

<div x-data x-init="() => {
    const options = {{ Js::from($config) }};
    new ApexCharts($refs.chart, options).render();
}" x-ref="chart" class="w-full h-full"></div>
