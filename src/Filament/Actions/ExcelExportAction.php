<?php

declare(strict_types=1);

namespace Apiu\FilamentExcelBridge\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExcelExportAction extends Action
{
    protected string|Closure|null $exportClass = null;

    protected mixed $exportFilter = null;

    protected string|Closure $fileName = 'export';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export');

        $this->icon('heroicon-o-arrow-up-tray');

        $this->action(function (): BinaryFileResponse {
            $exportClass = $this->evaluate($this->exportClass);
            $filter = $this->evaluate($this->exportFilter);

            $timestamp = Date::now()->format('d-m-Y-H-i-s');
            $fileName = $this->evaluate($this->fileName).'-'.$timestamp.'.xlsx';

            $export = $filter !== null ? new $exportClass($filter) : new $exportClass();

            return Excel::download($export, $fileName);
        });
    }

    public static function getDefaultName(): string
    {
        return 'excelExport';
    }

    /**
     * @param  class-string<Exportable>|Closure  $exportClass
     */
    public function export(string|Closure $exportClass): static
    {
        $this->exportClass = $exportClass;

        return $this;
    }

    public function filterBy(mixed $filter): static
    {
        $this->exportFilter = $filter;

        return $this;
    }

    public function fileName(string|Closure $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
