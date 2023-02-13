<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Validator;
use League\Config\Exception\ValidationException; // import Task model for db querys

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
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

        Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => 'Todo created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
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
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Todo was deleted successfully.']);
    }
}