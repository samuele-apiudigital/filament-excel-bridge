<?php

declare(strict_types=1);

use Apiu\FilamentExcelBridge\Http\ExportDownloadController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/filament-excel-bridge/export/download', ExportDownloadController::class)
        ->name('filament-excel-bridge.export.download')
        ->middleware('signed');
});
