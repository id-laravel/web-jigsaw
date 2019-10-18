<div class="flex flex-col mb-6">
    <p class="my-0 text-sm">
        {{ $post->getDate() }}
    </p>

    <h2 class="text-xl mt-0">
        <a
            href="{{ $post->getUrl() }}"
            title="Read more - {{ $post->title }}"
        >{{ $post->title }}</a>
    </h2>
</div>
