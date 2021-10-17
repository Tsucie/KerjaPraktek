<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ResponseMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @author Rizky A
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('customers')
                    ->select()
                    ->selectSub("SELECT count(*) FROM order_venues
                                    WHERE order_venues.ov_cst_id = customers.cst_id", "vnu_ord")
                    ->selectSub("SELECT count(*) FROM order_products
                                    WHERE order_products.op_cst_id = customers.cst_id", "pdct_ord")
                    ->orderBy('cst_name','asc')->paginate(10);
        return view('customers.index', compact('data'));
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
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required|string',
            'no_telp' => 'required'
        ]);

        try
        {
            if (preg_match('/[A-Za-z]/', $request->no_telp))
                throw new Exception("Nomor telpon tidak benar!", 0);
            $csData = [
                'cst_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'cst_name' => $request->nama,
                'cst_alamat' => $request->has('alamat') ? $request->alamat : null,
                'cst_no_telp' => $request->no_telp,
                'cst_email' => $request->email,
                'cst_password' => Hash::make($request->password)
            ];
            Customer::query()->create($csData);

            $resmsg->code = 1;
            $resmsg->message = 'Registrasi Berhasil';
        }
        catch (Exception $ex)
        {
           if ($ex->getCode() == "23000")
           {
                $resmsg->code = "0";
                $resmsg->message = "Email sudah digunakan!";
           }
           else
           {
                // $resmsg->code = 1;
                // $resmsg->message = 'Registrasi Gagal';

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

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            
            $data = Customer::query()->where('cst_id','=',$id)->get();
            if ($data->count() == 0)  throw new Exception("Data Tidak Ditemukan", 0);

            return response()->json($data);
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
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

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $updatedData = [
                'cst_name' => $request->has('nama') ? $request->nama : null,
                'cst_email' => $request->has('email') ? $request->email : null,
                'cst_alamat' => $request->has('alamat') ? $request->alamat : null,
                'cst_no_telp' => $request->has('no_telp') ? $request->no_telp : null
            ];
            Customer::query()->where('cst_id','=',$id)->update($updatedData);

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Diubah';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resmsg = new ResponseMessage();

        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data tidak valid", 0);

            Customer::query()->where('cst_id','=',$id)->delete();

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Dihapus';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
            // $resmsg->message = 'Data Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }

        return response()->json($resmsg);
    }
}
