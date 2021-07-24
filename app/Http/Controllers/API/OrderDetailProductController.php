<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\OrderDetailProduct;
use App\Models\ResponseMessage;
use Illuminate\Http\Request;

class OrderDetailProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate(['odp_op_id' => 'required|numeric']);
        try {
            $data = OrderDetailProduct::query()->where('odp_op_id','=',$request->odp_op_id)->with('product')->get();
            if ($data->count() == 0) throw new Exception("Tidak ada data", 0);
            
            return response()->json($data);
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Ditambahkan';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
            return response()->json($resmsg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resmsg = new ResponseMessage();
        try {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $data = OrderDetailProduct::query()->where('odp_id','=',$id)->with('product')->get();
            if ($data->count() == 0) throw new Exception("Tidak Ada Data", 0);
            return response()->json($data);
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Ditambahkan';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
            return response()->json($resmsg);
        }
    }
}
