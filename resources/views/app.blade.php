<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ config('seo.favicon') }}" sizes="any">

        @fonts
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @filamentStyles(['none'])

        <x-inertia::head>
            @if(isset($seoModel))
                @seo($seoModel)
            @elseif(isset($seoRoute))
                @seoForRoute($seoRoute)
            @else
                @seo(null)
            @endif
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
