<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entry;
use App\Models\Output;
use App\Models\Part;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Store;
use App\Models\Unit;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hekmatinasser\Verta\Verta;

class OutputController extends Controller
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
        $date = verta()->format('%Y/%m/%d');
        $receipts = Receipt::with('outputs', 'worker')->orderBy('id', 'desc')->paginate(10);
        return view('outputs-all', compact('receipts', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = verta()->format('%Y/%m/%d');
        $workers = Worker::all();
        $products = Product::all();
        $stores = Store::all();
        $generated_no = Receipt::latest()->first()->code;
        $generated_no = str_pad(intval($generated_no) + 1, strlen($generated_no), '0', STR_PAD_LEFT);
        $units = Unit::all();
        $parts = Part::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $roles = Role::with('parent')->whereNull('role_id')->orderBy('id', 'desc')->get();
        return view('outputs', compact('date', 'categories', 'products', 'stores', 'roles','workers','units', 'parts', 'generated_no'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = Auth::user();

//        Change Date
        $date_arr = explode("/", $request->date);
        $date = Verta::getGregorian($date_arr[0],$date_arr[1],$date_arr[2]);
        $date = implode("-", $date);
        $receipt = Receipt::create([
            'code' => $request->code,
            'date' => $date,
            'worker_id' => $request->worker_id,
            'part_id' => $request->part_id,
            'user_id' => $user->id
        ]);

        $count_outputs = count($request->product_id);

        for ($i = 0; $i < $count_outputs; $i++){

            $product = Product::where('id', $request->product_id[$i])->first();

            if ($product->unit_id == $request->unit[$i]){
                $value = $request->value[$i];
            } else {
                $value = $request->value[$i] * $product->proportion;
            }

            $stock = $product->stock - $value;
            if ($stock >= 0){
                $product->update(['stock' => $stock]);
            } else {
                continue;
            }

            Output::create([
                'value' => $value,
                'date' => $date,
                'description' => $request->description[$i],
                'stock_of_time' => $stock,
                'product_id' => $request->product_id[$i],
                'worker_id' => $request->worker_id,
                'user_id' => $user->id,
                'receipt_id' => $receipt->id,
                'unit_id' => $request->unit[$i]
            ]);
            
            
            
        }

        return redirect()->route('outputs.index');
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
        $workers = Worker::all();
        $products = Product::all();
        $stores = Store::all();
        $parts = Part::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $units = Unit::all();
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $roles = Role::with('parent')->whereNull('role_id')->orderBy('id', 'desc')->get();
        $receipt = Receipt::with('outputs','outputs.product','outputs.part', 'worker')->where('id', $id)->first();
        $date = Verta::createTimestamp($receipt->date);
        if ($date->month <10)
            $date->month = "0" . $date->month;

        if ($date->day <10)
            $date->day = "0" . $date->day;

        $date = str_replace("-", "/", $date);
        $date = substr($date, 0, 10);
        return view('outputs-edit', compact('receipt','date', 'parts', 'categories', 'products', 'stores', 'roles','workers','units'));
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
    
        $user = Auth::user();

//        Change Date
        $date_arr = explode("/", $request->date);
        $date = Verta::getGregorian($date_arr[0],$date_arr[1],$date_arr[2]);
        $date = implode("-", $date);

        $receipt = Receipt::where('id', $id)->first();

        $receipt->update([
            'code' => $request->code,
            'date' => $date,
            'worker_id' => $request->worker_id,
            'part_id' => $request->part_id,
            'user_id' => $user->id
        ]);

        $prev_outputs = Output::where('receipt_id', $receipt->id)->get();

        foreach ($prev_outputs as $prev_output){
            $product = Product::where('id', $prev_output->product_id)->first();
            $stock = $product->stock + $prev_output->value;
            $product->update(['stock' => $stock]);
        }

        Output::where('receipt_id', $receipt->id)->delete();


        $count_outputs = count($request->product_id);

        for ($i = 0; $i < $count_outputs; $i++){

            $product = Product::where('id', $request->product_id[$i])->first();
            $stock = $product->stock - $request->value[$i];
            $product->update(['stock' => $stock]);

            Output::create([
                'value' => $request->value[$i],
                'date' => $date,
                'description' => $request->description[$i],
                'stock_of_time' => $stock,
                'product_id' => $request->product_id[$i],
                'worker_id' => $request->worker_id,
                'user_id' => $user->id,
                'receipt_id' => $receipt->id,
                'unit_id' => $request->unit[$i],
            ]);

        }

        return redirect()->back();
    }

    public function print($id)
    {
        $receipt = Receipt::where('id', $id)->first();
        $outputs = Output::where('receipt_id', $receipt->id)->get();
        return view('outputs-print', compact('receipt','outputs'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $receipt = Receipt::where('id', $id)->first();

        $prev_outputs = Output::where('receipt_id', $receipt->id)->get();

        foreach ($prev_outputs as $prev_output){
            $product = Product::where('id', $prev_output->product_id)->first();
            $stock = $product->stock + $prev_output->value;
            $product->update(['stock' => $stock]);
        }

        Output::where('receipt_id', $receipt->id)->delete();
        $receipt->delete();


        return redirect()->route('outputs.index');
    }

}
