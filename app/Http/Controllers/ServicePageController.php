<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServicePageController extends Controller
{
    public function index(): View
    {
        return view('services', [
            'services' => config('public-services.services'),
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $services = config('public-services.services');
        $service = collect($services)->firstWhere('slug', $slug);

        abort_unless($service !== null, 404);

        return view('services.show', [
            'service' => $service,
            'services' => $services,
        ]);
    }
}
