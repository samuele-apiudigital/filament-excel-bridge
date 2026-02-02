<?php

declare(strict_types=1);


use App\Exports\AssignedVouchersExport;
use App\Models\Brand;
use App\Models\Request;
use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExcelExportAction extends Action
{
    private Request|Brand|Closure|null $filter = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Esporta buoni assegnati');

        $this->icon('heroicon-o-arrow-up-tray');

        $this->action(function (): BinaryFileResponse {
            /** @var Brand|Request $filter */
            $filter = $this->evaluate($this->filter);

            $timestamp = Date::now()->format('d-m-Y-H-i-s');
            $fileName = 'buoni-assegnati-'.$timestamp.'.xlsx';

            return Excel::download(new AssignedVouchersExport($filter), $fileName);
        });
    }

    public static function getDefaultName(): string
    {
        return 'exportAssignedVouchers';
    }

    public function filterBy(Request|Brand|Closure|null $filter): self
    {
        $this->filter = $filter ?: Brand::query()->first();

        return $this;
    }
}
