<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        $tasks = Task::all();
        
        
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            }
            return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = new Task;

        return view('tasks.create', [
            'task' => $tasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();


        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $task = Task::findOrFail($id);
        

        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
        // return view('tasks.show', [
        //     'task' => $task,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
        $task = Task::findOrFail($id);

         if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
        // return view('tasks.edit', [
        //     'task' => $task,
        // ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

    
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        return redirect('/');
    }
}
