<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DateTime as ModelsDateTime;
use App\Models\ImageProcessor;
use App\Models\Inventory;
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
        $datas = DB::table('order_products')
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
        foreach ($datas as $data) {
            $data->op_sum_biaya = number_format($data->op_sum_biaya, 2);
            $data->op_tanggal_order = date_format(
                DateTime::createFromFormat('Y-m-d H:i:s', $data->op_tanggal_order),
                'D, d M Y H:i:s'
            );
            if ($data->op_bukti_transfer_file != null)
                $data->op_bukti_transfer_file = base64_encode($data->op_bukti_transfer_file);
            if ($data->op_resi_file != null)
                $data->op_resi_file = base64_encode($data->op_resi_file);
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
            'cst_id' => 'required|numeric',
            'no_telp' => 'required',
            'pdct_id' => 'required|numeric',
            'pdct_qty' => 'required|numeric',
            'alamat_pemesanan' => 'required'
        ]);
        try 
        {
            if (preg_match('/[A-Za-z]/', $request->no_telp))
                throw new Exception("Nomor telpon tidak benar!", 0);
            $existPdct = Product::query()->where('pdct_id','=',$request->pdct_id)->with('promo')->get();
            if ($existPdct->count() == 0) throw new Exception("Data produk tidak ada",0);
            $harga = $existPdct[0]->promo != null ? $existPdct[0]->promo->prm_harga_promo : $existPdct[0]->pdct_harga;
            $biaya = intval($harga) * intval($request->pdct_qty);
            $orderProduct = [
                'op_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'op_cst_id' => $request->cst_id,
                'op_lokasi_pengiriman' => 'Belum di isi',
                'op_sum_harga_produk' => $biaya,
                'op_alamat_pemesanan' => $request->alamat_pemesanan,
                'op_tanggal_order' => ModelsDateTime::Now(),
                'op_status_order' => 0,
                'op_contact_customer' => 0,
                'op_note_to_admin' => $request->note,
                'op_sum_biaya' => $biaya
            ];
            $orderDetail = [
                'odp_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'odp_op_id' => $orderProduct['op_id'],
                'odp_pdct_id' => $existPdct[0]->pdct_id,
                'odp_pdct_kode' => $existPdct[0]->pdct_kode,
                'odp_pdct_harga' => $existPdct[0]->pdct_harga,
                'odp_pdct_qty' => $request->pdct_qty
            ];
            $csData = [
                'cst_no_telp' => $request->no_telp
            ];
            DB::beginTransaction();
            try
            {
                OrderProduct::query()->create($orderProduct);
                OrderDetailProduct::query()->create($orderDetail);
                Customer::query()->where('cst_id','=',$request->cst_id)->update($csData);

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
            $data[0]->op_tanggal_order = date_format(
                DateTime::createFromFormat('Y-m-d H:i:s', $data[0]->op_tanggal_order),
                'D, d M Y H:i:s'
            );
            if ($data[0]->op_bukti_transfer_file != null)
                $data[0]->op_bukti_transfer_file = base64_encode($data[0]->op_bukti_transfer_file);
            if ($data[0]->op_resi_file != null)
                $data[0]->op_resi_file = base64_encode($data[0]->op_resi_file);
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
            'op_contact_customer' => 'required|numeric',
            'op_bukti_transfer_file' => 'mimes:jpeg,png,jpg|max:2048',
            'op_resi_file' => 'mimes:jpeg,png,jpg|max:2048'
        ]);
        try
        {
            if (preg_match("/[A-Za-z]/", $id)) throw new Exception("Data Tidak Valid", 0);
            $existingOrder = OrderProduct::query()->where('op_id','=',$id)->with(['detail','customer'])->get();
            if ($existingOrder->count() == 0) throw new Exception("Data Order tidak ada", 0);

            $updateDetail = [
                'odp_pdct_qty' => $request->pdct_qty ?? $existingOrder[0]->detail->odp_pdct_qty
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
            $pajak =  $sumPdctPrice * ($request->op_persen_pajak/100);
            $updateOrder = [
                'op_lokasi_pengiriman' => $request->op_lokasi_pengiriman,
                'op_sum_harga_produk' => $sumPdctPrice,
                'op_harga_ongkir' => $request->op_harga_ongkir,
                'op_persen_pajak' => $request->op_persen_pajak,
                'op_nominal_pajak' => $pajak,
                'op_alamat_pemesanan' => $request->alamat_pemesanan,
                'op_sum_biaya' => $sumPdctPrice + $request->op_harga_ongkir + $pajak,
                'op_status_order' => $request->op_status_order,
                'op_contact_customer' => $request->op_contact_customer,
                'op_note_to_customer' => $request->op_note_to_customer
            ];
            if ($request->hasFile('op_bukti_transfer_file'))
            {
                $file = $request->file('op_bukti_transfer_file');
                $fileReq = ImageProcessor::getImageThumbnail($file,'BuktiTransfer','op_bukti_transfer_filename','op_bukti_transfer_file',$existingOrder[0]->customer->cst_name);
                $updateOrder = array_merge($updateOrder, $fileReq->all());
            }
            if ($request->hasFile('op_resi_file'))
            {
                $file = $request->file('op_resi_file');
                $fileReq = ImageProcessor::getImageThumbnail($file,'Resi','op_resi_filename','op_resi_file',$existingOrder[0]->customer->cst_name);
                $updateOrder = array_merge($updateOrder, $fileReq->all());
            }
            $updateInven = null;
            if ($updateOrder['op_status_order'] == 3) {
                $invenData = Inventory::query()->where('ivty_pdct_id','=',$existingOrder[0]->detail->odp_pdct_id)->get();
                $pdctStockLeft = $invenData[0]->ivty_pdct_stock - $updateDetail['odp_pdct_qty'];
                if ($pdctStockLeft < 0) throw new Exception("Stock Produk Kurang", 0);
                $updateInven = [
                    'ivty_pdct_stock' => $pdctStockLeft,
                    'ivty_cause' => $updateDetail['odp_pdct_qty'] . " produk telah dikirim ke " . $existingOrder[0]->customer->cst_name
                ];
            }

            DB::beginTransaction();
            try
            {
                OrderProduct::query()->where('op_id','=',$existingOrder[0]->op_id)->update($updateOrder);
                OrderDetailProduct::query()->where('odp_id','=',$existingOrder[0]->detail->odp_id)->update($updateDetail);
                if ($updateOrder['op_status_order'] == 3) {
                    Inventory::query()->where('ivty_pdct_id','=',$existingOrder[0]->detail->odp_pdct_id)->update($updateInven);
                }

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
            if ($ex->getCode() == 22001)
            {
                $resmsg->code = 0;
                $resmsg->message = 'Ukuran file tidak sesuai';
            }
            else
            {
                // $resmsg->code = 0;
                // $resmsg->message = 'Data Gagal Diubah';

                #region Code Testing
                $resmsg->code = $ex->getCode();
                $resmsg->message = $ex->getMessage();
                #endregion
            }
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
