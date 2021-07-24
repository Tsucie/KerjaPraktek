<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\OrderVenue;
use App\Models\ResponseMessage;
use App\Models\Venue;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @author Rizky A
 */
class OrderVenueController extends Controller
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
            $ele->gst_rencana_pemakaian = date_format(
                DateTime::createFromFormat('Y-m-d', $ele->gst_rencana_pemakaian),
                'l, d F Y' // for references see: https://www.php.net/manual/en/datetime.format.php
            );
        }
        return view('order.venue', compact('data'));
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
        $data = $this->selectListPartially($request->pageLength ?? 15, $request->cst_id, $request->page ?? 1);
        return response()->json($data);
    }

    /**
     * Get a list of the resource from database.
     *
     * @return \Illuminate\Support\Collection
     */
    private function selectListPartially($pageLength = 15, $cs_id = null, $page = null)
    {
        return DB::table('order_venues')
                     ->join('guests', 'order_venues.ov_gst_id', '=', 'guests.gst_id')
                        ->select('order_venues.*', 'guests.*')
                            ->orderBy('guests.gst_rencana_pemakaian')
                            ->orderBy('order_venues.ov_status_order')
                            ->where('ov_cst_id', $cs_id == null ? '<>' : '=', $cs_id)
                                ->paginate($pageLength, ['*'], 'page', $page);
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
            'cst_id' => 'required|numeric',
            'ov_vnu_id' => 'required|integer',
            'ov_vnu_nama' => 'required',
            'ov_no_telp' => 'required',
            'gst_nama' => 'required',
            'gst_alamat' => 'required',
            'gst_no_telp' => 'required',
            'gst_rencana_pemakaian' => 'required',
            'gst_waktu_pemakaian' => 'required'
        ]);
        try
        {
            $guestData = [
                'gst_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'gst_nama' => $request->gst_nama,
                'gst_alamat' => $request->gst_alamat,
                'gst_no_telp' => $request->gst_no_telp,
                'gst_rencana_pemakaian' => $request->gst_rencana_pemakaian,
                'gst_waktu_pemakaian' => $request->gst_waktu_pemakaian,
                'gst_keperluan_pemakaian' => $request->gst_keperluan_pemakaian ?: 'Tidak di jelaskan'
            ];
    
            $venue = Venue::query()->where('vnu_id','=',$request->ov_vnu_id)->with('promo')->get();
            if ($venue->count() == 0) throw new Exception("Data gedung tidak ada",0);
            $day = date_format(DateTime::createFromFormat('Y-m-d',$request->gst_rencana_pemakaian),'l');
            // Assign hargaSewa with nested Tenary Opr
            $hargaSewa = ($day == 'Saturday' || $day == 'Sunday') ?
                            $venue[0]->vnu_harga : (
                                $venue[0]->promo == null ? $venue[0]->vnu_harga : $venue[0]->promo->prm_harga_promo
                            );
            $orderData = [
                'ov_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'ov_cst_id' => $request->cst_id,
                'ov_gst_id' => $guestData['gst_id'],
                'ov_vnu_id' => $venue[0]->vnu_id,
                'ov_vnu_nama' => $venue[0]->vnu_nama,
                'ov_no_telp' => $request->ov_no_telp,
                'ov_harga_sewa' => $hargaSewa,
                'ov_more_facilities' => $request->ov_more_facilities ?? null,
                'ov_sum_biaya' => $venue[0]->vnu_harga,
                'ov_down_payment' => 0.00,
                'ov_remaining_payment' => $venue[0]->vnu_harga,
                'ov_status_order' => 0, // 0: Dalam Proses
                'ov_contact_customer' => 0
            ];

            DB::beginTransaction();
            try
            {
                Guest::query()->create($guestData);
                OrderVenue::query()->create($orderData);

                DB::commit();
                $resmsg->code = 1;
                $resmsg->message = 'Order Berhasil Ditambahkan';
            }
            catch (Exception $ex)
            {
                DB::rollBack();
                throw $ex;
            }
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Ditambahkan';

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
        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $data = OrderVenue::query()->where('ov_id','=',$id)
                                ->with(['customer', 'guest', 'venue'])->get();
            
            if ($data->count() == 0) throw new Exception("Tidak Ada Data", 0);
            $data[0]->guest->gst_rencana_pemakaian = date_format(DateTime::createFromFormat('Y-m-d', $data[0]->guest->gst_rencana_pemakaian), 'l, d F Y');
            return response()->json($data);
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Tidak Ditemukan';

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
            'ov_no_telp' => 'required',
            'ov_biaya_lain' => 'numeric',
            'ov_fee_catering' => 'numeric',
            'ov_fee_pelaminan' => 'numeric',
            'ov_lain_lain' => 'numeric',
            'ov_sum_lain_lain' => 'numeric',
            'ov_sum_biaya' => 'numeric',
            'ov_down_payment' => 'numeric',
            'ov_remaining_payment' => 'required|numeric',
            'ov_status_order' => 'required|numeric',
            'ov_contact_customer' => 'required|numeric'
        ]);
        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            $existingOrder = OrderVenue::query()->where('ov_id','=',$id)->with('guest')->get();
            if ($existingOrder->count() == 0) throw new Exception("Data Order tidak ada", 0);
            $updateGuest = [
                'gst_nama' => $request->gst_nama ?? $existingOrder[0]->guest->gst_nama,
                'gst_alamat' => $request->gst_alamat ?? $existingOrder[0]->guest->gst_alamat,
                'gst_no_telp' => $request->gst_no_telp ?? $existingOrder[0]->guest->gst_no_telp,
                'gst_rencana_pemakaian' => $request->gst_rencana_pemakaian ?? $existingOrder[0]->guest->gst_rencana_pemakaian,
                'gst_waktu_pemakaian' => $request->gst_waktu_pemakaian ?? $existingOrder[0]->guest->gst_waktu_pemakaian,
                'gst_keperluan_pemakaian' => $request->gst_keperluan_pemakaian ?? $existingOrder[0]->guest->gst_keperluan_pemakaian
            ];
            $updateOrder = [
                'ov_no_telp' => $request->ov_no_telp,
                'ov_nama_catering' => $request->ov_nama_catering,
                'ov_biaya_lain' => $request->ov_biaya_lain,
                'ov_fee_catering' => $request->ov_fee_catering,
                'ov_fee_pelaminan' => $request->ov_fee_pelaminan,
                'ov_more_facilities' => $request->ov_more_facilities,
                'ov_lain_lain' => $request->ov_lain_lain,
                'ov_sum_lain_lain' => $request->ov_sum_lain_lain,
                'ov_sum_biaya' => $request->ov_sum_biaya,
                'ov_down_payment' => $request->ov_down_payment,
                'ov_remaining_payment' => $request->ov_remaining_payment,
                'ov_status_order' => $request->ov_status_order, // 0: Dalam Proses; 1: Terverifikasi; 2: Sudah Down Payment; 3: Selesai(Lunas); 4: Ditolak;
                'ov_contact_customer' => $request->ov_contact_customer
            ];
            DB::beginTransaction();
            try
            {
                Guest::query()->where('gst_id','=',$existingOrder[0]->ov_gst_id)->update($updateGuest);
                OrderVenue::query()->where('ov_id','=',$existingOrder[0]->ov_id)->update($updateOrder);

                DB::commit();
                $resmsg->code = 1;
                $resmsg->message = 'Order berhasil diubah';
            }
            catch (Exception $ex)
            {
                DB::rollBack();
                throw $ex;
            }
        }
        catch (Exception $ex)
        {
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resmsg = new ResponseMessage();

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $existingOrder = OrderVenue::query()->where('ov_id','=',$id)->get();
            if ($existingOrder->count() == 0) throw new Exception("Data Order tidak ada", 0);

            DB::beginTransaction();
            try
            {
                Guest::query()->where('gst_id','=',$existingOrder[0]->ov_gst_id)->delete();
                OrderVenue::query()->where('ov_id','=',$existingOrder[0]->ov_id)->delete();

                DB::commit();
                $resmsg->code = 1;
                $resmsg->message = 'Order berhasil dihapus';
            }
            catch (Exception $ex)
            {
                DB::rollBack();
                throw $ex;
            }
        }
        catch (Exception $ex)
        {
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
