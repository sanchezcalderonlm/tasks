<?php

namespace App\Respositories;

use App\Models\Task;
use App\Models\TaskAssign;

class TaskRespository
{
    public function create(int $userId,
                           string $title,
                           string $description,
                           \DateTime $due_date,
                            string $status,
                            bool $completed,
                            array $assignments
    ): void
    {
        $task = Task::create([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date,
            'status' => $status,
            'completed' => $completed,
        ]);

        foreach ($assignments as $userId) {
            TaskAssign::create([
                'task_id' => $task->id,
                'user_id' => $userId,
            ]);
        }
    }

    public function update(int $taskId,
                           string $title,
                           string $description,
                           \DateTime $due_date,
                           string $status,
                           bool $completed,
                           array $assignments
    ): void
    {
        Task::where('id', $taskId)
            ->update([
                'title' => $title,
                'description' => $description,
                'due_date' => $due_date,
                'status' => $status,
                'completed' => $completed,
            ]);

        $taskAssign = TaskAssign::where('task_id', $taskId)->get();
        $usersIds = $taskAssign->pluck('user_id')->toArray();
        foreach ($assignments as $userId) {
            if(!in_array($userId, $usersIds)){
                TaskAssign::create([
                    'task_id' => $taskId,
                    'user_id' => $userId,
                ]);
            }
        }
        $usersToDelete = array_diff($usersIds, $assignments);
        TaskAssign::where('task_id', $taskId)
            ->whereIn('user_id', $usersToDelete)
            ->delete();
    }
}
