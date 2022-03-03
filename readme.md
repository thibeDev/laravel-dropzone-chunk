# Laravel Dropzone Chunk Upload

Just a little Laravel package that help to handle requests from dropzone.js out of the box.
It can be adapted for other front end libraries with few configuration options.

> This package works with Laravel 9 only.

## Installation

**1. Install via composer**

```
composer require thibedev/laravel-dropzone-chunk
```

**2. Publish the config (Optional)**

```
php artisan vendor:publish --provider="ThibeDev\DropzoneChunk\Providers\DropzoneChunkServiceProvider"
```

## Usage

After the installation, you can use the Facade provided : ChunkService
If you use dropzone and local storage, you can use this library without any configuration.
It is as simple as doing

```
$result = ChunkService::process($request);
```

Once all the chunks will be uploaded, $result will be an array containing the name and the path of the uploaded file.

You can provide 3 more arguments to ChunkService::process() method :

- The name of the request input file, default 'file'
- The path where you want to save the uploaded file, default '/temp'
- A boolean to keep the original file name, default false

```
$result = ChunkService::process($request, 'mySpecialInputName', '/public/media/', true);
```

If you want to use another frontend provider than dropzone, you can publish the config and change the request_template key or you can use the environment variables.
The uuid should be a unique id 
The chunkindex is the actual chunk index
And the totalchunkcount is the total chunk
Quite easy :)

```
'request_template' => [
        'uuid' => env('DROPZONE_CHUNK_UUID', 'dzuuid'),
        'chunkindex' => env('DROPZONE_CHUNK_UUID', 'dzchunkindex'),
        'totalchunkcount' => env('DROPZONE_CHUNK_TOTAL_CHUNKCOUNT', 'dztotalchunkcount'),
    ],
```

About the storage disk, I haven't tried it with s3 or ftp but it should work, let me know if it works for you !

By default, it will use the local storage, create a temp directory, save all the chunks with a .part extension and finally merge them all in the temp directory with the uuid as name and the original file extension.
You can change the temp directory name, the chunk extension and the export disk using the config file or the env vars.

There is two exemples in the exemples directory of this repo.

I know this is not the best library and it might be improved a lot but it does the job and I didn't find any other one that works with Laravel 9...