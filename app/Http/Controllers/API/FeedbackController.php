<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ResponseMessage;
use DateTime;
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
                ->leftJoin('venues', 'feedbacks.fb_vnu_id', '=', 'venues.vnu_id')
                ->leftJoin('products', 'feedbacks.fb_pdct_id', '=', 'products.pdct_id')
                ->select('venues.*', 'products.*', 'feedbacks.*')
                    ->orderBy('feedbacks.created_at', 'desc')
                        ->paginate(5);
        
        foreach ($data as $ele) {
            $strArr = explode(' ',trim($ele->fb_text));
            $ele->fb_text = $strArr[0].' ...';
            $ele->created_at = date_format(DateTime::createFromFormat('Y-m-d H:i:s', $ele->created_at),'l, d F Y H:i:s');
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
            'fb_vnu_id' => 'numeric',
            'fb_pdct_id' => 'numeric',
            'fb_cst_nama' => 'required|string',
            'fb_cst_email' => 'required|string',
            'fb_text' => 'required|string',
            'fb_rating' => 'required|numeric'
        ]);
        try {
            if (!$request->has('fb_vnu_id') && !$request->has('fb_pdct_id')) throw new Exception("Data tidak valid");
            $fbData = [
                'fb_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'fb_vnu_id' => $request->fb_vnu_id ?? null,
                'fb_pdct_id' => $request->fb_pdct_id ?? null,
                'fb_cst_nama' => $request->fb_cst_nama,
                'fb_cst_email' => $request->fb_cst_email,
                'fb_text' => $request->fb_text,
                'fb_rating' => $request->fb_rating ?? 0
            ];
            Feedback::query()->create($fbData);
            $resmsg->code = 1;
            $resmsg->message = "Feedback berhasil dibuat";
        } catch (Exception $ex) {
            if ($ex->getCode() == 23000) {
                $resmsg->code = 0;
                $resmsg->message = "Feedback sudah ada";
            }
            else {
                // $resmsg->code = 0;
                // $resmsg->message = 'Feedback Gagal dibuat';

                #region Code Testing
                $resmsg->code = $ex->getCode();
                $resmsg->message = $ex->getMessage();
                #endregion
            }
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
            $column = '';
            if (preg_match("/[0-9]/", $id)) $column = 'fb_id';
            if (filter_var($id, FILTER_VALIDATE_EMAIL)) $column = 'fb_cst_email';

            $data = Feedback::query()->where($column,'=',$id)->get();
            if ($data->count() == 0) throw new Exception("Tidak Ada Data", 0);
            return response()->json($data);
        } catch (Exception $ex) {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Tidak Ada';

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
            'fb_cst_nama' => 'required|string',
            'fb_cst_email' => 'required|string',
            'fb_text' => 'required|string',
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
            // $resmsg->message = 'Data Gagal diubah';

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
