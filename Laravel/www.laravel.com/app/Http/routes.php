<?php

use App\Task;
use App\Baike;
use Illuminate\Http\Request;

/**
 * Display All Tasks
 */
Route::get('/', function () {
   $tasks = Task::orderBy('created_at', 'asc')->get();

   return view('tasks', [
       'tasks' => $tasks
   ]);
});

/**
 * Add A New Task
 */
Route::post('/task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name_zhcn' => 'required|max:255',
        'name_enus' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->name_zhcn = $request->name_zhcn;
    $task->name_enus = $request->name_enus;
    $task->save();

    return redirect('/');
});

/**
 * Delete An Existing Task
 */
Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();
    return redirect('/');
});

/**
 * Display All Tasks
 */
Route::get('baike', function () {
	return redirect('baike/1');
});

Route::get('baike/{page}', function ($page) {
	$page = (int) trim($page);
	$page = $page > 1 ? $page : 1;
    $baikes = Baike::orderBy('baike_id', 'asc')->skip($page * 10)->take(10)->get();

   return view('baikes', [
       'baikes' => $baikes
   ]);
});

Route::get('detail/{id}', function ($id) {
	$baike = Baike::where('baike_id', '=', $id)->get();

	return view('detail', [
		'baike' => $baike[0]
	]);
});
