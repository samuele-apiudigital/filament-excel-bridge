<?php

declare(strict_types=1);

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\AgentPanelController;
use App\Http\Controllers\DownloadVouchersController;
use App\Http\Controllers\ExportDownloadController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RestockUploadController;
use App\Livewire\AcceptVouchers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/export/download', ExportDownloadController::class)
    ->name('export.download')
    ->middleware('signed');

