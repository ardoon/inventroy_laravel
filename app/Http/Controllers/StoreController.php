<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\User;
use Illuminate\Http\Request;

class StoreController extends Controller
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
        $stores = Store::with('users')->orderBy('id', 'desc')->get();
        $operators = User::where('role', 'operator')->get();
        return view('stores', compact('stores','operators'));
    }

    public function all(){
        return Store::get(['title']);
    }

    public function getUsers($id){
        $stores = Store::where('id', $id)->with('users')->first();
        return $stores->users;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $store = Store::create([
            'title' => $request->title,
        ]);

        if ($request->operators != null){
            $store->users()->attach($request->operators);
        }

        $new_store = Store::with('users')->where('id', $store->id)->first();

        $userNames = [];

        foreach ($new_store->users as $user){
            $userNames[] = $user->name;
        }

        $new_store->userNames = $userNames;

        return $new_store;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        if ($request->title != ""){
            $store = Store::where('id', $id)
                ->update(['title' => $request->title]);
        }

        $store = Store::where('id', $id)->first();

        $store->users()->detach();

        if ($request->operators != null){
            $store->users()->attach($request->operators);
        }

        $new_store = Store::with('users')->where('id', $store->id)->first();

        $userNames = [];

        foreach ($new_store->users as $user){
            $userNames[] = $user->name;
        }

        $new_store->userNames = $userNames;

        return $new_store;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::where('id', $id)->first()->users()->detach();

        Store::destroy($id);

        return true;
    }
}
