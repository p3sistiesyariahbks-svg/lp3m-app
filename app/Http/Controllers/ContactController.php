<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Rankbeam\Seo\Data\SEOData;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('contact', [])
            ->withSeo(new SEOData(
                title: 'Contact',
                description: 'Our friendly team is always here to chat.',
            ));
    }
}
