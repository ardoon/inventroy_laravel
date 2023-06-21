<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
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
    public function index(Request $request)
    {
        $products = Product::query();

        $searched_product = $request->product ?? 'none';
        $searched_category = $request->category ?? 'all';
        if ($searched_product != 'none') {
            $products->where('title', 'like', '%' . $searched_product . '%');
        }
        if ($searched_category != 'all') {
            $category_ids = [];
            $category_ids[] = (int)$searched_category;

//          Add category's children and grandchildren too
            if ($searched_category != null) {
                $category_children = Category::where('parent_id', $searched_category)->get(['id']);
                if (count($category_children) > 0) {
                    foreach ($category_children as $child) {
                        $category_ids[] = $child->id;
                        $category_grandchildren = Category::where('parent_id', $child->id)->get(['id']);
                        if (count($category_grandchildren) > 0) {
                            foreach ($category_grandchildren as $grandchild) {
                                $category_ids[] = $grandchild->id;
                            }
                        }
                    }
                }
            }

       
                $products->whereHas('categories', function($q) use($category_ids) {
                    $q->whereIn('category_id', $category_ids);
                });


        }

        $products = $products->orderBy('id', 'desc')->paginate(10);

        $units = Unit::all();
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();

        return view('products', compact('products', 'units', 'categories'));
    }

    public function all()
    {
        $products = Product::get(['title']);
        return $products;
    }

    public function getCategories($id){
        $product = Product::where('id', $id)->with('categories')->first();
        return $product->categories;
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


        $product = Product::create([
            'title' => $request->title,
            'unit_id' => $request->product_unit,
            'unit_sub_id' => $request->product_unit_sub,
            'proportion' => $request->proportion,
        ]);

         $product->categories()->attach($request->categories);

         $unit = Unit::where('id', $request->product_unit)->first();

         $product->unit = $unit->title;

         $categoriesTitles = [];

         $product_categories = $product->categories()->get();

         foreach ($product_categories as $category){
             $categoriesTitles[] = $category->title;
         }

         $product->categoriesTitles = $categoriesTitles;

         return $product;
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
    public function bycategory($id)
    {
        if ($id == "all"){
            return Product::all();
        }

        $products = [];

        $prim_products = Category::where('id', $id)->first()->products()->get();

        foreach ($prim_products as $prim_product){
            $products[] = $prim_product;
        }

        $sub_cats = Category::where('parent_id', $id)->get();

        if (count($sub_cats) > 0){
            foreach ($sub_cats as $sub_cat){
                $sub_cat_products = $sub_cat->products()->get();
                foreach ($sub_cat_products as $sub_cat_product){
                    $products[] = $sub_cat_product;
                }
                $sub_sub_cats = Category::where('parent_id', $sub_cat->id)->get();
                if (count($sub_sub_cats) > 0){
                    foreach ($sub_sub_cats as $sub_sub_cat){
                        $sub_sub_cat_products = $sub_sub_cat->products()->get();
                        foreach ($sub_sub_cat_products as $sub_sub_cat_product){
                            $products[] = $sub_sub_cat_product;
                        }
                    }
                }
            }
        }

        function super_unique($array,$key)
        {
            $temp_array = [];
            foreach ($array as &$v) {
                if (!isset($temp_array[$v[$key]]))
                    $temp_array[$v[$key]] =& $v;
            }
            $array = array_values($temp_array);
            return $array;

        }

        return super_unique($products,'title');
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
        $product = Product::where('id', $id)->update([
            'title' => $request->title,
            'unit_id' => $request->product_unit,
            'unit_sub_id' => $request->product_unit_sub,
            'proportion' => $request->proportion,
        ]);

        $product = Product::where('id', $id)->first();

        $product->categories()->detach();

        $product->categories()->attach($request->categories);

        $unit = Unit::where('id', $request->product_unit)->first();

        $product->unit = $unit->title;

        $categoriesTitles = [];

        $product_categories = $product->categories()->get();

        foreach ($product_categories as $category){
            $categoriesTitles[] = $category->title;
        }

        $product->categoriesTitles = $categoriesTitles;

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::where('id', $id)->first();

        $product->categories()->detach();

        Product::destroy($id);

        return true;

    }
}
