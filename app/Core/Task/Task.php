<?php

namespace App\Core\Task;

use App\Core\File\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $remote_id
 * @property string $status
 * @property object $result
 * @property File $file
 */
class Task extends Model
{
    public const RECEIVED = 'received';
    public const SUCCESS = 'success';

    protected $casts = [
        'result' => 'object'
    ];

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
