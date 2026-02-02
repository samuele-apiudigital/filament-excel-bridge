<?php

declare(strict_types=1);

namespace Apiu\FilamentExcelBridge\Jobs;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

final class NotifyUserForExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Authenticatable $user,
        public string $fileName,
    ) {}

    public function handle(): void
    {
        $url = URL::temporarySignedRoute(
            'filament-excel-bridge.export.download',
            now()->addDays(7),
            ['file' => $this->fileName]
        );

        Notification::make()
            ->title('Export completato')
            ->body('Il tuo export è pronto per il download.')
            ->icon('heroicon-o-document-arrow-down')
            ->success()
            ->actions([
                Action::make('download')
                    ->label('Scarica')
                    ->url($url)
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($this->user);
    }
}
