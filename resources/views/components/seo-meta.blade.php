@props([
    'title',
    'description',
    'canonical' => null,
    'robots' => 'index, follow',
    'ogImage' => null,
])

@php
    $canonical = $canonical ?? request()->url();
    $ogImage = $ogImage ?? asset('images/nurud-logo.png');
    $title = trim($title);
    $description = trim($description);
@endphp

<title>{!! $title !!}</title>
<meta name="description" content="{{ $description }}">
@if($canonical)
    <link rel="canonical" href="{{ $canonical }}">
@endif
<meta name="robots" content="{{ $robots }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Nurud Travels">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">