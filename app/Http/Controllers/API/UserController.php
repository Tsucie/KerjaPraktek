<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ResponseMessage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @author Rizky A
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::orderBy('name', 'ASC')->get();
        return view('users.index', compact('data'));
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
            'password' => 'required|string'
        ]);

        try
        {
            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];
            User::query()->create($userData);

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Ditambahkan';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
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
            
            $data = User::query()->where('id','=',$id)->get();
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
                'name' => $request->has('nama') ? $request->nama : null,
                'email' => $request->has('email') ? $request->email : null
            ];
            User::query()->where('id','=',$id)->update($updatedData);

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

            User::query()->where('id','=',$id)->delete();

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
