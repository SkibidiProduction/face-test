<?php

namespace App\Core\File;

class FileDataTransfer
{
    public string $fileHash;
    public string $fileName;
    public string $filePath;

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFileHash(): string
    {
        return $this->fileHash;
    }

    /**
     * @param string $fileHash
     */
    public function setFileHash(string $fileHash): void
    {
        $this->fileHash = $fileHash;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }
}
