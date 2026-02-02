<?php

declare(strict_types=1);

namespace Apiu\FilamentExcelBridge\Http;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportDownloadController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        /** @var string $fileName */
        $fileName = $request->query('file');

        abort_unless(Storage::disk('local')->exists($fileName), 404, 'File non trovato.');

        return Storage::disk('local')->download($fileName);
    }
}
