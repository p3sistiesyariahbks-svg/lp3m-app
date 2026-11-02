<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Rankbeam\Seo\Data\SEOData;

class PlacementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('placements/list', [])
            ->withSeo(new SEOData(
                title: 'Placements',
                description: 'Explore KKM, internship, and K2P placements.',
            ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
