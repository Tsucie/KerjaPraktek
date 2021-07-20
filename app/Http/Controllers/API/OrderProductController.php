<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->selectListPartially(10);
        foreach ($data as $ele) {
            $ele->op_tanggal_order = date_format(
                DateTime::createFromFormat('Y-m-d H:i:s', $ele->op_tanggal_order),
                DateTime::RSS
            );
        }
        return view('order.product', compact('data'));
    }

    /**
     * Return a list of the resource as json.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response::json
     */
    public function getList(Request $request)
    {
        $request->validate(['cst_id' => 'required|numeric']);
        $data = $this->selectListPartially(15, $request->cst_id);
        return response()->json($data);
    }

    /**
     * Get a list of the resource from database.
     *
     * @return \Illuminate\Support\Collection
     */
    private function selectListPartially($pageLength = 15, $cs_id = null)
    {
        return DB::table('order_products')
                     ->join('customers', 'order_products.op_cst_id', '=', 'customers.cst_id')
                        ->select('order_products.*', 'customers.*')
                        ->selectSub(
                            "SELECT pdct_nama FROM dbsilungkang.products WHERE pdct_id=(
                                SELECT odp_pdct_id FROM dbsilungkang.order_detail_products
                                WHERE odp_op_id=order_products.op_id
                            )",
                            'pdct_nama')
                            ->orderBy('order_products.op_status_order')
                            ->orderBy('order_products.op_tanggal_order')
                            ->where('op_cst_id', $cs_id == null ? '<>' : '=', $cs_id)
                                ->paginate($pageLength);
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
