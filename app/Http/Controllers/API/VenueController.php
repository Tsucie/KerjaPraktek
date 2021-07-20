<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ImageProcessor;
use App\Models\ResponseMessage;
use App\Models\Venue;
use App\Models\VenuePhoto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @author Rizky A
 */
class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->selectList();
        return view('venue.index', compact('data'));
    }

    /**
     * Return a list of the resource as json.
     *
     * @return \Illuminate\Http\Response::json
     */
    public function getList()
    {
        $data = $this->selectList();
        return response()->json($data);
    }

    /**
     * Get a list of the resource from database.
     *
     * @return array $datas
     */
    private function selectList()
    {
        $datas = DB::select(
            "SELECT vnu.*,".
                " (SELECT vp_filename FROM venue_photos WHERE vp_vnu_id=vnu_id LIMIT 1) AS vp_filename,".
                " (SELECT vp_photo FROM venue_photos WHERE vp_vnu_id=vnu_id LIMIT 1) AS vp_photo".
            " FROM dbsilungkang.venues vnu ORDER BY vnu.vnu_nama;"
        );

        foreach ($datas as $data)
        {
            $data->vp_photo = base64_encode($data->vp_photo);
        }

        return $datas;
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
            'images.*' => 'mimes:jpeg,png,jpg|max:5120',
            'nama' => 'required',
            'fasilitas' => 'required',
            'harga' => 'required|numeric',
            'tipe_waktu' => 'required|numeric',
            'status_tersedia' => 'required'
        ]);

        $venueData = [
            'vnu_id' => rand(0,2147483647),
            'vnu_nama' => $request->nama,
            'vnu_desc' => $request->has('desc') ? $request->desc : null,
            'vnu_fasilitas' => $request->fasilitas,
            'vnu_harga' => $request->harga,
            'vnu_tipe_waktu' => $request->tipe_waktu,
            'vnu_jam_pemakaian_siang' => $request->has('jam_siang') ? $request->jam_siang : null,
            'vnu_jam_pemakaian_malam' => $request->has('jam_malam') ? $request->jam_malam : null,
            'vnu_status_tersedia' => $request->status_tersedia,
            'created_by' => auth()->user()->name ?? 'system'
        ];

        $photoData = [];
        if ($request->has('imgLength') && $request->imgLength > 0)
        {
            for($i = 0; $i < $request->imgLength; $i++)
            {
                if ($request->hasFile('images'.$i))
                {
                    $file = $request->file('images'.$i);
                    $photoRequest = ImageProcessor::getImageThumbnail($file, $request->nama, 'vp_filename', 'vp_photo',$i);
                    array_push($photoData, $photoRequest);
                }
            }
        }

        DB::beginTransaction();
        try
        {
            Venue::query()->create($venueData);
            foreach ($photoData as $photo)
            {
                $photo->merge([
                    'vp_id' => rand(0,2147483647),
                    'vp_vnu_id' => $venueData['vnu_id']
                ]);
                VenuePhoto::query()->create($photo->all());
            }

            DB::commit();
            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Ditambahkan';
        }
        catch (Exception $ex)
        {
            DB::rollBack();
            if ($ex->getCode() == 22001)
            {
                $resmsg->code = 0;
                $resmsg->message = 'Ukuran foto tidak sesuai';
            }
            else
            {
                // $resmsg->code = 0;
                // $resmsg->message = 'Data Gagal Ditambahkan';

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

            $venue = Venue::query()->where('vnu_id','=',$id)->get();
            $photos = VenuePhoto::query()->where('vp_vnu_id','=',$id)->get();
            foreach ($photos as $photo)
            {
                $photo->vp_photo = base64_encode($photo->vp_photo);
            }

            if ($venue->count() == 0) throw new Exception("Tidak Ada Data", 0);

            return response()->json([
                'venue' => $venue,
                'photos' => $photos
            ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate([
            'id' => 'required|integer',
            'nama' => 'required',
            'fasilitas' => 'required',
            'harga' => 'required|numeric',
            'tipe_waktu' => 'required|numeric',
            'status_tersedia' => 'required'
        ]);

        $updateVenue = [
            'vnu_nama' => $request->nama,
            'vnu_desc' => $request->has('desc') ? $request->desc : null,
            'vnu_fasilitas' => $request->fasilitas,
            'vnu_harga' => $request->harga,
            'vnu_tipe_waktu' => $request->tipe_waktu,
            'vnu_jam_pemakaian_siang' => $request->has('jam_siang') ? $request->jam_siang : null,
            'vnu_jam_pemakaian_malam' => $request->has('jam_malam') ? $request->jam_malam : null,
            'vnu_status_tersedia' => $request->status_tersedia,
            'updated_by' => auth()->user()->name ?? 'system'
        ];

        try
        {
            $vnu_id = $request->id;
            Venue::query()->where('vnu_id','=',$vnu_id)->update($updateVenue);

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
     * Update the specified photo resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate([
            'vp_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama' => 'required',
            'index' => 'required|integer'
        ]);
        
        $photo = null;
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $photo = ImageProcessor::getImageThumbnail($image, $request->nama, 'vp_filename', 'vp_photo', $request->index);
        }

        try
        {
            $vp_id = $request->vp_id;
            VenuePhoto::query()->where('vp_id','=',$vp_id)->update($photo->all());

            $resmsg->code = 1;
            $resmsg->message = 'Photo Berhasil Diubah';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 0;
            // $resmsg->message = 'Photo Gagal Diubah';

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
    public function destroy(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate(['id' => 'required']);

        try 
        {
            $id = $request->id;
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            Venue::query()->where('vnu_id','=',$id)->delete();

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

    /**
     * Remove the specified photo resource from storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPhoto(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate(['vp_id' => 'required|integer']);

        try
        {
            $vp_id = $request->vp_id;
            VenuePhoto::query()->where('vp_id','=',$vp_id)->delete();

            $resmsg->code = 1;
            $resmsg->message = 'Photo Berhasil Dihapus';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 0;
            // $resmsg->message = 'Photo Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }

        return response()->json($resmsg);
    }
}
