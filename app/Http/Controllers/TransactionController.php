<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()) {
            $query = Transaction::query();
            return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <a href="'.route('dashboard.transaction.show', $item->id).'" class="px-4 py-2 mr-3 rounded bg-yellow-500 text-slate-700 ">
                        Show
                    </a>
                    <a href="'.route('dashboard.transaction.edit', $item->id).'" class="px-4 py-2 mr-3 rounded bg-gray-500 text-white ">
                        Edit
                    </a>
                    
                ';
            })
            ->editColumn('total_price', function($item){
                return 'Rp. '.number_format($item->total_price, 0, ',', '.');
            })  
            ->rawColumns(['action'])
            ->make();
        }
        return view('pages.dashboard.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {   
        if(request()->ajax()) {
            $query = TransactionItem::with(['product'])->where('transactions_id', $transaction->id);
            return DataTables::of($query)
            
            ->editColumn('product.price', function($item){
                return 'Rp. '.number_format($item->product->price, 0, ',', '.');
            })  
            ->rawColumns(['action'])
            ->make();
        }
        return view('pages.dashboard.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('pages.dashboard.transaction.edit', [
            'item' => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $transaction->update($data);
        return redirect()->route('dashboard.transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
