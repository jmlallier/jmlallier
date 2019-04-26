<?php

return [
    'baseUrl' => 'http://localhost:3000/',
    'production' => true,
    'siteName' => "John-Michael L'Allier",
    'siteDescription' => 'Code thyself',
    'siteAuthor' => "John-Michael L'Allier",

    // collections
    'collections' => [
        'posts' => [
            'author' => 'John-Michael L\'Allier', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => '/{filename}',
        ],
        'categories' => [
            'path' => '/tags/{filename}',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
        ],
    ],

    // helpers
    'getDate' => function ($page) {
        return Datetime::createFromFormat('U', $page->date);
    },
    'getExcerpt' => function ($page, $length = 255) {
        $cleaned = strip_tags(
            preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $page->getContent()),
            '<code>'
        );

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
