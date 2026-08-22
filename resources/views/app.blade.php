<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Judul & deskripsi dicetak server per halaman (lihat App\Support\PageSeo)
             supaya crawler tetap dapat isinya tanpa menjalankan JavaScript. --}}
        <meta name="description" content="{{ $seo['description'] }}">
        @if ($keywords)
            <meta name="keywords" content="{{ $keywords }}">
        @endif
        <meta name="robots" content="{{ data_get($site->seo, 'robots') ?: 'index, follow' }}">
        <link rel="canonical" href="{{ data_get($site->seo, 'canonical') ?: url()->current() }}">

        <meta property="og:type" content="{{ data_get($site->og, 'type', 'website') }}">
        <meta property="og:site_name" content="{{ data_get($site->og, 'site_name') ?: $site->brand_name }}">
        <meta property="og:title" content="{{ data_get($site->og, 'title') ?: $seo['title'] }}">
        <meta property="og:description" content="{{ data_get($site->og, 'description') ?: $seo['description'] }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:locale" content="id_ID">
        @if ($seo['image'])
            <meta property="og:image" content="{{ url($seo['image']) }}">
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

        {{-- Warna dari /admin → Pengaturan → Warna & Tampilan. Dimuat setelah
             stylesheet supaya menimpa tema bawaan Tailwind. --}}
        @if ($themeCss)
            <style>:root{ {!! $themeCss !!} }</style>
        @endif

        <x-inertia::head>
            <title>{{ $seo['title'] }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
