<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Validator;
use League\Config\Exception\ValidationException; // import Task model for db querys

class TaskController extends Controller
{
    /**
     * Display a listing of tasks linked to the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $id = $request->input('user')->id;
        $tasks = Task::where('user_id', $id)->get();
        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Create a new task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) // uses the Request class as an argument

    {
        $data = $request->all();


        // this is the default validation method in laravel
        $validator = Validator::make(
            $data,
            [
                'title' => 'required|min:5|max:255',
                'description' => 'nullable|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $task = new Task;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = $request->input('user')->id;
        $task->save();

        return response()->json(['message' => 'Todo created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $userId = $request->input('user')->id;

        if ($task->user_id != $userId) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // search for the task, if dont find it will throw exception
        $task = Task::findOrFail($id);
        $userId = $request->input('user')->id;

        if ($task->user_id != $userId) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }

        $data = $request->all();

        // this is the default validation method in laravel
        $validator = Validator::make(
            $data,
            [
                'title' => 'required|min:5|max:255',
                'description' => 'nullable|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // here array filter is used to remove empty field
        // in case description came empty
        $task->update(array_filter($data));

        return response()->json(['message' => 'Todo was updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $userId = $request->input('user')->id;

        if ($task->user_id != $userId) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }


        $task->delete();

        return response()->json(['message' => 'Todo was deleted successfully.']);
    }
}