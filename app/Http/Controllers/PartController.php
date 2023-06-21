<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Unit;
use Illuminate\Http\Request;

class PartController extends Controller
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

        $categories = Part::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();

        // درخواست تمام زیر دسته بندی‌ها از دیتابیس

        $subcategories = Part::with('parent')->whereNotNull('parent_id')->orderBy('id', 'desc')->get();

        // هدایت هر دو متغیر به یک ویوو

        return view('parts', compact('categories', 'subcategories'));

    }

    public function allCategories()
    {
        return Part::with('parent')->get();
    }

    public function parents()
    {
        // درخواست تمام دسته بندی‌های والد از دیتابیس
        return Part::with('parent')->whereNull('parent_id')->get();
    }

    public function children($parent)
    {
        return Part::with('parent')->where('parent_id', $parent)->get();
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

        $category = Part::create([
            'title' => $request->title,
            'parent_id' => $request->parent_id
        ]);

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $part = Part::with('parent')->where('id', $id)->first();
        if ($part->parent != null && $part->parent->parent_id != null){
            $grandparent = Part::where('id', $part->parent->parent_id)->first()->parent_id;
        }
        $parents = [];
        if ($part->parent_id != null) {
            $parents['level1'] = $part->parent_id;
        }
        if ($part->parent != null && $part->parent->parent_id != null) {
            $parents['level2'] = $part->parent->parent_id;
        }
        if ($part->parent != null && $part->parent->parent_id != null && $grandparent != null) {
            $parents['level3'] = $grandparent;
        }
        $part->parents = $parents;
        return $part;
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

        $category = Part::where('id', $id)->first();

        if ($category->parent_id != null) {
            $has_parent = true;
        }

        if ($request->parent_id != null){
            $new_parent = Part::where('id', $request->parent_id)->first();
            if ($new_parent != null) {
                if ($new_parent->parent_id != null) {
                    $parent_is_child = true;
                }
            }
        }

        $subcategories = Part::where('parent_id', $category->id)->orderBy('id', 'desc')->get();

        if (count($subcategories) > 0) {
            $has_child_level_one = true;
        }

        $flat_subcategories = [];

        foreach ($subcategories as $subcategory) {

            $flat_subcategories[] = $subcategory->id;
            $temp_categories = Part::where('parent_id', $subcategory->id)->orderBy('id', 'desc')->get();

            if (count($temp_categories) > 0) {
                $has_child_level_two = true;
            }

            foreach ($temp_categories as $temp_category) {
                $flat_subcategories[] = $temp_category->id;
                $temp_subcategories = Part::where('parent_id', $temp_category->id)->orderBy('id', 'desc')->get();

                if (count($temp_subcategories) > 0) {
                    $has_child_level_three = true;
                }

                foreach ($temp_subcategories as $temp_subcategory){
                    $flat_subcategories[] = $temp_subcategory->id;
                }
            }

        }

//        if ($has_parent == 0 && $has_child_level_three == true) {
//            return null;
//        }
//
//        if ($parent_is_child && $has_child_level_one) {
//            return null;
//        }

        Part::where('id', $id)
            ->update(['title' => $request->title, 'parent_id' => $request->parent_id]);

        $updated_category = Part::where('id', $id)->first();

        $updated_category->sub_categories = $flat_subcategories;

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
        Part::destroy($id);

        return true;
    }
}
