<?php

namespace App\Services;

use App\Respositories\TaskRespository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskService
{
    public TaskRespository $taskRespository;

    public function __construct(TaskRespository $taskRespository)
    {
        $this->taskRespository = $taskRespository;
    }

    public function createTask(Request $request): void
    {
        $userId = auth('api')->user()->id;
        $this->taskRespository->create($userId,
            $request['title'],
            $request['description'],
            Carbon::createFromFormat('Y-m-d', $request['due_date']),
            $request['status'],
            $request['completed'],
            $request['assignments'],
        );
    }

    public function updateTask(int $taskId, Request $request): void
    {
        $this->taskRespository->update($taskId,
            $request['title'],
            $request['description'],
            Carbon::createFromFormat('Y-m-d', $request['due_date']),
            $request['status'],
            $request['completed'],
            $request['assignments'],
        );
    }

}
