<?php

namespace App\Core\File;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    public function prepareUploadFileData($file): FileDataTransfer
    {
        $dto = new FileDataTransfer();
        $dto->setFileName(Str::random(12) . '-' . $file->getClientOriginalName());
        $dto->setFilePath(config('uploading.photo'));
        $file->move($dto->getFilePath(), $dto->getFileName());
        $dto->setFileHash(hash_file('md5', $dto->getFilePath() . $dto->getFileName()));

        return $dto;
    }
}
