<?php

declare(strict_types=1);

use Apiu\FilamentExcelBridge\Http\ExportDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/export/download', ExportDownloadController::class)
    ->name('filament-excel-bridge.export.download')
    ->middleware('signed');
