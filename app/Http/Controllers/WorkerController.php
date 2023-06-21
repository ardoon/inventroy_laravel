<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Role;
use App\Models\Worker;
use Illuminate\Http\Request;
use mysql_xdevapi\Warning;

class WorkerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::whereNull('role_id')->get();
        $workers = Worker::with('role')->orderBy('id', 'desc')->paginate(10);
        return view('workers', compact('workers', 'roles'));
    }

    public function all()
    {
        return Worker::get(['title']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_worker = Worker::create([
            'title' => $request->title,
            'code' => $request->code,
            'role_id' => $request->role_id,
        ]);

        $worker = Worker::with('role')->where('id', $new_worker->id)->first();

        return $worker;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function byrole($id)
    {
        if ($id == "all") {
            return Worker::all();
        }

        $workers = [];

        $prim_workers = Role::where('id', $id)->first()->workers()->get();

        foreach ($prim_workers as $prim_worker) {
            $workers[] = $prim_worker;
        }

        $sub_roles = Role::where('role_id', $id)->get();

        if (count($sub_roles) > 0) {
            foreach ($sub_roles as $sub_role) {
                $sub_role_workers = $sub_role->workers()->get();
                foreach ($sub_role_workers as $sub_role_worker) {
                    $workers[] = $sub_role_worker;
                }
                $sub_sub_roles = Role::where('role_id', $sub_role->id)->get();
                if (count($sub_sub_roles) > 0) {
                    foreach ($sub_sub_roles as $sub_sub_role) {
                        $sub_sub_role_workers = $sub_sub_role->workers()->get();
                        foreach ($sub_sub_role_workers as $sub_sub_role_worker) {
                            $workers[] = $sub_sub_role_worker;
                        }
                    }
                }
            }
        }

        function super_unique($array, $key)
        {
            $temp_array = [];
            foreach ($array as &$v) {
                if (!isset($temp_array[$v[$key]]))
                    $temp_array[$v[$key]] =& $v;
            }
            $array = array_values($temp_array);
            return $array;

        }

        return super_unique($workers, 'title');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Worker::where('id', $id)->update([
            'title' => $request->title,
            'code' => $request->code,
            'role_id' => $request->role_id,
        ]);

        return Worker::with('role')->where('id', $id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Worker::destroy($id);

        return true;
    }
}
