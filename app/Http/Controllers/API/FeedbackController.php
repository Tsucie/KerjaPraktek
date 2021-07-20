<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('feedbacks')
                ->leftJoin('order_venues', 'feedbacks.fb_ov_id', '=', 'order_venues.ov_id')
                ->leftJoin('order_products', 'feedbacks.fb_op_id', '=', 'order_products.op_id')
                ->select('order_venues.*', 'order_products.*', 'feedbacks.*')
                ->selectSub("SELECT cst_name FROM dbsilungkang.customers WHERE cst_id=order_venues.ov_cst_id OR cst_id=order_products.op_cst_id",'cst_name')
                    ->orderBy('feedbacks.fb_order_status')
                        ->paginate(5);
        
        foreach ($data as $ele) {
            $strArr = explode(' ',trim($ele->fb_text));
            $ele->fb_text = $strArr[0].' '.$strArr[1].' '.$strArr[2].' '.$strArr[3].' ...';
        }
        return view('feedback.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
