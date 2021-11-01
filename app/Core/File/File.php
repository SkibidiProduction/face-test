<?php

namespace App\Core\File;

use App\Core\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $name
 * @property string $hash
 * @property float $result
 */
class File extends Model
{
    /**
     * @param User $user
     * @param string $name
     * @param string $hash
     * @return File
     */
    public static function firstOrCreateByUserAndFileHash(User $user, string $name, string $hash): File
    {
        $file = self::where('user_id', $user->id)->where('hash', $hash)->first();
        if (!$file) {
            $file = new self();
            $file->user_id = $user->id;
            $file->name = $name;
            $file->hash = $hash;
            $file->save();
        }
        return $file;
    }
}
