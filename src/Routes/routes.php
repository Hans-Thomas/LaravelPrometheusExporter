<?php

Route::get(
    config('prometheus-exporter.path'),
    [\Hans\PrometheusExporter\Controller\LaravelController::class ,'metrics']
)->name('Hans.pe.metrics');
