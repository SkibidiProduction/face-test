<?php

namespace App\Core\Task;

class TaskEntityManager
{
    /**
     * @param Task $task
     * @param object $response
     * @return bool
     */
    public function updateAfterRetry(Task $task, object $response): bool
    {
        $task->file->result = $response->result;
        $task->file->save();
        $task->result = $response;
        $task->status = Task::SUCCESS;
        return $task->save();
    }

    /**
     * @param object $response
     * @param int $fileId
     * @return Task
     */
    public function prepareByRetryId(object $response, int $fileId): Task
    {
        $task = Task::where('remote_id', $response->retry_id)->first();
        if (!$task) {
            $task = new Task();
            $task->remote_id = $response->retry_id;
        }
        $task->result = $response;
        $task->status = Task::RECEIVED;
        $task->file_id = $fileId;
        $task->save();
        return $task;
    }
}
