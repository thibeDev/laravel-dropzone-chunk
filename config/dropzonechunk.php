<?php

return [
    'disk' => env('DROPZONE_CHUNK_DISK', 'local'),
    'export_disk' => env('DROPZONE_CHUNK_EXPORT_DISK', 'local'),

    'request_template' => [
        'uuid' => env('DROPZONE_CHUNK_UUID', 'dzuuid'),
        'chunkindex' => env('DROPZONE_CHUNK_UUID', 'dzchunkindex'),
        'totalchunkcount' => env('DROPZONE_CHUNK_TOTAL_CHUNKCOUNT', 'dztotalchunkcount'),
    ],

    'temp_directory' =>  env('DROPZONE_CHUNK_TEMP_DIRECTORY', 'temp'),
    'chunk_extention' =>  env('DROPZONE_CHUNK_CHUNK_EXTENTION', 'part'),
];