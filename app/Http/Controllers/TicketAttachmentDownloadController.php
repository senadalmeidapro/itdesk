<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketAttachmentDownloadController extends Controller
{
    public function __invoke(TicketAttachment $attachment): StreamedResponse
    {
        $this->authorize('view', $attachment->ticket);

        abort_unless(
            Storage::disk($attachment->disk)->exists($attachment->path),
            404,
            'File no longer exists.'
        );

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_filename
        );
    }
}
