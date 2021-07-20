<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MoreFacility;
use App\Models\ResponseMessage;
use Exception;
use Illuminate\Http\Request;

/**
 * @author Rizky A
 */
class MoreFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = MoreFacility::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate([
            'mfc_nama' => 'required',
            'mfc_satuan' => 'required',
            'mfc_harga' => 'required'
        ]);

        try
        {
            $insertedData = $request->all();
            MoreFacility::query()->create($insertedData);

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Ditambahkan';
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $resmsg = new ResponseMessage();

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            
            $data = MoreFacility::query()->where('mfc_id','=',$id)->get();
            if ($data->count() == 0)  throw new Exception("Data Tidak Ditemukan", 0);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $resmsg = new ResponseMessage();
        $request->validate([
            'mfc_nama' => 'required',
            'mfc_satuan' => 'required',
            'mfc_harga' => 'required'
        ]);

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data tidak valid", 0);

            $updatedData = $request->all();
            MoreFacility::query()->where('mfc_id','=',$id)->update($updatedData);

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Diubah';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 0;
            // $resmsg->message = 'Data Gagal Diubah';

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $resmsg = new ResponseMessage();

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data tidak valid", 0);

            MoreFacility::query()->where('mfc_id','=',$id)->delete();

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Dihapus';
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
