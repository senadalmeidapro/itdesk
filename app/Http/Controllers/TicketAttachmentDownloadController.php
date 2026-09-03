<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentDownloadController extends Controller
{
    public function __invoke(TicketAttachment $attachment): Response
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