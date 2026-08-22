<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="{{ data_get($site->seo, 'description') ?? $site->tagline }}">
        <meta name="keywords" content="{{ data_get($site->seo, 'keywords') }}">
        <meta name="robots" content="{{ data_get($site->seo, 'robots', 'index, follow') }}">
        @if (data_get($site->seo, 'canonical'))
            <link rel="canonical" href="{{ data_get($site->seo, 'canonical') }}">
        @endif

        <meta property="og:type" content="{{ data_get($site->og, 'type', 'website') }}">
        <meta property="og:site_name" content="{{ data_get($site->og, 'site_name') ?? $site->brand_name }}">
        <meta property="og:title" content="{{ data_get($site->og, 'title') ?? data_get($site->seo, 'title') ?? $site->brand_name }}">
        <meta property="og:description" content="{{ data_get($site->og, 'description') ?? data_get($site->seo, 'description') ?? $site->tagline }}">
        <meta property="og:url" content="{{ url()->current() }}">
        @if ($ogImage = $site->asset(data_get($site->og, 'image')))
            <meta property="og:image" content="{{ url($ogImage) }}">
        @endif
        <meta name="twitter:card" content="{{ data_get($site->og, 'twitter_card', 'summary_large_image') }}">

        <link rel="icon" href="{{ $site->asset($site->favicon) ?? '/favicon.ico' }}" sizes="any">
        @unless ($site->favicon)
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
            <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @endunless

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])

        {{-- Colours from /admin → Pengaturan → Warna & Tampilan. Loaded after the
             stylesheet so they win over the Tailwind theme defaults. --}}
        @if ($themeCss)
            <style>:root{ {!! $themeCss !!} }</style>
        @endif

        <x-inertia::head>
            <title>{{ data_get($site->seo, 'title') ?? $site->brand_name }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
