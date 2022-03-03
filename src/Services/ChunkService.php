<?php

namespace ThibeDev\DropzoneChunk\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChunkService
{
    private $requestTemplate;
    private $fileInput;
    private $exportPath;
    private $keepOriginalName;
    private $disk;
    private $tempDirectory;
    private $chunkExtention;
    private $actualFileChunk;

    private $uuid;
    private $chunkindex;
    private $totalchunkcount;

    private $uploaded = false;
    
    public function __construct()
    {
        $this->requestTemplate = config('dropzonechunk.request_template');
    }

    /**
     * @return int
     */
    public function getChunkindex() : int
    {
        return (int) $this->chunkindex;
    }

    /**
     * @return float
     */
    public function getPercentageDone() : float
    {
        return !$this->uploaded
            ? round($this->getTotalChunksUploaded() / $this->totalchunkcount  * 100, 2)
            : 100;
    }

    /**
     * @return int
     */
    public function getTotalChunksUploaded() : int
    {
        $chunksUploaded = 0;
        for($i = 0; $i < $this->totalchunkcount; $i++) {
            if(Storage::disk($this->disk)
                ->exists($this->tempDirectory . '/' . $this->uuid . '-' . $i . '.' . $this->chunkExtention)) {
                $chunksUploaded++;
            }
        }
        return $chunksUploaded;
    }

    /**
     * @return string
     */
    public function getFilename() : string
    {
        return $this->keepOriginalName
            ?  $this->actualFileChunk->getClientOriginalName()
            : $this->uuid . '.' . $this->actualFileChunk->getClientOriginalExtension();
    }

    /**
     * @return String
     */
    public function getUuid() : string
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getTotalchunksCount() : int
    {
        return (int)$this->totalchunkcount;
    }

    /**
     * @param Request $request
     * @param String $fileInput
     * @param string|null $exportPath
     * @param bool $keepOriginalName
     */
    public function init(Request $request, string $fileInput = 'file', string $exportPath = null, bool $keepOriginalName = false) : void
    {
        $this->fileInput = $fileInput;
        $this->exportPath = $exportPath;
        $this->actualFileChunk = $request->file($this->fileInput);
        $this->disk = config('dropzonechunk.disk');
        $this->tempDirectory = config('dropzonechunk.temp_directory');
        $this->chunkExtention = config('dropzonechunk.chunk_extention');
        $this->keepOriginalName = $keepOriginalName;
        foreach($this->requestTemplate as $key => $val) {
            $this->$key = $request->input($val);
        }
    }

    /**
     * @return array
     */
    public function mergeChunks() : array
    {
        $exportDisk = config('dropzonechunck.export_disk');
        $tempPath =  '/'. $this->tempDirectory;
        $exportPath = $this->exportPath ?? $tempPath;

        $tmpStream = tmpfile();
        for ($i = 0; $i < $this->totalchunkcount; $i++) {
            $buff = Storage::disk($this->disk)->readStream($tempPath.'/'.$this->uuid.'-'.$i.'.'. $this->chunkExtention);
            stream_copy_to_stream($buff, $tmpStream);
            Storage::disk($this->disk)->delete($tempPath.'/'.$this->uuid.'-'.$i.'.'. $this->chunkExtention);
        }
        Storage::disk($exportDisk)->put($exportPath.'/'.$this->getFilename(), $tmpStream);
        $this->uploaded = true;
        return [
            'name' => $this->getFilename(),
            'path' => $exportPath . '/' .  $this->getFilename()
        ];
    }

    /**
     * @param Request $request
     * @param string $fileInput
     * @param string|null $exportPath
     * @param bool $keepOriginalName
     * @return null|array
     */
    public function process(Request $request, string $fileInput = 'file', string $exportPath = null, bool $keepOriginalName = false)
    {
        self::init($request, $fileInput, $exportPath, $keepOriginalName);
        self::storeChunk();

        if(self::getTotalChunksUploaded() === self::getTotalchunksCount()) {
            return self::mergeChunks();
        }
        return null;
    }

    /**
     * @return string|false
     */
    public function storeChunk()
    {
        return Storage::disk($this->disk)
            ->putFileAs(
                $this->tempDirectory.'/',
                $this->actualFileChunk,
                $this->uuid .'-'.
                $this->chunkindex.'.'.
                $this->chunkExtention
        );
    }
}