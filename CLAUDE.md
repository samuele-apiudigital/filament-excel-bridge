# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel package (`apiu/filament-excel-bridge`) that bridges Filament v4 with Laravel Excel (maatwebsite/excel). It provides Excel export functionality for Filament admin panels with support for async exports, notifications, and signed download URLs.

**Requirements:** PHP 8.2+, Laravel 11.31+, Filament 4.5.3+, maatwebsite/excel 3.1.67+

## Commands

```bash
# Install dependencies
composer install

# No test suite configured yet
```

## Architecture

### Package Structure

- **Namespace:** `Apiu\FilamentExcelBridge`
- **Auto-discovery:** Package registers via Laravel's package auto-discovery (`Apiu\FilamentExcelBridge\ServiceProvider`)

### Core Components

**`src/ServiceProvider.php`**
Registers package routes.

**`src/Filament/Actions/ExcelExportAction.php`**
Generic Filament Action for Excel exports. Configurable via fluent methods:
- `export($exportClass)` - Set the export class (must implement Maatwebsite\Excel\Concerns\Exportable)
- `filterBy($filter)` - Pass a filter/model to the export class constructor
- `fileName($name)` - Set the download filename prefix

**`src/Jobs/NotifyUserForExport.php`**
Queued job that sends a Filament database notification with a signed download URL (7-day expiry) when async exports complete. Uses `Authenticatable` interface for user type flexibility.

**`src/Http/ExportDownloadController.php`**
Invokable controller that handles signed URL downloads from local storage.

**`routes/web.php`**
Defines the `filament-excel-bridge.export.download` route with signed middleware.

### Key Patterns

- Actions use closures for lazy evaluation (`$this->evaluate()`)
- Exports use temporary signed URLs for secure downloads
- Notifications are stored in database (requires Filament's database notifications)
- All components use generic interfaces/types for reusability across projects
