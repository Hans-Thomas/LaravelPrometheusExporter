<?php

Route::get(
    'hans/pe/metrics',
    [\Hans\PrometheusExporter\Controller\LaravelController::class ,'metrics']
)->name('Hans.pe.metrics');
