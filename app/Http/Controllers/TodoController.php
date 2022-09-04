<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    //public constructor
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    // index get all
    public function index(){

        $todos = Todo::all();

        return response()->json([
            'status' => 'success',
            'todos' => $todos,
        ]);

    }

    // store: create a todo
    public function store(Request $request){

        // validate the inputs
        $request->validate([
            'title' => 'required | string | max:255',
            'description' => 'required | string | max:255'
        ]);

        // Create and insert the todo
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully',
            'todo' => $todo,
        ]);
    }

    // show: get a specific todo
    public function show($id){

        $todo = Todo::find($id);

        return response()->json([
            'status' => 'success',
            'todo' => $todo,
        ]);
    }

    // Update fucntion
    public function update(Request $request, $id){

        // validate inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Fetch the todo
        $todo = Todo::find($id);

        // Updated that todo
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        // Return a response to user
        return response()->json([
            'status' => 'success',
            'message' => 'Todo with id : ' . $id . ' successfully updated',
            'updatedtodo' => $todo,
        ]);

    }


    //Delete todo
    public function destroy($id){

        // Todo::destroy($id);

        // Fetch that todo with the given id
        $todo = Todo::find($id);
        $todo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo with id : ' . $id . ' successfully deleted',
        ]);
    } 


}
