<?php

Route::get(
    config('prometheus-exporter.path','hans/pe/metrics'),
    [\Hans\PrometheusExporter\Controller\LaravelController::class ,'metrics']
)->name('hans.pe.metrics');
