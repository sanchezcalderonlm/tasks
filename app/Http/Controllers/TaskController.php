<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAssign;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function createTask(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date|date_format:Y-m-d',
            'status' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['open', 'in_progress', 'complete'])) {
                        $fail("The $attribute is invalid.");
                    }
                },
            ],
            'completed' => 'required|boolean',
            'assignments' => 'required|array',
            'assignments.*' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $this->taskService->createTask($request);
            DB::commit();
            return response()->json([
                'message' => 'task created'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function updateTask(Task $task, Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date|date_format:Y-m-d',
            'status' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['open', 'in_progress', 'complete'])) {
                        $fail("The $attribute is invalid.");
                    }
                },
            ],
            'completed' => 'required|boolean',
            'assignments' => 'required|array',
            'assignments.*' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $userId = auth('api')->user()->id;

        $userIds = TaskAssign::where('task_id', $task->id)
            ->get()
            ->pluck('user_id')
            ->toArray();

        $userIds = array_merge([$task->user_id], $userIds);
        if (!in_array($userId, $userIds)) {
            return response()->json([
                'message' => 'You cannot modify this task because it is not assigned to you or you are not the owner.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $this->taskService->updateTask($task->id, $request);
            DB::commit();
            return response()->json([
                'message' => 'task updated'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function deleteTask(Task $task)
    {
        try {
            DB::beginTransaction();
            TaskAssign::where('task_id', $task->id)
                ->delete();
            $task->delete();
            DB::commit();
            return response()->json([
                'message' => 'task deleted'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
