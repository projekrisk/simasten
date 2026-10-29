<?php

$uploadsRoot = (function () {
    if ($custom = env('UPLOADS_PATH')) {
        return rtrim($custom, '/\\');
    }

    if (basename(base_path()) === 'public_html' && str_ends_with(public_path(), DIRECTORY_SEPARATOR.'public')) {
        return base_path('uploads');
    }

    return public_path('uploads');
})();

$uploadsUrl = (function () {
    if ($custom = env('UPLOADS_URL')) {
        return rtrim($custom, '/');
    }

    $appUrl = env('APP_URL');
    if ($appUrl && ! in_array(rtrim($appUrl, '/'), ['http://localhost', 'http://localhost:8000', 'https://localhost', 'https://localhost:8000'])) {
        return rtrim($appUrl, '/').'/uploads';
    }

    return '/uploads';
})();

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => $uploadsRoot,
            'url' => $uploadsUrl,
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'public_uploads' => [
            'driver' => 'local',
            'root' => $uploadsRoot,
            'url' => $uploadsUrl,
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'uploads' => [
            'driver' => 'local',
            'root' => $uploadsRoot,
            'url' => $uploadsUrl,
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'logo' => [
            'driver' => 'local',
            'root' => $uploadsRoot.'/logo',
            'url' => $uploadsUrl.'/logo',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'presensi' => [
            'driver' => 'local',
            'root' => $uploadsRoot.'/presensi',
            'url' => $uploadsUrl.'/presensi',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'spesimen' => [
            'driver' => 'local',
            'root' => $uploadsRoot.'/spesimen',
            'url' => $uploadsUrl.'/spesimen',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'imports' => [
            'driver' => 'local',
            'root' => $uploadsRoot.'/imports',
            'url' => $uploadsUrl.'/imports',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'pdf' => [
            'driver' => 'local',
            'root' => $uploadsRoot.'/pdf',
            'url' => $uploadsUrl.'/pdf',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
