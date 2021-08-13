<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cs_count = DB::table('customers')->count();
        $order_count = DB::table('order_venues')->count() + DB::table('order_products')->count();
        $venue_set = DB::table('order_venues')->where('ov_status_order','=',3)
                        ->selectRaw('SUM(ov_sum_biaya) AS ov_sum_biaya')
                        ->groupByRaw('MONTH(updated_at)')
                        ->get();
        foreach ($venue_set as $item) {
            $item->ov_sum_biaya /= 1000;
        }
        $product_set = DB::table('order_products')->where('op_status_order','=',3)
                        ->selectRaw('SUM(op_sum_biaya) AS op_sum_biaya')
                        ->groupByRaw('MONTH(updated_at)')
                        ->get();
        foreach ($product_set as $item) {
            $item->op_sum_biaya /= 1000;
        }
        $data = [
            'vst' => 350897,
            'cs' => $cs_count,
            'ord' => $order_count,
            'performa' => '49,65%',
            'venue_set' => $venue_set->all(),
            'product_set' => $product_set->all()
        ];
        // dd($data);
        return view('dashboard', compact('data'));
    }
}
