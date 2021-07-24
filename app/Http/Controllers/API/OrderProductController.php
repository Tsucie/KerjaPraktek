<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DateTime as ModelsDateTime;
use App\Models\OrderDetailProduct;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ResponseMessage;
use DateTime;
use Exception;
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
        $resmsg = new ResponseMessage();
        $request->validate([
            'cst_id' => 'required|numeric',
            'pdct_id' => 'required|numeric',
            'pdct_qty' => 'required|numeric',
            'alamat_pemesanan' => 'required'
        ]);
        try 
        {
            $existPdct = Product::query()->where('pdct_id','=',$request->pdct_id)->get();
            if ($existPdct->count() == 0) throw new Exception("Data produk tidak ada",0);
            $orderProduct = [
                'op_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'op_cst_id' => $request->cst_id,
                'op_lokasi_pengiriman' => 'Belum di isi',
                'op_sum_harga_produk' => (int)$existPdct->pdct_harga * $request->pdct_qty,
                'op_alamat_pemesanan' => $request->alamat_pemesanan,
                'op_tanggal_order' => ModelsDateTime::Now(),
                'op_status_order' => 0,
                'op_contact_customer' => 0
            ];
            $orderDetail = [
                'odp_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'odp_op_id' => $orderProduct['op_id'],
                'odp_pdct_id' => $existPdct['pdct_id'],
                'odp_pdct_kode' => $existPdct['pdct_kode'],
                'odp_pdct_harga' => $existPdct['pdct_harga'],
                'odp_pdct_qty' => $request->pdct_qty
            ];
            DB::beginTransaction();
            try
            {
                OrderProduct::query()->create($orderProduct);
                OrderDetailProduct::query()->create($orderDetail);

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

            $data = OrderProduct::query()->where('op_id','=',$id)
                        ->with(['customer','detail','product'])->get();
            
            if ($data->count() == 0) throw new Exception("Tidak Ada Data", 0);
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
            'pdct_id' => 'numeric',
            'pdct_qty' => 'numeric',
            'alamat_pemesanan' => 'required',
            'op_lokasi_pengiriman' => 'required',
            'op_harga_ongkir' => 'numeric',
            'op_persen_pajak' => 'numeric',
            'op_nominal_pajak' => 'numeric',
            'op_status_order' => 'required|numeric',
            'op_contact_customer' => 'required|numeric'
        ]);
        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            $existingOrder = OrderProduct::query()->where('op_id','=',$id)->with('detail')->get();
            if ($existingOrder->count() == 0) throw new Exception("Data Order tidak ada", 0);
            $updateDetail = [
                'odp_pdct_qty' => $request->pdct_qty
            ];
            $sumPdctPrice = $existingOrder[0]->op_sum_harga_produk;
            if ($request->has('pdct_id') && $request->pdct_id != $existingOrder[0]->detail->odp_pdct_id)
            {
                $existPdct = Product::query()->where('pdct_id','=',$request->pdct_id)->get();
                if ($existPdct->count() > 0)
                {
                    array_merge($updateDetail, [
                        'odp_pdct_id' => $existPdct['pdct_id'],
                        'odp_pdct_kode' => $existPdct['pdct_kode'],
                        'odp_pdct_harga' => $existPdct['pdct_harga'],
                    ]);
                    $sumPdctPrice = $existPdct['pdct_harga'] * $updateDetail['odp_pdct_qty'];
                }
            }
            $updateOrder = [
                'op_lokasi_pengiriman' => $request->op_lokasi_pengiriman,
                'op_sum_harga_produk' => $sumPdctPrice,
                'op_harga_ongkir' => $request->op_harga_ongkir,
                'op_persen_pajak' => $request->op_persen_pajak,
                'op_nominal_pajak' => $sumPdctPrice * ($request->op_persen_pajak/100),
                'op_alamat_pemesanan' => $request->alamat_pemesanan,
                'op_status_order' => $request->op_status_order,
                'op_contact_customer' => $request->op_contact_customer
            ];
            DB::beginTransaction();
            try
            {
                OrderProduct::query()->where('op_id','=',$existingOrder[0]->op_id)->update($updateOrder);
                OrderDetailProduct::query()->where('odp_id','=',$existingOrder[0]->detail->odp_id)->update($updateDetail);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resmsg = new ResponseMessage();
        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);

            $existingOrder = OrderProduct::query()->where('op_id','=',$id)->with('detail')->get();
            if ($existingOrder->count() == 0) throw new Exception("Data Order tidak ada", 0);

            DB::beginTransaction();
            try
            {
                OrderDetailProduct::query()->where('odp_op_id','=',$existingOrder[0]->op_id)->delete();
                OrderProduct::query()->where('op_id','=',$existingOrder[0]->op_id)->delete();

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
