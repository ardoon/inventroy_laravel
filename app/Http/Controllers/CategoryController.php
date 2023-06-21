<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

class CategoryController extends Controller
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

        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();

        // درخواست تمام زیر دسته بندی‌ها از دیتابیس

        $subcategories = Category::with('parent')->whereNotNull('parent_id')->orderBy('id', 'desc')->get();

        // هدایت هر دو متغیر به یک ویوو

        return view('categories', compact('categories', 'subcategories'));

    }

    public function allCategories(){
        return Category::all();
    }

    public function parents(){
        // درخواست تمام دسته بندی‌های والد از دیتابیس
        return Category::with('parent')->whereNull('parent_id')->get();
    }

    public function children($parent){
        return Category::with('parent')->where('parent_id', $parent)->get();
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

        $category = Category::create([
            'title' => $request->title,
            'parent_id' => $request->parent_id
        ]);

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::with('parent')->where('id', $id)->first();
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

        $has_parent = 0;
        $has_child_level_one = 0;
        $has_child_level_two = 0;
        $parent_is_child = 0;

        $category = Category::where('id', $id)->first();

        if ($category->parent_id != null){
            $has_parent = true;
        }

        if ($request->parent_id != null){
            $new_parent = Category::where('id', $request->parent_id)->first();

            if ($new_parent != null) {
                if ($new_parent->parent_id != null) {
                    $parent_is_child = true;
                }
            }
        }

        $subcategories = Category::where('parent_id', $category->id)->orderBy('id', 'desc')->get();

        if (count($subcategories) > 0){
            $has_child_level_one = true;
        }

        $flat_subcategories = [];

        foreach ($subcategories as $subcategory){

            $flat_subcategories[] = $subcategory->id;
            $temp_categories = Category::where('parent_id', $subcategory->id)->orderBy('id', 'desc')->get();

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

        Category::where('id', $id)
            ->update(['title' => $request->title, 'parent_id' => $request->parent_id]);

        $updated_category = Category::where('id', $id)->first();

        $updated_category->sub_categories = $flat_subcategories;

        return $updated_category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return true;
    }
}
