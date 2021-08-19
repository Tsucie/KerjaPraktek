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
        $vnu_ord = DB::table('order_venues')->count();
        $pdct_ord = DB::table('order_products')->count();
        $prm_count = DB::table('promos')->count();
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
            'cs' => $cs_count,
            'vnu_ord' => $vnu_ord,
            'pdct_ord' => $pdct_ord,
            'prm_count' => $prm_count,
            'venue_set' => $venue_set->all(),
            'product_set' => $product_set->all()
        ];
        return view('dashboard', compact('data'));
    }
}
