<?php

namespace App\Http\Controllers;

use App\Data\CountryDestinations;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryDestinationController extends Controller
{
    public function index(): View
    {
        return view('destinations.index', [
            'countries' => CountryDestinations::all(),
        ]);
    }

    public function show(string $slug): View
    {
        $country = CountryDestinations::find($slug);

        abort_unless($country !== null, 404);

        return view('destinations.show', [
            'country' => $country,
        ]);
    }
}