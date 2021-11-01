<?php

namespace App\Http\Controllers;

use App\Core\Api\Merlin\MerlinClient;
use App\Core\File\FileService;
use App\Core\Task\Task;
use App\Core\Task\TaskEntityManager;
use App\Core\User\User;
use App\Http\Requests\CheckRequest;
use App\Http\Requests\FaceUploadRequest;
use App\Http\Resources\ReadyResource;
use App\Http\Resources\TaskResource;
use App\Jobs\RetryFaceCheckScoring;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Core\File\File;

class FaceCheckController extends Controller
{
    /**
     * @param FaceUploadRequest $request
     * @param MerlinClient $merlinClient
     * @param TaskEntityManager $taskEntityManager
     * @param FileService $fileService
     * @return JsonResponse
     */
    public function upload(
        FaceUploadRequest $request,
        MerlinClient      $merlinClient,
        TaskEntityManager $taskEntityManager,
        FileService       $fileService
    ): JsonResponse
    {
        $fileData = $fileService->prepareUploadFileData($request->file('photo'));

        $user = User::where('name', $request->post('name'))->first();
        if (!$user) {
            $user = new User;
            $user->name = $request->post('name');
            $user->save();
        }
        $file = File::firstOrCreateByUserAndFileHash($user, $fileData->getFileName(), $fileData->getFileHash());

        if ($file->result) {
            return response()->json(new ReadyResource($file));
        }

        $response = $merlinClient->setFileName($fileData->getFileName())
            ->setFilePath($fileData->getFilePath())
            ->setName($user->name)
            ->send();

        if ($response->object()->status == 'success') {
            $file->result = $response->object()->result;
            $file->save();
            return response()->json(new ReadyResource($file));
        } else {
            $task = $taskEntityManager->prepareByRetryId($response->object(), $file->id);
            return response()->json(new TaskResource($task), 202);
        }
    }

    /**
     * @param CheckRequest $request
     * @param MerlinClient $merlinClient
     * @return JsonResponse
     */
    public function check(CheckRequest $request, MerlinClient $merlinClient): JsonResponse
    {
        $task = Task::find($request->get('task_id'));
        if (!$task) {
            return response()->json(['status' => 'not_found','result' => null], 404);
        } elseif ($task->status === Task::SUCCESS) {
            return response()->json(['status' => 'ready','result' => $task->file->result], 202);
        } else {
            $response = $merlinClient->retry($task->remote_id);
            if ($response->object()->status == 'success') {
                $task->file->result = $response->object()->result;
                $task->file->save();
                return response()->json(new ReadyResource($task->file));
            } else {
                return response()->json(['status' => 'wait','result' => null], 202);
            }
        }
    }
}
