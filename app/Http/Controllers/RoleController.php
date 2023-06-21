<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
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
        // درخواست تمام دسته بندی‌های والد از دیتابیس

        $roles = Role::with('parent')->whereNull('role_id')->orderBy('id', 'desc')->get();

        // درخواست تمام زیر دسته بندی‌ها از دیتابیس

        $subroles = Role::with('parent')->whereNotNull('role_id')->orderBy('id', 'desc')->get();

        // هدایت هر دو متغیر به یک ویوو

        return view('roles', compact('roles', 'subroles'));

    }

    public function allCategories()
    {
        return Role::all();
    }

    public function parents()
    {
        // درخواست تمام دسته بندی‌های والد از دیتابیس
        return Role::with('parent')->whereNull('role_id')->get();
    }

    public function children($parent)
    {
        return Role::with('parent')->where('role_id', $parent)->get();
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
        $roles = Role::create([
            'title' => $request->title,
            'role_id' => $request->role_id
        ]);

        return $roles;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Role::with('parent')->where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $has_parent = 0;
        $has_child_level_one = 0;
        $has_child_level_two = 0;
        $parent_is_child = 0;

        $category = Role::where('id', $id)->first();

        if ($category->parent_id != null){
            $has_parent = true;
        }

        if ($request->role_id != null){
            $new_parent = Role::where('id', $request->role_id)->first();

            if ($new_parent != null){
                if ($new_parent->role_id != null){
                    $parent_is_child = true;
                }
            }
        }

        $subcategories = Role::where('role_id', $category->id)->orderBy('id', 'desc')->get();

        if (count($subcategories) > 0){
            $has_child_level_one = true;
        }

        $flat_subcategories = [];

        foreach ($subcategories as $subcategory){

            $flat_subcategories[] = $subcategory->id;
            $temp_categories = Role::where('role_id', $subcategory->id)->orderBy('id', 'desc')->get();

            if (count($temp_categories) > 0){
                $has_child_level_two = true;
            }

            foreach ($temp_categories as $temp_category){
                $flat_subcategories[] = $temp_category->id;
            }

        }

        if ($has_parent == 0 && $has_child_level_two == true){
            return null;
        }

        if ($parent_is_child && $has_child_level_one){
            return null;
        }

        Role::where('id', $id)
            ->update(['title' => $request->title, 'role_id' => $request->role_id]);

        $updated_category = Role::where('id', $id)->first();

        $updated_category->sub_roles = $flat_subcategories;

        return $updated_category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);

        return true;
    }
}
