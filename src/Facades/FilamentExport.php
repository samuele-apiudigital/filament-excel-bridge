<?php

namespace Apiu\FilamentExcelBridge\Facades;

use Apiu\FilamentExcelBridge\Services\FilamentExportService;
use Illuminate\Support\Facades\Facade;

/**
 * @see FilamentExportService
 */
class FilamentExport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilamentExportService::class;
    }
}
