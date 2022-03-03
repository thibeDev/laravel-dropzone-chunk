<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ThibeDev\DropzoneChunk\Facades\ChunkService;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        /**
         * ChunkService will use local storage by default
         * As long as the upload isn't finished, $result will be null
         * The result will be an array with a key 'name' containing the filename => 'dzuuid.file_extension'
         * and a key 'path' containing the file path => 'app/temp/dzuuid.file_extension'
         */
        $result = ChunkService::process($request);
        if($result) {
              //do something with $result['name'] and $result['path']
        }

        return response()->json([
            'done' => ChunkService::getPercentageDone(),
        ], 200);
    }

    public function manualUpload(Request $request)
    {
        /**
         * Instead of using ChunkService::process($request), you can do all the steps by yourself
         * You can change the file input name from dropzone, the path where the file will be saved and keep the original file name
         */
        ChunkService::init($request, 'mySpecialInputName', '/public/media/', true);
        ChunkService::storeChunk();

        if(ChunkService::getTotalChunksUploaded() === ChunkService::getTotalchunksCount()) {
            $result = ChunkService::mergeChunks();
            //$result['path'] => '/public/media/originalFileName.originalFileExtention'
            //Storage::move($result['path'], 'path_where_the_file_should_go/'.$result['name']);
        }

        return response()->json([
            'done' => ChunkService::getPercentageDone(),
        ], 200);
    }
}