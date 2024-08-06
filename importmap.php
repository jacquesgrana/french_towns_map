<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'map_vue' => [
        'path' => './assets/javascript/vue/MapVue.js',
        'entrypoint' => true,
    ],
    'map_manager' => [
        'path' => './assets/javascript/manager/MapManager.js',
        'entrypoint' => true,
    ],
    'town_service' => [
        'path' => './assets/javascript/service/TownService.js',
        'entrypoint' => true,
    ],
    'favorite_service' => [
        'path' => './assets/javascript/service/FavoriteService.js',
        'entrypoint' => true,
    ],
    'comment_service' => [
        'path' => './assets/javascript/service/CommentService.js',
        'entrypoint' => true,
    ],
    'security_service' => [
        'path' => './assets/javascript/service/SecurityService.js',
        'entrypoint' => true,
    ],
];
