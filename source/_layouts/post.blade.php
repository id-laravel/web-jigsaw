@extends('_layouts.master')

@push('meta')
    <meta property="og:title" content="{{ $page->title }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="{{ $page->description }}"/>
@endpush

@section('body')
    @if ($page->cover_image)
        <img src="{{ $page->cover_image }}" alt="{{ $page->title }} cover image" class="mb-2">
    @endif

    <div class="text-center">
        <p class="text-base font-heading md:my-0">
        <div class="text-sm">{{ $page->getDate() }} &bull; {{ $page->author }}</div>
        </p>
        <h1 class="leading-none mb-2">{{ $page->title }}</h1>
    </div>

    {{--    @if ($page->categories)--}}
    {{--        <div class="mb-10 mt-4">--}}
    {{--            @foreach ($page->categories as $i => $category)--}}
    {{--                <a--}}
    {{--                        href="{{ '/post/categories/' . $category }}"--}}
    {{--                        title="View posts in {{ $category }}"--}}
    {{--                        class="inline-block bg-gray-200 font-bold hover:bg-red-200 leading-loose tracking-wide text-gray-800 uppercase text-xs rounded mr-1 px-3 pt-px"--}}
    {{--                >{{ $category }}</a>--}}
    {{--            @endforeach--}}
    {{--        </div>--}}
    {{--    @endif--}}

    <hr>

    <div class="mb-10 pb-4 markdown" v-pre>
        {!! $page->content !!}
    </div>

    <nav class="text-sm border-t-2 border-b-2 my-8 py-8 text-center">
        Mungkin Anda tertarik dengan artikel menarik lainnya:
        <ol>
            @if ($next = $page->getNext())
                <li>
                    <a href="{{ $next->getUrl() }}" title="Older Post: {{ $next->title }}">
                        {{ $next->title }}
                    </a>
                </li>
            @endif
            @if ($previous = $page->getPrevious())
                <li>
                    <a href="{{ $previous->getUrl() }}" title="Newer Post: {{ $previous->title }}">
                        {{ $previous->title }}
                    </a>
                </li>
            @endif
        </ol>
    </nav>

    @include('_scripts.disqus', compact('page'))

@endsection
