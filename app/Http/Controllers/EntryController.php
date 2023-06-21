<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entry;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\Unit;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hekmatinasser\Verta\Verta;
use Illuminate\Validation\Rules\In;

class EntryController extends Controller
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
        $invoices = Invoice::with('entries', 'worker')->orderBy('id', 'desc')->paginate(10);
        return view('entries-all', compact('invoices', 'date'));
    }

    public function codes(){
        $codes = Invoice::get(['code']);
        return $codes;
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
        $units = Unit::all();
        $generated_no = Invoice::latest()->first()->code;
        $generated_no =  str_pad(intval($generated_no) + 1, strlen($generated_no), '0', STR_PAD_LEFT);
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $roles = Role::with('parent')->whereNull('role_id')->orderBy('id', 'desc')->get();
        return view('entries', compact('date', 'categories', 'products', 'stores', 'roles','workers','units','generated_no'));
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

        $invoice = Invoice::create([
            'code' => $request->code,
            'date' => $date,
            'title' => $request->title,
            'worker_id' => $request->worker_id,
            'user_id' => $user->id,
            'store_id' => $request->store,
        ]);

        $count_products = count($request->product_id);

        for ($i = 0; $i < $count_products; $i++){

            $product = Product::where('id', $request->product_id[$i])->first();

            if ($product->unit_id == $request->unit[$i]){
                $value = $request->value[$i];
            } else {
                $value = $request->value[$i] * $product->proportion;
            }

            $stock = $product->stock + $value ;
            $product->update(['stock' => $stock]);
//dd($stock);
            Entry::create([
                'code' => $request->code,
                'value' => $value,
                'price' => $request->price[$i],
                'date' => $date,
                'description' => $request->description[$i],
                'stock_of_time' => $stock,
                'product_id' => $request->product_id[$i],
                'worker_id' => $request->worker_id,
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'unit_id' => $request->unit[$i],
                'special' => $request->special[$i]
            ]);



        }

        return redirect()->route('entries.index');
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
        $units = Unit::all();
        $categories = Category::with('parent')->whereNull('parent_id')->orderBy('id', 'desc')->get();
        $roles = Role::with('parent')->whereNull('role_id')->orderBy('id', 'desc')->get();
        $invoice = Invoice::with('entries', 'worker')->where('id', $id)->first();
        $date = Verta::createTimestamp($invoice->date);
        if ($date->month <10)
            $date->month = "0" . $date->month;

        if ($date->day <10)
            $date->day = "0" . $date->day;

        $date = str_replace("-", "/", $date);
        $date = substr($date, 0, 10);
        return view('entries-edit', compact('invoice','date', 'categories', 'products', 'stores', 'roles','workers','units'));
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

        $invoice = Invoice::where('id', $id)->first();

        $invoice->update([
            'code' => $request->code,
            'date' => $date,
            'title' => $request->title,
            'worker_id' => $request->worker_id,
            'store_id' => $request->store,
            'user_id' => $user->id
        ]);

        $prev_entries = Entry::where('invoice_id', $invoice->id)->get();

        foreach ($prev_entries as $prev_entry){
            $product = Product::where('id', $prev_entry->product_id)->first();
            $stock = $product->stock - $prev_entry->value;
            $product->update(['stock' => $stock]);
        }

        Entry::where('invoice_id', $invoice->id)->delete();

        $count_products = count($request->product_id);

        for ($i = 0; $i < $count_products; $i++){

            $product = Product::where('id', $request->product_id[$i])->first();
            $stock = $product->stock + $request->value[$i];
            $product->update(['stock' => $stock]);

            Entry::create([
                'code' => $request->code,
                'value' => $request->value[$i],
                'price' => $request->price[$i],
                'date' => $date,
                'description' => $request->description[$i],
                'stock_of_time' => $stock,
                'product_id' => $request->product_id[$i],
                'worker_id' => $request->worker_id,
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'unit_id' => $request->unit[$i],
                'special' => $request->special[$i]
            ]);

        }

        return redirect()->back();
    }

    public function print($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $entries = Entry::where('invoice_id', $invoice->id)->get();
        return view('entries-print', compact('invoice','entries'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::where('id', $id)->first();

        $prev_entries = Entry::where('invoice_id', $invoice->id)->get();

        foreach ($prev_entries as $prev_entry){
            $product = Product::where('id', $prev_entry->product_id)->first();
            $stock = $product->stock - $prev_entry->value;
            $product->update(['stock' => $stock]);
        }

        Entry::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();

        return redirect()->route('entries.index');
    }

}
