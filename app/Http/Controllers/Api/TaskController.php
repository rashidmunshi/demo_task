<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskRequest;
use App\Http\Resources\Api\TaskResource;
use App\Http\Resources\Api\UserTasksResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    function store(TaskRequest $request)
    {
        $task = Task::create($request->validated() + ['user_id' => auth()->user()->id]);
        return (new TaskResource($task))->additional(['message' => 'Task Added Successfully', 'status' => true]);
    }
    
    function list()
    {
        $tasks = User::authusertask()->with('tasks')->first();
        return $tasks
            ? (new UserTasksResource($tasks))->additional(['message' => 'Success', 'status' => true])
            : response()->json(['status' => false, 'message' => 'not found']);
    }
    
    function update(TaskRequest $request)
    {
        $task = Task::AuthUserTaskUpdate($request->task_id)->first();
        return $task
            ? (new TaskResource($task))->additional(['message' => 'Task Updated Successfully', 'status' => true])
            : response()->json(['status' => false, 'message' => 'not found']);
    }
    
    function delete()
    {
        $taskDeleted = Task::find(request()->task_id)->delete();
        return $taskDeleted
            ? response()->json(['status' => true, 'message' => 'Task Deleted Successfully'])
            : response()->json(['status' => false, 'message' => 'not found']);
    }
}    