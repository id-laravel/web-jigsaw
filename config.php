<?php

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'ID Laravel',
    'siteTagline' => 'Belajar Laravel Ala Indonesia',
    'siteDescription' => 'Tutorial Laravel Bahasa Indonesia',
    'siteAuthor' => 'Programmer Indonesia',
    'locale' => 'id',

    // collections
    'collections' => [
        'posts' => [
            'author' => 'Jon Dodo', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => 'post/{filename}',
            'posts' => function ($page, $allPosts) {
                return [];
            },
        ],
        'categories' => [
            'path' => '/post/categories/{filename}',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
        ],
    ],

    // helpers
    'getDate' => function ($page) {
    \Jenssegers\Date\Date::setLocale('id');

        return \Jenssegers\Date\Date::createFromFormat('U', $page->date)->format('j F Y');
    },
    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->excerpt;
        }

        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $cleaned = trim(
            strip_tags(
                preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0]),
                '<code>'
            )
        );

        if (count($content) > 1) {
            return $content[0];
        }

        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated) . '...'
            : $cleaned;
    },
    'isActive' => function ($page, $path) {
        return ends_with(trimPath($page->getPath()), trimPath($path));
    },
];
