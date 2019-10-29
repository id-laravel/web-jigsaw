<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

    <meta property="og:title" content="{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="{{ $page->siteDescription }}"/>

    <title>{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}</title>

    <link rel="home" href="{{ $page->baseUrl }}">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="/blog/feed.atom" type="application/atom+xml" rel="alternate" title="{{ $page->siteName }} Atom Feed">

    @stack('meta')

    @if ($page->production)
        @includeWhen($page->production, '_scripts.ga')
    @endif

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,700|PT+Sans:400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
</head>

<body class="flex flex-col justify-between min-h-screen bg-gray-100 text-gray-800 leading-normal font-sans">
<header class="flex items-center shadow bg-white border-b h-24 py-4" role="banner">
    <div class="container flex items-center max-w-8xl mx-auto px-4 lg:px-8">
        <div class="flex items-center">
            <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                <h1 class="text-lg md:text-2xl text-red-600 font-semibold hover:text-red-800 my-0">
                    {{ $page->siteName }}
                </h1>
            </a>
            <div class="inline-flex items-center">
                <div class="opacity-75 text-base mx-8">{{ $page->siteTagline }}</div>
            </div>
        </div>

        <div id="vue-search" class="flex flex-1 justify-end items-center">
            <search></search>

            {{--                    @include('_nav.menu')--}}

            {{--                    @include('_nav.menu-toggle')--}}
        </div>
    </div>
</header>

@include('_nav.menu-responsive')

<main role="main" class="flex-auto w-full container max-w-3xl mx-auto py-16 px-6">
    @yield('body')

    @include('_components.newsletter-signup')

</main>


<div class="bg-white p-16 text-center md:text-left text-sm">
    <div class="container mx-auto w-full">
        <div class="sm:flex">
            <div class="sm:w-1/3 h-auto">
                <h5 class="mb-2">ID Laravel</h5>
                <ul class="list-reset leading-loose">
                    <li><a href="#">Yuk Kontribusi!</a></li>
                    <li><a href="#">Panduan Penulisan</a></li>
                    <li><a href="https://github.com/id-laravel/web-jigsaw">GitHub</a></li>
                </ul>
            </div>
            <div class="sm:w-1/3 h-auto sm:mt-0 mt-8">
                <h5>Sumber Ilmu</h5>
                <ul class="list-reset leading-loose">
                    <li><a href="http://laravel.com">Laravel.com</a> <small>~ Website resmi</small></li>
                    <li><a href="http://laravel-news.com">Laravel News</a> <small>~ Artikel pilihan</small></li>
                    <li><a href="http://laracasts.com">Laracasts</a> <small>~ Tutorial Video</small></li>
                    <li><a href="http://berbageek.com">Berbageek</a> <small>~ Tanya Jawab</small></li>
                </ul>
            </div>
            <div class="sm:w-1/3 h-auto sm:mt-0 mt-8">
                <h5>Kontak ID Laravel</h5>
                <p>
                    Kritik, saran, dan tawaran kerja sama atau kolaborasi bisa dikirimkan ke alamat email
                    <span class="font-bold font-heading">id.laravel@gmail.com</span>
                </p>
            </div>
        </div>

    </div>
</div>

<footer class="bg-white text-center text-xs pb-16" role="contentinfo">
    <ul class="justify-center">
        <li class="md:mr-2">
            Sebuah persembahan dari <a class="text-black font-heading font-bold" href="https://javan.co.id" title="PT Javan Cipta Solusi">PT Javan Cipta Solusi</a> untuk Indonesia.
        </li>
        <li>
            Website ini menggunakan <a class="text-black font-heading font-bold" href="http://jigsaw.tighten.co" title="Jigsaw by Tighten">Jigsaw</a>,
            <a class="text-black font-heading font-bold" href="https://tailwindcss.com" title="Tailwind CSS, a utility-first CSS framework">Tailwind CSS</a>,
            dan <a class="text-black font-heading font-bold" href="https://www.netlify.com/">netlify</a>.
        </li>
        <li class="my-4">
            2013 - {{ date('Y') }}
        </li>
    </ul>
</footer>

<script src="{{ mix('js/main.js', 'assets/build') }}"></script>

@stack('scripts')
</body>
</html>
