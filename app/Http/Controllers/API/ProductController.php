<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\ImageProcessor;
use App\Models\Inventory;
use App\Models\ResponseMessage;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Promo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @author Rizky A
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->selectList();
        return view('product.index', compact('data'));
    }

    public function detail($id)
    {
        try {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $data = Product::query()->where('pdct_id','=',$id)
                    ->with(['inventories','promo','photos'])
                    ->get();

            return view('customers.productdetail', compact('data'));
        }
        catch (Exception $ex) {
            #region Code Testing
            $error = [
                'code' => $ex->getCode(), 
                'message' => $ex->getMessage()
            ];
            #endregion
            return view('layouts.errors.ErrorPage', compact('error'));
        }
    }

    /**
     * Return a list of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        $data = $this->selectList();
        return response()->json($data);
    }

    /**
     * Return a list of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelectList()
    {
        $data = Product::orderBy('pdct_nama')->get();
        return response()->json($data);
    }

    /**
     * Get a list of the resource from database.
     *
     * @return array $datas
     */
    private function selectList()
    {
        $datas = DB::table('products')
                    ->select()
                    ->selectSub("SELECT ivty_pdct_stock FROM inventories WHERE ivty_pdct_id=pdct_id", 'pdct_stock')
                    ->selectSub("SELECT pp_filename FROM product_photos WHERE pp_pdct_id=pdct_id LIMIT 1", 'pp_filename')
                    ->selectSub("SELECT pp_photo FROM product_photos WHERE pp_pdct_id=pdct_id LIMIT 1", 'pp_photo')
                    ->orderBy('pdct_nama')
                    ->get();
        foreach ($datas as $data)
        {
            $data->pp_photo = base64_encode($data->pp_photo);
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
            'kategori_id' => 'required',
            'kategori_nama' => 'required',
            'nama' => 'required',
            'harga' => 'required',
            'stock' => 'required'
        ]);

        $productData = [
            'pdct_id' => rand(0,2147483647),
            'pdct_kategori_id' => $request->kategori_id,
            'pdct_kategori_nama' => $request->kategori_nama,
            'pdct_kode' => "PD#".date('Ymd').rand(0,2147483647),
            'pdct_nama' => $request->nama,
            'pdct_desc' => $request->has('desc') ? $request->desc : null,
            'pdct_harga' => $request->harga,
            'created_by' => auth()->user()->name ?? 'system'
        ];

        $inventoryData = [
            'ivty_id' => rand(0,2147483647),
            'ivty_pdct_id' => $productData['pdct_id'],
            'ivty_pdct_nama' => $productData['pdct_nama'],
            'ivty_pdct_stock' => $request->stock,
            'ivty_cause' => 'Pembuatan Product oleh ' . $productData['created_by'] . ' pada tanggal ' . DateTime::Now()
        ];

        $photoData = [];
        if ($request->has('imgLength') && $request->imgLength > 0)
        {
            for ($i = 0; $i < $request->imgLength; $i++)
            { 
                if ($request->hasFile('images'.$i))
                {
                    $file = $request->file('images'.$i);
                    $photoRequest = ImageProcessor::getImageThumbnail($file, $productData['pdct_nama'], 'pp_filename', 'pp_photo',$i);
                    array_push($photoData, $photoRequest);
                }
            }
        }

        DB::beginTransaction();
        try
        {
            Product::query()->create($productData);
            Inventory::query()->create($inventoryData);
            foreach ($photoData as $photo)
            {
                $photo->merge([
                    'pp_id' => rand(0,2147483647),
                    'pp_pdct_id' => $productData['pdct_id']
                ]);
                ProductPhoto::query()->create($photo->all());
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

            $product = Product::query()->where('pdct_id','=',$id)->with('inventories')->get();
            $photos = ProductPhoto::query()->where('pp_pdct_id','=',$id)->get();
            foreach ($photos as $photo)
            {
                $photo->pp_photo = base64_encode($photo->pp_photo);
            }

            if ($product->count() == 0) throw new Exception("Tidak Ada Data", 0);

            return response()->json([
                'product' => $product,
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
            'images.*' => 'mimes:jpeg,png,jpg|max:5120',
            'id' => 'required|integer',
            'kategori_id' => 'required',
            'kategori_nama' => 'required',
            'nama' => 'required|string',
            'harga' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        $updateProduct = [
            'pdct_kategori_id' => $request->kategori_id,
            'pdct_kategori_nama' => $request->kategori_nama,
            'pdct_nama' => $request->nama,
            'pdct_desc' => $request->has('desc') ? $request->desc : null,
            'pdct_harga' => $request->harga,
            'updated_by' => auth()->user()->name ?? 'system'
        ];

        $inventoryData = Inventory::query()->where('ivty_pdct_id','=',$request->id)->get();
        $updateInventory = [
            'ivty_pdct_nama' => $updateProduct['pdct_nama'],
            'ivty_pdct_stock' => $request->stock,
            'ivty_cause' => (
                $updateProduct['pdct_nama'] == $inventoryData[0]->ivty_pdct_nama ?
                    "" : $updateProduct['updated_by'] . " mengubah nama produk menjadi " . $updateProduct['pdct_nama']
            ).(
                $request->stock == $inventoryData[0]->ivty_pdct_stock ?
                    "" : "\n" . $updateProduct['updated_by'] . " mengubah stok produk menjadi " . $request->stock
            )
        ];

        $updatePromo = [];
        $existPromo = Promo::query()->where('prm_pdct_id','=',$request->id)->get();
        if ($existPromo->count() > 0) {
            $updatePromo = [
                'prm_harga_promo' => $request->harga - ($request->harga * ($existPromo[0]->prm_disc_percent/100))
            ];
        }

        $photoData = [];
        if ($request->has('imgLength') && $request->imgLength > 0)
        {
            for($i = 0; $i < $request->imgLength; $i++)
            {
                if ($request->hasFile('images'.$i))
                {
                    $file = $request->file('images'.$i);
                    $photoRequest = ImageProcessor::getImageThumbnail($file, $request->nama, 'pp_filename', 'pp_photo',$i);
                    array_push($photoData, $photoRequest);
                }
            }
        }

        DB::beginTransaction();
        try
        {
            $pdct_id = $request->id;
            Product::query()->where('pdct_id','=',$pdct_id)->update($updateProduct);
            Inventory::query()->where('ivty_pdct_id','=',$pdct_id)->update($updateInventory);
            if ($request->has('imgLength') && $request->imgLength > 0)
            {
                ProductPhoto::query()->where('pp_pdct_id','=',$pdct_id)->delete();
                foreach ($photoData as $photo)
                {
                    $photo->merge([
                        'pp_id' => rand(0,2147483647),
                        'pp_pdct_id' => $pdct_id
                    ]);
                    ProductPhoto::query()->create($photo->all());
                }
            }
            if ($existPromo->count() > 0) {
                Promo::query()->where('prm_pdct_id','=',$pdct_id)->update($updatePromo);
            }

            DB::commit();
            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Diubah';
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
     * Update the specified photo resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $resmsg = new ResponseMessage();
        $request->validate([
            'pp_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama' => 'required',
            'index' => 'required|integer'
        ]);

        $photo = null;
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $photo = ImageProcessor::getImageThumbnail($image, $request->nama, 'pp_filename', 'pp_photo', $request->index);
        }

        try
        {
            $pp_id = $request->pp_id;
            ProductPhoto::query()->where('pp_id','=',$pp_id)->update($photo->all());

            $resmsg->code = 1;
            $resmsg->message = 'Photo Berhasil Diubah';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
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

            Product::query()->where('pdct_id','=',$id)->delete();

            $resmsg->code = 1;
            $resmsg->message = 'Data Berhasil Dihapus';
        } 
        catch (Exception $ex)
        {
            if ($ex->getCode() == 23000)
            {
                $resmsg->code = 0;
                $resmsg->message = 'Gagal hapus, Product mempunyai Feedback';
            }
            else
            {
                // $resmsg->code = 0;
                // $resmsg->message = 'Data Gagal Dihapus';

                #region Code Testing
                $resmsg->code = $ex->getCode();
                $resmsg->message = $ex->getMessage();
                #endregion
            }
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
        $request->validate(['pp_id' => 'required|integer']);

        try
        {
            $pp_id = $request->pp_id;
            ProductPhoto::query()->where('pp_id','=',$pp_id)->delete();

            $resmsg->code = 1;
            $resmsg->message = 'Photo Berhasil Dihapus';
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
            // $resmsg->message = 'Photo Gagal Dihapus';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }

        return response()->json($resmsg);
    }
}
