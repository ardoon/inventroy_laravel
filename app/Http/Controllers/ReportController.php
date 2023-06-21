<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entry;
use App\Models\Output;
use App\Models\Part;
use App\Models\Product;
use App\Models\Store;
use App\Models\Worker;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class ReportController extends Controller
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
        return view('reports');
    }

    public function entries()
    {
        $date = verta()->format('%Y/%m/%d');
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();

        return view('reports-entries', compact('date', 'categories'));
    }

    public function outputs()
    {
        $date = verta()->format('%Y/%m/%d');
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        return view('reports-outputs', compact('date', 'categories'));
    }

    public function stocks()
    {
        $date = verta()->format('%Y/%m/%d');
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        return view('reports-stocks', compact('date', 'categories'));
    }

    public function records()
    {
        $date = verta()->format('%Y/%m/%d');
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        return view('reports-records', compact('date', 'categories'));
    }
    
    public function specials_show(Request $request)
    {
        $outputs = [];

        //        Change Date
        $date_start_arr = explode("/", $request->start_date);
        $date_start = Verta::getGregorian($date_start_arr[0], $date_start_arr[1], $date_start_arr[2]);
        $date_start = implode("-", $date_start);
        $date_end_arr = explode("/", $request->end_date);
        $date_end = Verta::getGregorian($date_end_arr[0], $date_end_arr[1], $date_end_arr[2]);
        $date_end = implode("-", $date_end);

        $query = Entry::query();
        
        $query->where('special', 1);

        $query->whereBetween('date', [$date_start, $date_end]);
        
        $outputs = $query->get();
        
        $date = verta()->format('%Y/%m/%d');

        return view('reports-specials-print', compact('outputs', 'date'));
    }

    public function entries_show(Request $request)
    {
        $entries = [];

        //        Change Date
        
        if($request->start_date != null){
            $date_start_arr = explode("/", $request->start_date);
            $date_start = Verta::getGregorian($date_start_arr[0], $date_start_arr[1], $date_start_arr[2]);
            $date_start = implode("-", $date_start);
        }
        
        $date_end_arr = explode("/", $request->end_date);
        $date_end = Verta::getGregorian($date_end_arr[0], $date_end_arr[1], $date_end_arr[2]);
        $date_end = implode("-", $date_end);

        $query = Entry::query();

        if($request->start_date != null){
            $query->whereBetween('date', [$date_start, $date_end]);
        }

        if ($request->key != null) {
            $count_filters = count($request->key);
        }
        if ($request->key != null && $count_filters != 0) {

            $code_values = [];
            $product_ids = [];
            $category_ids = [];
            $store_ids = [];
            $worker_ids = [];

            for ($i = 0; $i < $count_filters; $i++) {
                switch ($request->key[$i]) {
                    case "code":
                        $code_values[] = $request->value[$i];
                        break;
                    case "product":
                        $product = Product::where('title', $request->value[$i])->first(['id']);
                        $product_ids[] = $product->id;
                        break;
                    case "category":
                        $category = Category::where('title', $request->value[$i])->first(['id']);
                        $category_ids[] = $category->id;

//                      Add category's children and grandchildren too
                        if ($category != null){
                            $category_children = Category::where('parent_id', $category->id)->get(['id']);
                            if (count($category_children) > 0){
                                foreach ($category_children as $child){
                                    $category_ids[] = $child->id;
                                    $category_grandchildren = Category::where('parent_id', $child->id)->get(['id']);
                                    if (count($category_grandchildren) > 0){
                                        foreach ($category_grandchildren as $grandchild){
                                            $category_ids[] = $grandchild->id;
                                        }
                                    }
                                }
                            }
                        }

                        break;
                    case "store":
                        $store = Store::where('title', $request->value[$i])->first(['id']);
                        $store_ids[] = $store->id;
                        break;
                    case "worker":
                        $worker = Worker::where('title', $request->value[$i])->first(['id']);
                        $worker_ids[] = $worker->id;
                        break;
                }
            }
            if (count($code_values) > 0) {
                $query->whereIn('code', $code_values);
            }
            if (count($product_ids) > 0) {
                $query->whereIn('product_id', $product_ids);
            }
            if (count($category_ids) > 0) {
                // this will hold all products ids
                $category_products_ids = [];

//              this for loop will catch each category that user added to filter
                foreach ($category_ids as $category_id) {

//                  The query bellow will get all category's products
                    $category = Category::with('products')->where('id', $category_id)->first();

//                  If category has products belows code will add product ids to main ids holder to search in db
                    if (count($category->products) > 0) {
                        foreach ($category->products as $product) {
                            $category_products_ids[] = $product->id;
                        }
                    }
                }

//              This will catch pointed entries from database with specified product_id in the categories
                if (count($category_products_ids) > 0) {
                    $query->whereIn('product_id', $category_products_ids);
                } else {
                    $query->whereIn('product_id', [-1,-2]);
                }
            }
            if (count($store_ids) > 0) {
                $query->whereIn('store_id', $store_ids);
            }
            if (count($worker_ids) > 0) {
                $query->whereIn('worker_id', $worker_ids);
            }
        }
        $entries = $query->get();
        return view('reports-entries-show', compact('entries'));
    }

    public function outputs_show(Request $request)
    {
        $outputs = [];

        //        Change Date
        
        if($request->start_date != null){
            $date_start_arr = explode("/", $request->start_date);
            $date_start = Verta::getGregorian($date_start_arr[0], $date_start_arr[1], $date_start_arr[2]);
            $date_start = implode("-", $date_start);
        }
        
        $date_end_arr = explode("/", $request->end_date);
        $date_end = Verta::getGregorian($date_end_arr[0], $date_end_arr[1], $date_end_arr[2]);
        $date_end = implode("-", $date_end);

        $query = Output::query();

        if($request->start_date != null){
            $query->whereBetween('date', [$date_start, $date_end]);
        }
        
        if ($request->key != null) {
            $count_filters = count($request->key);
        }
        if ($request->key != null && $count_filters != 0) {

            $code_values = [];
            $product_ids = [];
            $category_ids = [];
            $parts_ids = [];
            $store_ids = [];
            $worker_ids = [];

            for ($i = 0; $i < $count_filters; $i++) {
                switch ($request->key[$i]) {
                    case "code":
                        $code_values[] = $request->value[$i];
                        break;
                    case "product":
                        $product = Product::where('title', $request->value[$i])->first(['id']);
                        $product_ids[] = $product->id;
                        break;
                    case "category":
                        $category = Category::where('title', $request->value[$i])->first(['id']);
                        $category_ids[] = $category->id;

//                      Add category's children and grandchildren too
                        if ($category != null){
                            $category_children = Category::where('parent_id', $category->id)->get(['id']);
                            if (count($category_children) > 0){
                                foreach ($category_children as $child){
                                    $category_ids[] = $child->id;
                                    $category_grandchildren = Category::where('parent_id', $child->id)->get(['id']);
                                    if (count($category_grandchildren) > 0){
                                        foreach ($category_grandchildren as $grandchild){
                                            $category_ids[] = $grandchild->id;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    case "store":
                        $store = Store::where('title', $request->value[$i])->first(['id']);
                        $store_ids[] = $store->id;
                        break;
                    case "part":
                        $part = part::where('title', $request->value[$i])->first(['id']);
                        $parts_ids[] = $part->id;

                        if ($part != null){
                            $part_children = Part::where('parent_id', $part->id)->get(['id']);
                            if (count($part_children) > 0){
                                foreach ($part_children as $child){
                                    $parts_ids[] = $child->id;
                                    $part_grandchildren = Part::where('parent_id', $child->id)->get(['id']);
                                    if (count($part_grandchildren) > 0){
                                        foreach ($part_grandchildren as $grandchild){
                                            $parts_ids[] = $grandchild->id;
                                            $part_lastchildren = Part::where('parent_id', $grandchild->id)->get(['id']);
                                            if (count($part_lastchildren) > 0){
                                                foreach ($part_lastchildren as $lastchild){
                                                    $parts_ids[] = $lastchild->id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        break;
                    case "worker":
                        $worker = Worker::where('title', $request->value[$i])->first(['id']);
                        $worker_ids[] = $worker->id;
                        break;
                }
            }
            if (count($code_values) > 0) {
                $query->whereIn('code', $code_values);
            }
            if (count($product_ids) > 0) {
                $query->whereIn('product_id', $product_ids);
            }
            if (count($category_ids) > 0) {
                // this will hold all products ids
                $category_products_ids = [];

//              this for loop will catch each category that user added to filter
                foreach ($category_ids as $category_id) {

//                  The query bellow will get all category's products
                    $category = Category::with('products')->where('id', $category_id)->first();

//                  If category has products belows code will add product ids to main ids holder to search in db
                    if (count($category->products) > 0) {
                        foreach ($category->products as $product) {
                            $category_products_ids[] = $product->id;
                        }
                    }
                }

//              This will catch pointed entries from database with specified product_id in the categories
                if (count($category_products_ids) > 0) {
                    $query->whereIn('product_id', $category_products_ids);
                } else {
                    $query->whereIn('product_id', [-1,-2]);
                }
            }
            if (count($parts_ids) > 0) {
                $query->whereIn('part_id', $parts_ids);
            }
            if (count($store_ids) > 0) {
                $query->whereIn('store_id', $store_ids);
            }
            if (count($worker_ids) > 0) {
                $query->whereIn('worker_id', $worker_ids);
            }
        }

        $outputs = $query->get();

        return view('reports-outputs-show', compact('outputs'));
    }

    public function stocks_show(Request $request)
    {
        $stocks = [];

        $query = Product::query();

        if ($request->key != null) {
            $count_filters = count($request->key);
        }
        if ($request->key != null && $count_filters != 0) {

            $product_ids = [];
            $category_ids = [];
            $store_ids = [];

            for ($i = 0; $i < $count_filters; $i++) {
                switch ($request->key[$i]) {
                    case "product":
                        $product = Product::where('title', $request->value[$i])->first(['id']);
                        $product_ids[] = $product->id;
                        break;
                    case "category":
                        $category = Category::where('title', $request->value[$i])->first(['id']);
                        $category_ids[] = $category->id;
                        break;
                    case "store":
                        $store = Store::where('title', $request->value[$i])->first(['id']);
                        $store_ids[] = $store->id;
                        break;
                }
            }
            if (count($product_ids) > 0) {
                $query->whereIn('id', $product_ids);
            }
            if (count($category_ids) > 0) {
                // this will hold all products ids
                $category_products_ids = [];

//              this for loop will catch each category that user added to filter
                foreach ($category_ids as $category_id) {

//                  The query bellow will get all category's products
                    $category = Category::with('products')->where('id', $category_id)->first();

//                  If category has products belows code will add product ids to main ids holder to search in db
                    if (count($category->products) > 0) {
                        foreach ($category->products as $product) {
                            $category_products_ids[] = $product->id;
                        }
                    }
                }

//              This will catch pointed entries from database with specified product_id in the categories
                if (count($category_products_ids) > 0) {
                    $query->whereIn('id', $category_products_ids);
                }
            }
            if (count($store_ids) > 0) {
                $query->whereIn('store_id', $store_ids);
            }
        }

        $stocks = $query->get();
        return view('reports-stocks-show', compact('stocks'));
    }

    public function records_show(Request $request)
    {
        $records = [];

        //        Change Date
        
        if($request->start_date != null){
            $date_start_arr = explode("/", $request->start_date);
            $date_start = Verta::getGregorian($date_start_arr[0], $date_start_arr[1], $date_start_arr[2]);
            $date_start = implode("-", $date_start);
        }
        
        $date_end_arr = explode("/", $request->end_date);
        $date_end = Verta::getGregorian($date_end_arr[0], $date_end_arr[1], $date_end_arr[2]);
        $date_end = implode("-", $date_end);

        $product = Product::where('title', $request->value[0])->first();
        $product_id = $product->id;
if($request->start_date != null){
        $entries = Entry::where('product_id', $product_id)->whereBetween('date', [$date_start, $date_end])->get();
        $outputs = Output::where('product_id', $product_id)->whereBetween('date', [$date_start, $date_end])->get();
} else {
        $entries = Entry::where('product_id', $product_id)->get();
        $outputs = Output::where('product_id', $product_id)->get();
}
        $records = $entries->merge($outputs)->sortBy('created_at');

        return view('reports-records-show', compact('records', 'product'));
    }

    public function entries_print(Request $request)
    {
        $items = explode(",", $request->items);
        $columns = explode(",", $request->columns);

        foreach ($columns as $column) {
            if ($column == 'value')
                $columns[] = 'unit_id';
            if ($column == 'invoice_id')
                $columns[] = 'code';
        }

        $entries = Entry::whereIn('id', $items)->get($columns);

        $column_titles = [];
        foreach ($columns as $column) {
            switch ($column) {
                case 'product_id':
                    $column_titles[] = 'نام کالا';
                    break;
                case 'date':
                    $column_titles[] = 'تاریخ ورود';
                    break;
                case 'worker_id':
                    $column_titles[] = 'وارد کننده';
                    break;
                case 'value':
                    $column_titles[] = 'مقدار';
                    break;
                case 'price':
                    $column_titles[] = 'قیمت (ریال)';
                    break;
                case 'store_id':
                    $column_titles[] = 'انبار';
                    break;
                case 'invoice_id':
                    $column_titles[] = 'شماره رسید';
                    break;
            }
        }

        return view('reports-entries-print', compact('entries', 'column_titles'));
    }
    
    public function outputs_print(Request $request)
    {
        $items = explode(",", $request->items);
        $columns = explode(",", $request->columns);

        foreach ($columns as $column) {
            if ($column == 'value')
                $columns[] = 'unit_id';
            if ($column == 'invoice_id')
                $columns[] = 'code';
        }

        $entries = Output::whereIn('id', $items)->get($columns);

        $column_titles = [];
        foreach ($columns as $column) {
            switch ($column) {
                case 'product_id':
                    $column_titles[] = 'نام کالا';
                    break;
                case 'date':
                    $column_titles[] = 'تاریخ ورود';
                    break;
                case 'worker_id':
                    $column_titles[] = 'خارج کننده';
                    break;
                case 'value':
                    $column_titles[] = 'مقدار';
                    break;
                case 'price':
                    $column_titles[] = 'قیمت (ریال)';
                    break;
                case 'store_id':
                    $column_titles[] = 'انبار';
                    break;
                case 'invoice_id':
                    $column_titles[] = 'شماره رسید';
                    break;
            }
        }

        return view('reports-entries-print', compact('entries', 'column_titles'));
    }

}
