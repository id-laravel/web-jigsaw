<div class="flex flex-col mb-4">
    <p class="text-gray-700 font-medium my-2">
        {{ $post->getDate()->format('j F Y') }}
    </p>

    <h2 class="text-xl mt-0">
        <a
            href="{{ $post->getUrl() }}"
            title="Read more - {{ $post->title }}"
            class="text-gray-900 font-extrabold"
        >{{ $post->title }}</a>
    </h2>
</div>
