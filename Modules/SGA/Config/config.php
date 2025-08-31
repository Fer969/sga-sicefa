<?php

return [
    'name' => 'SGA',
    'providers' => [
        Barryvdh\DomPDF\ServiceProvider::class,
    ],

    'aliases' => [
        'PDF' => Barryvdh\DomPDF\Facade::class,
    ],
];
