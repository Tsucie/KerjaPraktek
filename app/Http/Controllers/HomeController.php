<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use DateTime;

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

        $chartLength = 6;
        // Chart Valuasi Order
        $venue_set = DB::table('order_venues')->where('ov_status_order','=',2)
                        ->selectRaw('SUM(ov_sum_biaya) AS ov_sum_biaya, MONTH(updated_at) AS month')
                        ->groupByRaw('MONTH(updated_at)')
                        ->orderByRaw('ANY_VALUE(updated_at) ASC')
                        ->get();
        $label_venue = [];
        $data_venue = [];
        $venue_last_month = 0;
        foreach ($venue_set as $item) {
            $item->ov_sum_biaya /= 1000;
            array_push($label_venue, date_format(DateTime::createFromFormat('m', $item->month), 'M'));
            array_push($data_venue, floatval($item->ov_sum_biaya));
            $venue_last_month = $item->month;
        }
        $venue_length = count($label_venue);
        if ($venue_length < $chartLength) {
            for ($i = $venue_length; $i < $chartLength; $i++) {
                $venue_last_month++;
                array_push($label_venue, date_format(DateTime::createFromFormat('m', $venue_last_month), 'M'));
                array_push($data_venue, floatval(0));
            }
        }
        if ($venue_length > $chartLength) {
            $label_venue = array_reverse($label_venue);
            $data_venue = array_reverse($data_venue);
            for ($i=0; $i < ($venue_length - $chartLength); $i++) { 
                array_pop($label_venue);
                array_pop($data_venue);
            }
            $label_venue = array_reverse($label_venue);
            $data_venue = array_reverse($data_venue);
        }

        $product_set = DB::table('order_products')->where('op_status_order','=',3)
                        ->selectRaw('SUM(op_sum_biaya) AS op_sum_biaya, MONTH(updated_at) AS month')
                        ->groupByRaw('MONTH(updated_at)')
                        ->orderByRaw('ANY_VALUE(updated_at) ASC')
                        ->get();
        $label_product = [];
        $data_product = [];
        $product_last_month = 0;
        foreach ($product_set as $item) {
            $item->op_sum_biaya /= 1000;
            array_push($label_product, date_format(DateTime::createFromFormat('m', $item->month), 'M'));
            array_push($data_product, floatval($item->op_sum_biaya));
            $product_last_month = $item->month;
        }
        $product_length = count($label_product);
        if ($product_length < $chartLength) {
            for ($i = $product_length; $i < $chartLength; $i++) {
                $product_last_month++;
                array_push($label_product, date_format(DateTime::createFromFormat('m', $product_last_month), 'M'));
                array_push($data_product, floatval(0));
            }
        }
        if ($product_length > $chartLength) {
            $label_product = array_reverse($label_product);
            $data_product = array_reverse($data_product);
            for ($i=0; $i < ($product_length - $chartLength); $i++) { 
                array_pop($label_product);
                array_pop($data_product);
            }
            $label_product = array_reverse($label_product);
            $data_product = array_reverse($data_product);
        }

        // Chart Total Order
        $vnu_ord_set = DB::table('order_venues')
                        ->selectRaw('count(*) AS ord_val, MONTH(updated_at) AS month')
                        ->groupByRaw('MONTH(updated_at)')
                        ->orderByRaw('ANY_VALUE(updated_at) ASC')
                        ->get();
        $label_vnu_ord = [];
        $data_vnu_ord = [];
        $vnu_ord_last_month = 0;
        foreach ($vnu_ord_set as $item) {
            array_push($label_vnu_ord, date_format(DateTime::createFromFormat('m', $item->month), 'M'));
            array_push($data_vnu_ord, intval($item->ord_val));
            $vnu_ord_last_month = $item->month;
        }
        $vnu_ord_length = count($label_vnu_ord);
        if ($vnu_ord_length < $chartLength) {
            for ($i = $vnu_ord_length; $i < $chartLength; $i++) {
                $vnu_ord_last_month++; 
                array_push($label_vnu_ord, date_format(DateTime::createFromFormat('m', $vnu_ord_last_month), 'M'));
                array_push($data_vnu_ord, 0);
            }
        }
        if ($vnu_ord_length > $chartLength) {
            $label_vnu_ord = array_reverse($label_vnu_ord);
            $data_vnu_ord = array_reverse($data_vnu_ord);
            for ($i=0; $i < ($vnu_ord_length - $chartLength); $i++) { 
                array_pop($label_vnu_ord);
                array_pop($data_vnu_ord);
            }
            $label_vnu_ord = array_reverse($label_vnu_ord);
            $data_vnu_ord = array_reverse($data_vnu_ord);
        }

        $pdct_ord_set = DB::table('order_products')
                        ->selectRaw('count(*) AS ord_val, MONTH(updated_at) AS month')
                        ->groupByRaw('MONTH(updated_at)')
                        ->orderByRaw('ANY_VALUE(updated_at) ASC')
                        ->get();
        $label_pdct_ord = [];
        $data_pdct_ord = [];
        $pdct_ord_last_month = 0;
        foreach ($pdct_ord_set as $item) {
            array_push($label_pdct_ord, date_format(DateTime::createFromFormat('m', $item->month), 'M'));
            array_push($data_pdct_ord, intval($item->ord_val));
            $pdct_ord_last_month = $item->month;
        }
        $pdct_ord_length = count($label_pdct_ord);
        if ($pdct_ord_length < $chartLength) {
            for ($i = $pdct_ord_length; $i < $chartLength; $i++) {
                $pdct_ord_last_month++;
                array_push($label_pdct_ord, date_format(DateTime::createFromFormat('m', $pdct_ord_last_month), 'M'));
                array_push($data_pdct_ord, 0);
            }
        }
        if ($pdct_ord_length > $chartLength) {
            $label_pdct_ord = array_reverse($label_pdct_ord);
            $data_pdct_ord = array_reverse($data_pdct_ord);
            for ($i=0; $i < ($pdct_ord_length - $chartLength); $i++) { 
                array_pop($label_pdct_ord);
                array_pop($data_pdct_ord);
            }
            $label_pdct_ord = array_reverse($label_pdct_ord);
            $data_pdct_ord = array_reverse($data_pdct_ord);
        }

        $data = [
            'cs' => $cs_count,
            'vnu_ord' => $vnu_ord,
            'pdct_ord' => $pdct_ord,
            'prm_count' => $prm_count,
            'venue_label' => $label_venue,
            'venue_set' => $data_venue,
            'product_label' => $label_product,
            'product_set' => $data_product,
            'vnu_ord_label' => $label_vnu_ord,
            'vnu_ord_set' => $data_vnu_ord,
            'pdct_ord_label' => $label_pdct_ord,
            'pdct_ord_set' => $data_pdct_ord
        ];
        return view('dashboard', compact('data'));
    }
}
