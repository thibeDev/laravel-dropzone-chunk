<?php

namespace ThibeDev\DropzoneChunk\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * Class ChunkService
 * @package ThibeDev\DropzoneChunk\Facades
 *
 * @method static int getChunkindex()
 * @method static float getPercentageDone()
 * @method static int getTotalChunksUploaded()
 * @method static string getUuid()
 * @method static int getTotalchunksCount()
 * @method static string getFilename()
 * @method static void init(Request $request, string $fileInput = 'file', string $exportPath = null, bool $keepOriginalName = false)
 * @method static array mergeChunks()
 * @method static null|array process(Request $request, string $fileInput = 'file', string $exportPath = null, bool $keepOriginalName = false)
 * @method static string|false storeChunk()
 *
 */

class ChunkService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'chunk-service';
    }
}