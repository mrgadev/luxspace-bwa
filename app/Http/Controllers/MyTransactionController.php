<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MyTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()) {
            $query = Transaction::with(['user' ])->where('users_id', Auth::user()->id);
            return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <a href="'.route('dashboard.my-transaction.show', $item->id).'" class="px-4 py-2 mr-3 rounded bg-yellow-500 text-slate-700 ">
                        Show
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
    public function show(Transaction $myTransaction)
    {
        if(request()->ajax()) {
            $query = TransactionItem::with(['product'])->where('transactions_id', $myTransaction->id);
            return DataTables::of($query)
            
            ->editColumn('product.price', function($item){
                return 'Rp. '.number_format($item->product->price, 0, ',', '.');
            })  
            ->rawColumns(['action'])
            ->make();
        }
        return view('pages.dashboard.transaction.show', [
            'transaction' => $myTransaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
