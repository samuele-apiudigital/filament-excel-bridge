<?php

declare(strict_types=1);

namespace Apiu\FilamentExcelBridge\Filament\Actions;

use Apiu\FilamentExcelBridge\Jobs\NotifyUserForExport;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExcelExportAction extends Action
{
    protected string|Closure|null $exportClass = null;

    protected bool $shouldQueue = true;

    protected mixed $exportFilter = null;

    protected string|Closure $fileName = 'export.xls';

    protected string|Closure $notificationMessage = 'Export in progress. You will receive a notification when it is ready.';

    protected string|Closure $downloadMessage = 'Export is ready to download.';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export');

        $this->icon('heroicon-o-arrow-up-tray');

        $this->action(function (): ?BinaryFileResponse {
            $exportClass = $this->evaluate($this->exportClass);
            $shouldQueue = $this->evaluate($this->shouldQueue);
            $timestamp = Date::now()->format('d-m-Y-H-i-s');
            $notificationMessage = $this->evaluate($this->notificationMessage);
            $fileName = $this->evaluate($this->fileName).'-'.$timestamp.'.xlsx';
            $downloadMessage = $this->evaluate($this->downloadMessage);

            if ($shouldQueue) {
                Notification::make('Success')
                    ->success()
                    ->body($notificationMessage);
                Excel::queue($exportClass, $fileName)
                    ->chain([
                        NotifyUserForExport::dispatch(Auth::user(), $fileName, $downloadMessage)
                    ]);
                return null;
            } else {
                return Excel::download($exportClass, $fileName);
            }

        });
    }

    public static function getDefaultName(): string
    {
        return 'excelExport';
    }

    /**
     * @param string|Closure|null $exportClass
     * @return ExcelExportAction
     */
    public function export(string|Closure|null $exportClass): static
    {
        $this->exportClass = $exportClass;

        return $this;
    }

    public function shouldQueue(bool|Closure|null $flag = true): static
    {
        $this->shouldQueue = $flag;

        return $this;
    }

    public function notificationMessage(string|Closure $message): static
    {
        $this->notificationMessage = $message;

        return $this;
    }

    public function downloadMessage(string|Closure $message): static
    {
        $this->downloadMessage = $message;

        return $this;
    }

    public function fileName(string|Closure $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
