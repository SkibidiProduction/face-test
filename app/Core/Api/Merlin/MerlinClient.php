<?php

namespace App\Core\Api\Merlin;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class MerlinClient
{
    private string $url;
    private ?string $filePath = null;
    private ?string $fileName = null;
    private ?string $name = null;

    public function __construct()
    {
        $this->url = config('merlin.url');
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function send()
    {
        return Http::attach('photo', file_get_contents($this->filePath . $this->fileName), $this->fileName)
            ->post($this->url, ['name' => $this->name]);
    }

    /**
     * @param string $retryId
     * @return Response
     */
    public function retry(string $retryId): Response
    {
        return Http::asForm()->post($this->url, ['retry_id' => $retryId]);
    }
}
