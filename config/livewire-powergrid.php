<?php
return [
    // 'theme' => \PowerComponents\LivewirePowerGrid\Themes\Bootstrap5::class,
    'theme' => \PowerComponents\LivewirePowerGrid\Themes\Tailwind::class,
    'persist_driver' => 'session',
    'filter' => 'outside',
     'plugins' => [
        // ...
        'flatpickr' => [
            // ..
            'locales'   => [
                'pt_BR' => [
                    'locale'     => 'pt',
                    'dateFormat' => 'd/m/Y H:i',
                    'enableTime' => true,
                    'time_24hr'  => true,
                ],
                'us' => [
                    'locale'     => 'us',
                    'dateFormat' => 'm/d/Y',
                    'enableTime' => true,
                    'time_24hr'  => false,
                ],
            ],
        ],
    ],

    // 'theme' => \PowerComponents\LivewirePowerGrid\Themes\DaisyUI::class,
];
