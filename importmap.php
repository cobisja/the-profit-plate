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
 *
 * This file has been auto-generated by the importmap commands.
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
    'bootstrap' => [
        'version' => '5.3.2',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.2',
        'type' => 'css',
    ],
    'formvalidation' => [
        'version' => '0.6.2-dev',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables.net-bs5' => [
        'version' => '2.0.2',
    ],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => [
        'version' => '2.0.2',
        'type' => 'css',
    ],
    'datatables.net' => [
        'version' => '2.0.2',
    ],
    'sweetalert2' => [
        'version' => '11.10.5',
    ],
    'jszip' => [
        'version' => '3.10.1',
    ],
    'datatables.net-dt' => [
        'version' => '2.0.2',
    ],
    'datatables.net-dt/css/dataTables.dataTables.min.css' => [
        'version' => '2.0.2',
        'type' => 'css',
    ],
    'datatables.net-buttons/js/buttons.html5' => [
        'version' => '3.0.1',
    ],
    'datatables.net-buttons/js/buttons.print' => [
        'version' => '3.0.1',
    ],
    'datatables.net-buttons-dt' => [
        'version' => '3.0.1',
    ],
    'datatables.net-buttons-dt/css/buttons.dataTables.min.css' => [
        'version' => '3.0.1',
        'type' => 'css',
    ],
    'datatables.net-buttons' => [
        'version' => '3.0.1',
    ],
    'pdfmake/build/pdfmake' => [
        'version' => '0.2.10',
    ],
];
