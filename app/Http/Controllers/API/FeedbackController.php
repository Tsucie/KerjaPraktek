<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ResponseMessage;
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
        $resmsg = new ResponseMessage();
        $request->validate([
            'fb_ov_id' => 'numeric',
            'fb_op_id' => 'numeric',
            'fb_text' => 'required|string',
            'fb_order_status' => 'required|numeric',
            'fb_rating' => 'required|numeric'
        ]);
        try {
            if (!$request->has('fb_ov_id') && !$request->has('fb_op_id')) throw new Exception("Data tidak valid");
            $fbData = [
                'fb_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'fb_ov_id' => $request->fb_ov_id ?? null,
                'fb_op_id' => $request->fb_op_id ?? null,
                'fb_text' => $request->fb_text,
                'fb_order_status' => $request->fb_order_status
            ];
            Feedback::query()->create($fbData);
            $resmsg->code = 1;
            $resmsg->message = "Feedback berhasil dibuat";
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }
        return response()->json($resmsg);
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

            $data = Feedback::query()->where('fb_id','=',$id)->with(['orderVenue','orderProduct'])->get();
            if ($data->count() == 0) throw new Exception("Tidak Ada Data", 0);
            return response()->json($data);
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
            return response()->json($resmsg);
        }
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
        $resmsg = new ResponseMessage();
        $request->validate([
            'fb_text' => 'required|string',
            'fb_order_status' => 'required|numeric',
            'fb_rating' => 'required|numeric'
        ]);
        try {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            $existFb = Feedback::query()->where('fb_id','=',$id)->get();
            if ($existFb->count() == 0) throw new Exception("Data tidak ditemukan",0);
            Feedback::query()->where('fb_id','=',$existFb['fb_id'])->update($request->all());
            $resmsg->code = 1;
            $resmsg->message = 'Feedback berhasil diubah';
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }
        return response()->json($resmsg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resmsg = new ResponseMessage();
        try {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            $existFb = Feedback::query()->where('fb_id','=',$id)->get();
            if ($existFb->count() == 0) throw new Exception("Data tidak ditemukan",0);
            Feedback::query()->where('fb_id','=',$existFb['fb_id'])->delete();
            $resmsg->code = 1;
            $resmsg->message = 'Feedback berhasil dihapus';
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }
        return response()->json($resmsg);
    }
}
